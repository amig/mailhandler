<?php

namespace mailHandler;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class smtpMailHandler {

  protected $mail;

  public function __construct($config) {
    $this->mail = new PHPMailer(true);
    $this->mail->isSMTP();
    $this->mail->Host = $config['server'];
    $this->mail->SMTPAuth = true;
    $this->mail->Username = $config['username'];
    $this->mail->Password = $config['password'];
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->Port = $config['port'];

    return $this;
  }

  /**
   *
   * @param mixed $to A single email address or an array of email addresses
   * @param email $from
   * @param string $inReplyTo
   * @return $this
   */
  public function biuldEnvelop($to, $from, $inReplyTo = '') {
    $this->mail->setFrom($from);
    if (is_array($to)) {
      foreach ($to as $t) {
        $this->mail->addAddress($t);
      }
    }
    else {
      $this->mail->addAddress($to);
    }

    if (!empty($inReplyTo)) {
      $this->mail->addCustomHeader('in_reply_to', $inReplyTo);
      $this->mail->addCustomHeader('references', $inReplyTo);
    }

    return $this;
  }

  /**
   *
   * @param mixed $cc A single email address or an array of email addresses
   */
  public function addCCtoEnvelop($cc) {
    if (is_array($cc)) {
      foreach ($cc as $c) {
        $this->mail->addCC($c);
      }
    }
    else {
      $this->mail->addCC($cc);
    }

    return $this;
  }

  /**
   *
   * @param mixed $bcc A single email address or an array of email addresses
   * @return $this
   */
  public function addBCCtoEnvelop($bcc) {
    if (is_array($bcc)) {
      foreach ($bcc as $c) {
        $this->mail->addBCC($c);
      }
    }
    else {
      $this->mail->addBCC($bcc);
    }

    return $this;
  }

  /**
   *
   * @param string $path
   * @return $this
   */
  public function addAttachmentToEnvelop($path) {
    $this->mail->addAttachment($path);

    return $this;
  }

  /**
   *
   * @param string $subject
   * @param html $message
   * @return $this
   */
  public function biuldBody($subject, $message = '') {
    $this->mail->isHTML(true);
    $this->mail->Subject = $subject;
    $this->mail->Body = $message;
    $this->mail->AltBody = strip_tags($message);

    return $this;
  }

  /**
   * Use the send method to send the mail object
   */
  public function sendMail() {
    $this->mail->send();
  }

}
