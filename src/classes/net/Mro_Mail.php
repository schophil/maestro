<?php

namespace Maestro\net;

/**
 * Mail class.
 */
class Mro_Mail
{

    private $from;      // FROM address
    private $to;        // array of TO addresses
    private $cc;        // array of CC addresses
    private $bcc;       // array of BCC addresses
    private $subject;   // the message subject
    private $content;   // the message body
    private $reply_to;  // the reply to address
    private $log;        // logger

    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->log = get_logger('/maestro/net/mail');
        $this->cc = array();
        $this->bcc = array();
        $this->to = array();
    }

    /**
     * Sets the reply-to address.
     * @param string $address A valid email address.
     */
    function setReplyTo($address)
    {
        $this->reply_to = $address;
    }

    /**
     * Adds a TO recepiant.
     * @param string $address A valid email address.
     */
    function addTo($address)
    {
        $this->to[] = $address;
    }

    /**
     * Sets the single TO recepiant.
     * @param string $address A valid email address.
     */
    function setTo($addresses)
    {
        $this->to = $addresses;
    }

    /**
     * Adds a CC recepiant.
     * @param string $address A valid email address.
     */
    function addCc($address)
    {
        $this->cc[] = $address;
    }

    /**
     * Adds a BCC recepiant.
     * @param string $address A valid email address.
     */
    function addBcc($address)
    {
        $this->bcc[] = $address;
    }

    /**
     * Sets the subject of the mail.
     * @param string $subject Text
     */
    function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Sets the email of the sender.
     * @param string $address A valid email address.
     */
    function setFrom($address)
    {
        $this->from = $address;
    }

    /**
     * Sends this message using a mail sender.
     */
    function send()
    {
        $sender = new Mro_MailSender();
        $my_reply_to = $this->reply_to;
        if ($my_reply_to == null) {
            $my_reply_to = $this->from;
        }
        return $sender->send($this->from, $this->to, $this->cc, $this->bcc, $this->subject,
            $this->content, $my_reply_to);
    }

    /**
     * Sets the content of the mail.
     * @param string $content Text.
     */
    function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Adds content to the already existing content.
     * @param string $content Text.
     */
    function appendContent($content)
    {
        if ($this->content == null) {
            $this->content = "";
        }
        $this->content = $this->content . $content;
    }
}
