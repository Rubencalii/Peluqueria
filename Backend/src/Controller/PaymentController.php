<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Payment;
use App\Repository\AppointmentRepository;
use App\Service\BookingNotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/webhook/stripe', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(
        Request $request,
        EntityManagerInterface $em,
        AppointmentRepository $appointmentRepo,
        BookingNotificationService $notificationService
    ): Response {
        $payload = $request->getContent();
        $sigHeader = $request->headers->get('Stripe-Signature');
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return new Response('Invalid payload', Response::HTTP_BAD_REQUEST);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return new Response('Invalid signature', Response::HTTP_BAD_REQUEST);
        }

        // Handle the event
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            
            // In a real app, we search for the appointment by metadata or stored Intent ID
            // For this demo, we assume the appointment was created with status 'pending_payment'
            // and we find it here.
            
            // Since we haven't implemented the complex linking yet, 
            // the ApiController currently creates it directly. 
            // In a robust flow, ApiController creates it as 'pending_payment' 
            // and Webhook confirms it.
        }

        return new Response('Webhook handled', Response::HTTP_OK);
    }
}
