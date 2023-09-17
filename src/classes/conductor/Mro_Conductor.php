<?php

namespace Maestro\conductor;

use Maestro\logic\Mro_LogicProcessor;
use Maestro\Mro_Exception;
use Monolog\Registry;
use Psr\Log\LoggerInterface;

/**
 * The action parameter name in the HTTP request.
 */
define('ACTION_PARAMETER', 'action');

/**
 * The conductor class.
 * Conductors are the central entry point of a web application. On request the
 * conductor will:
 * <ol>
 * <li>initialze the context</li>
 * <li>retrieve the action corresponding to the request</li>
 * <li>execute the actions logic if any</li>
 * <li>retrieve the page for the return action code</li>
 * <li>forward the request to the page hence causing the resulting HTML page to be viewed</li>
 * </ol>
 * If any error occured the conductor should show a configured error page.
 * The logic will be executed by a logic execution chain. This chain will be
 * responsible for opening database session, hanlding transactions, execute the
 * logic...
 */
class Mro_Conductor
{

    private LoggerInterface $log;
    private $actionProvider;
    private $pageProvider;
    private $logicProcessor;
    private $actionProcessor;

    /**
     * The default constructor.
     *
     * @param Mro_ActionProvider $actionProvider The action provider to use.
     * @param Mro_PageProvider $pageProvider The page provider to use.
     * @param Mro_LogicProcessor $logicProcessor The logic processor.
     * @param Mro_ActionProcessor $actionProcessor The action processor.
     */
    function __construct(Mro_ActionProvider $actionProvider,
                         Mro_PageProvider $pageProvider,
                         Mro_LogicProcessor $logicProcessor,
                         Mro_ActionProcessor $actionProcessor)
    {
        $this->actionProvider = $actionProvider;
        $this->pageProvider = $pageProvider;
        $this->logicProcessor = $logicProcessor;
        $this->actionProcessor = $actionProcessor;
        $this->log = Registry::getInstance('conductor');
    }

    /**
     * Start processing the request(s).
     */
    function start()
    {
        $this->log->debug('Starting conductor session');
        $this->handle($_POST, $_GET);
    }

    private function handle($post, $get)
    {
        $context = null;
        if (isset($post[ACTION_PARAMETER])) {
            $context = new Mro_Context($post);
        } else {
            $context = new Mro_Context($get);
        }
        $context->setPara('maestro-info', maestro_info());
        $this->execute($context);
    }

    private function execute(Mro_Context $context)
    {
        $nextPage = null;
        try {
            // get the action id from the context
            $actionName = $context->getArg(ACTION_PARAMETER);
            // search the action
            $action = $this->getAction($actionName);
            $this->log->debug('Executing action', ['action' => $action->getName()]);

            // pass action through the action processor; the result may be a
            // redirect to another page than the one planned in the action,
            // for example because of security
            $nextPageId = null;
            if (isset($this->actionProcessor)) {
                $this->log->debug('Processing action', ['action' => $action->getName()]);
                $nextPageId = $this->actionProcessor->handle($action, $context);
            }

            $this->checkHeader($context);

            // if no page id was received from processing the action,
            // we can execute the action logic
            if (is_null($nextPageId)) {
                // execute logic
                $logic = $action->getLogic($context);
                if (!is_null($logic)) {
                    $this->log->debug('Processing action logic');
                    $this->logicProcessor->handle($logic, $context);
                }
                // set next page
                $nextPageId = $action->getPageId($context);
            }

            $this->checkHeader($context);
            $this->log->debug('Next page name', ['page' => $nextPageId]);
            $nextPage = $this->getPage($nextPageId);

            $pageLogic = $nextPage->logic;
            if (!is_null($pageLogic)) {
                $this->logicProcessor->handle($pageLogic, $context);
            }

            $this->checkHeader($context);

        } catch (\Exception $e) {
            $nextPage = $this->pageProvider->getErrorPage();
            $context->setPara('error', $e);
        }

        $this->logicProcessor->cleanUp();

        // if a header was set, send it
        $header = $context->getHeader();
        if (!is_null($header)) {
            $header->send();
        }

        if (!is_null($nextPage)) {
            $this->log->debug("redirecting to {$nextPageId}");
            $nextPage->show($context);
        }
    }

    private function checkHeader($context)
    {
        $this->log->debug('checking header');

        // if a header was set, send it
        $header = $context->getHeader();
        if (!is_null($header) && $header->isExitAfterSend()) {
            $this->log->debug('sending header early');

            // clean up
            $this->logicProcessor->cleanUp();

            // here after the execution breaks normally
            $header->send();
        }
    }

    private function getPage($pageName)
    {
        $page = null;
        if (is_null($pageName)) {
            $page = $this->pageProvider->getDefaultPage();
        } else {
            $page = $this->pageProvider->get($pageName);
            if (is_null($page)) {
                throw new Mro_Exception("no page for name {$pageName}");
            }
        }
        return $page;
    }

    private function getAction($actionName)
    {
        $action = null;
        if (is_null($actionName)) {
            $action = $this->actionProvider->getDefaultAction();
        } else {
            $action = $this->actionProvider->get($actionName);
            if (is_null($action)) {
                throw new Mro_Exception("no action for name {$actionName}");
            }
        }
        return $action;
    }
}
