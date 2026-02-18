<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\SustainabilityRecord;
use Doctrine\ORM\EntityManagerInterface;

class EcoService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function generateEcoImpact(Appointment $appointment): SustainabilityRecord
    {
        $record = new SustainabilityRecord();
        $record->setAppointment($appointment);
        
        // Simulated calculations based on service category/duration
        $duration = $appointment->getService()->getDuration();
        
        // Logic: 10g carbon saved per minute of specialized eco-treatment (simulated)
        $record->setCarbonSaved($duration * 1.5); 
        $record->setWaterSaved($duration * 0.5); // 0.5L saved per minute
        
        // 1% of service price goes to RSC
        $price = (float) $appointment->getService()->getPrice();
        $record->setDonationAmount($price * 0.01);

        $this->em->persist($record);
        $this->em->flush();

        return $record;
    }
}
