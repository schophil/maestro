<?php

namespace Maestro\html;

class Mro_HtmlBlock
{

    private array $content = [];

    /**
     * Adds a HTML element to this block.
     * @param Mro_HtmlElement $html
     */
    function add(Mro_HtmlElement $html)
    {
        $this->content[] = $html;
    }

    /**
     * Collects all the HTML of each element.
     * No wrapper element is added.
     * @return string The collected HTML.
     */
    function createHtml(): string
    {
        $html = '';
        foreach ($this->content as $element) {
            $html = $this->appendElement($element, $html);
        }
        return $html;
    }

    private function appendElement(Mro_HtmlElement $element, string $html): string
    {
        $html .= $element->createHtml();
        return $html;
    }
}
