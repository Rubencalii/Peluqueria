<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- Create Admin ---
        $admin = new User();
        $admin->setEmail('admin@luxesalon.com');
        $admin->setName('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // --- Create Employees ---
        $employees = [];
        $names = ['Laura Martínez', 'Carlos Ruiz', 'Ana Torres'];
        $emails = ['laura@luxesalon.com', 'carlos@luxesalon.com', 'ana@luxesalon.com'];
        
        foreach ($names as $i => $name) {
            $employee = new User();
            $employee->setEmail($emails[$i]);
            $employee->setName($name);
            $employee->setRoles(['ROLE_EMPLOYEE']);
            $employee->setPassword($this->passwordHasher->hashPassword($employee, 'emp123'));
            $manager->persist($employee);
            $employees[] = $employee;
        }

        // --- Create Services ---
        $services = [
            ['Corte y Peinado', 'Corte profesional con acabado premium.', 35.00, 45],
            ['Color Completo', 'Tinte integral con productos de alta gama.', 65.00, 90],
            ['Mechas Balayage', 'Técnica de aclarado natural.', 120.00, 150],
            ['Tratamiento Hidratante', 'Hidratación profunda para cabellos secos.', 25.00, 30],
            ['Corte Caballero', 'Corte clásico o moderno para hombre.', 20.00, 30],
        ];

        $serviceEntities = [];
        foreach ($services as $data) {
            $service = new Service();
            $service->setName($data[0]);
            $service->setDescription($data[1]);
            $service->setPrice($data[2]);
            $service->setDuration($data[3]);
            $service->setActive(true);
            $manager->persist($service);
            $serviceEntities[] = $service;
        }

        // --- Create Customers ---
        $customer = new User();
        $customer->setEmail('maria@example.com');
        $customer->setName('María García');
        $customer->setPhone('+34 600 123 456');
        $customer->setRoles(['ROLE_CUSTOMER']);
        $customer->setPassword($this->passwordHasher->hashPassword($customer, 'maria123'));
        $manager->persist($customer);

        // --- Create some Appointments for today ---
        $today = new \DateTime('today');
        
        for ($i = 0; $i < 5; $i++) {
            $appointment = new Appointment();
            $appointment->setCustomer($customer);
            $appointment->setEmployee($employees[array_rand($employees)]);
            $appointment->setService($serviceEntities[array_rand($serviceEntities)]);
            
            $start = (clone $today)->setTime(9 + $i*2, 0);
            $appointment->setStartAt($start);
            $appointment->setEndAt((clone $start)->modify('+45 minutes'));
            $appointment->setStatus(Appointment::STATUS_CONFIRMED);
            
            $manager->persist($appointment);
        }

        $manager->flush();
    }
}
