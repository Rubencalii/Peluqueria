<?php

namespace App\Service;

use App\Entity\Appointment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class BookingNotificationService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmation(Appointment $appointment): void
    {
        $email = (new Email())
            ->from('noreply@luxesalon.com')
            ->to($appointment->getCustomer()->getEmail())
            ->subject('Confirmación de tu reserva - LuxeSalon')
            ->html($this->twig->render('emails/booking_confirmation.html.twig', [
                'appointment' => $appointment
            ]));

        $this->mailer->send($email);
    }

    public function sendReminder(Appointment $appointment): void
    {
        $email = (new Email())
            ->from('noreply@luxesalon.com')
            ->to($appointment->getCustomer()->getEmail())
            ->subject('Recordatorio de tu cita mañana - LuxeSalon')
            ->html($this->twig->render('emails/booking_reminder.html.twig', [
                'appointment' => $appointment
            ]));

        $this->mailer->send($email);
    }
}
