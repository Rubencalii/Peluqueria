<?php

namespace App\Service;

use App\Entity\Service;

class AiAdvancedService
{
    public function processChatQuery(string $query): array
    {
        $query = strtolower($query);
        
        // Simulated NLP/Intent logic
        if (str_contains($query, 'reservar') || str_contains($query, 'cita')) {
            return [
                'intent' => 'booking',
                'response' => '¡Claro! Puedo ayudarte a reservar. ¿Qué servicio buscas (corte, color, etc.) y para qué día?',
                'action_required' => true,
            ];
        }

        if (str_contains($query, 'precio') || str_contains($query, 'cuánto cuesta')) {
            return [
                'intent' => 'pricing',
                'response' => 'Nuestros precios varían según el servicio. Por ejemplo, un corte premium son 25€. ¿Quieres ver el catálogo completo?',
                'action_required' => false,
            ];
        }

        if (str_contains($query, 'horario') || str_contains($query, 'abierto')) {
            return [
                'intent' => 'hours',
                'response' => 'Abrimos de Lunes a Sábado de 10:00 a 20:00. ¿Quieres que compruebe si hay hueco hoy?',
                'action_required' => false,
            ];
        }

        return [
            'intent' => 'unknown',
            'response' => 'Lo siento, no he entendido eso. Puedo ayudarte con reservas, precios u horarios. ¿Qué necesitas?',
            'action_required' => false,
        ];
    }

    public function calculateDynamicPrice(Service $service, \DateTimeInterface $time): array
    {
        $hour = (int) $time->format('H');
        $dayOfWeek = (int) $time->format('N');
        $basePrice = (float) $service->getPrice();
        $multiplier = 1.0;
        $reason = 'Precio base estándar.';

        // Peaks: Fridays and Saturdays (5, 6)
        if ($dayOfWeek >= 5) {
            $multiplier += 0.15;
            $reason = 'Recargo por alta demanda en fin de semana (+15%).';
        }

        // Night peak: 18:00 - 20:00
        if ($hour >= 18) {
            $multiplier += 0.10;
            $reason .= ' Recargo por hora punta tarde (+10%).';
        }

        // Morning gap: 10:00 - 12:00 (Mon-Thu)
        if ($dayOfWeek <= 4 && $hour >= 10 && $hour <= 12) {
            $multiplier -= 0.20;
            $reason = 'Descuento por reserva en franja valle (-20%).';
        }

        $finalPrice = round($basePrice * $multiplier, 2);

        return [
            'basePrice' => $basePrice,
            'finalPrice' => $finalPrice,
            'difference' => round($finalPrice - $basePrice, 2),
            'reason' => $reason,
        ];
    }
}
