<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        try {
            $email = (new Email())
                ->from('guesmimelek928@gmail.com')
                ->to($to)
                ->subject($subject)
                ->text($body);
    
            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Log the exception or handle it in a specific way
            // For example, you could log it using Monolog:
            // $logger->error('Failed to send email: ' . $e->getMessage());
            throw new \Exception('Failed to send email: ' . $e->getMessage());
        }
    }
}
