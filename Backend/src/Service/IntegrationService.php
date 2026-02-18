<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\BeforeAfterGallery;

class IntegrationService
{
    public function generateGoogleCalendarUrl(Appointment $appointment): string
    {
        $start = $appointment->getStartAt()->format('Ymd\THis\Z');
        $end = $appointment->getEndAt()->format('Ymd\THis\Z');
        $title = urlencode('Cita en LuxeSalon: ' . $appointment->getService()->getName());
        $details = urlencode('Recordatorio de tu cita con ' . $appointment->getEmployee()->getFullName());
        $location = urlencode($appointment->getSalon() ? $appointment->getSalon()->getAddress() : 'LuxeSalon');

        return "https://www.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$start}/{$end}&details={$details}&location={$location}";
    }

    public function generateSocialMediaTemplate(BeforeAfterGallery $photo): array
    {
        $customerName = $photo->getCustomer()->getName();
        $serviceName = $photo->getAppointment()->getService()->getName();
        
        return [
            'instagram' => [
                'caption' => "✨ Transformación increíble para {$customerName} ✨\n\nTrabajo realizado: #{$serviceName}\n📍 Visítanos en LuxeSalon\n\n#PeluqueriaLujo #Estetica #LuxeSalon",
                'hashtags' => ['#LuxeSalon', '#HairStyle', '#Beauty'],
            ],
            'facebook' => [
                'text' => "¡Mira este resultado! Hoy {$customerName} se ha ido feliz tras su servicio de {$serviceName}. Reserva tu cita ahora.",
            ]
        ];
    }
}
