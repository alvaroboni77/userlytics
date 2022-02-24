<?php

namespace App\Service;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailerService
{
    private $qrGeneratorService;

    function __construct(QrGeneratorService $qrGeneratorService)
    {
        $this->qrGeneratorService = $qrGeneratorService;
    }

    public function sendMail($assistant)
    {
        $mail = new PHPMailer(true);
        $qrCode = $this->qrGeneratorService->getQrCode();
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'xxxxxxx@gmail.com';
            $mail->Password   = 'xxxxxxx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->setFrom('me@example.com', 'TestCompany');
            $mail->addAddress($assistant->getEmail(), $assistant->getName());
            $mail->addStringEmbeddedImage($qrCode, 'qr', 'ticket.png');
            $mail->isHTML(true);
            $mail->Subject = 'Ticket reservation';
            $mail->Body    = '<img src="cid:qr"/>';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}