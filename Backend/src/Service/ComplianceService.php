<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Incident;
use Doctrine\ORM\EntityManagerInterface;

class ComplianceService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function recordIncident(User $client, string $type, string $description, string $severity = Incident::SEVERITY_MEDIUM): Incident
    {
        $incident = new Incident();
        $incident->setClient($client);
        $incident->setType($type);
        $incident->setDescription($description);
        $incident->setSeverity($severity);

        $this->em->persist($incident);
        $this->em->flush();

        return $incident;
    }

    public function checkMedicalAlerts(User $client): array
    {
        $repo = $this->em->getRepository(Incident::class);
        $alerts = $repo->findBy([
            'client' => $client,
            'type' => 'medical_alert',
            'status' => Incident::STATUS_OPEN
        ]);

        return array_map(fn($a) => [
            'severity' => $a->getSeverity(),
            'description' => $a->getDescription()
        ], $alerts);
    }
}
