<?php

namespace Maestro\net;

/**
 * The mail sending class.
 */
class Mro_MailSender
{

    private $log; // logger
    private $eol; // system specific end of line character

    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->log = get_logger('/maestro/net/mail');
        if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
            $this->eol = "\r\n";
        } elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
            $this->eol = "\r";
        } else {
            $this->eol = "\n";
        }
    }

    /**
     * Sends a mail.
     * @param string $from
     * @param array $to
     * @param array $cc
     * @param array $bcc
     * @param string $subject
     * @param string $content
     * @param string $reply_to
     * @return unknown
     */
    function send($from, $to, $cc, $bcc, $subject, $content, $reply_to)
    {
        $eol = $this->eol;
        // to field
        $mail_to = $this->appendAddresses($to);
        // cc
        $mail_cc = $this->appendAddresses($cc);
        // bcc
        $mail_bcc = $this->appendAddresses($bcc);
        // additional headers
        $headers = "From: <{$from}>{$eol}";
        $headers .= "Reply-To: <{$reply_to}>$eol";
        $headers .= "X-Sender: <{$mail_to}> $eol";
        $headers .= "X-Priority: 3{$eol}";
        $headers .= "Return-Path: <$from>{$eol}";
        $headers .= "X-Mailer:PHP 5.1{$eol}";
        $headers .= "MIME-Version: 1.0{$eol}";
        $headers .= "Content-Type: text/html; charset=iso-8859-1;{$eol}";
        if ($mail_cc != '') {
            $headers .= "Cc: {$mail_cc}{$eol}";
        }
        if ($mail_bcc != '') {
            $headers .= "Bcc: {$mail_bcc}{$eol}";
        }

        $this->log->debug("mailto: {$mail_to}");
        $this->log->debug("subject: {$subject}");
        $this->log->debug("headers: {$headers}");
        $this->log->debug("headers: {$headers}");
        $mailed = mail($mail_to, $subject, $content, $headers);
        $this->log->debug("mailed: {$mailed}");
        return $mailed;
    }

    private function appendAddresses($addresses, $enclosed)
    {
        $text = '';
        foreach ($addresses as $address) {
            if ($text != '') {
                $text .= ',';
            }
            if ($enclosed) {
                $text .= "<{$address}>";
            } else {
                $text .= $address;
            }
        }
        return $text;
    }
}
