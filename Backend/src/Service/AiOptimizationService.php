<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\AppointmentRepository;
use App\Repository\ServiceRepository;

class AiOptimizationService
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private ServiceRepository $serviceRepository
    ) {}

    public function getUpsellingSuggestions(User $customer): array
    {
        $history = $customer->getAppointmentsAsCustomer();
        $servicesUsed = [];
        
        foreach ($history as $appointment) {
            if ($appointment->getStatus() === Appointment::STATUS_COMPLETED) {
                $servicesUsed[] = $appointment->getService()->getName();
            }
        }

        $suggestions = [];
        $uniqueServices = array_unique($servicesUsed);

        // Simulated AI Rules
        if (in_array('Corte Caballer', $uniqueServices) || in_array('Corte y Peinado', $uniqueServices)) {
            $suggestions[] = [
                'service' => 'Tratamiento Capilar Hidratante',
                'reason' => 'Basado en tu frecuencia de corte, este tratamiento mantendrá tu cabello sano.',
                'probability' => 0.85
            ];
        }

        if (in_array('Mechas', $uniqueServices) || in_array('Color completo', $uniqueServices)) {
            $suggestions[] = [
                'service' => 'Mascarilla Protectora de Color',
                'reason' => 'Recomendado para prolongar el brillo de tus mechas.',
                'probability' => 0.92
            ];
        }

        // Default suggestion if no history
        if (empty($suggestions)) {
            $suggestions[] = [
                'service' => 'Servicio Completo Premium',
                'reason' => 'Nuestros clientes nuevos suelen amar este servicio integral.',
                'probability' => 0.50
            ];
        }

        return $suggestions;
    }

    public function optimizeSchedules(): array
    {
        // AI Logic: Detect gaps and high-demand peaks
        // This would return suggested shift adjustments
        return [
            'peakHours' => ['10:00', '18:00'],
            'resourceOptimization' => 'Considerar refuerzo el Viernes tarde basado en el historial de reservas.',
            'efficiencyScore' => 0.88
        ];
    }
}
