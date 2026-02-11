<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211095106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, status VARCHAR(20) NOT NULL, payment_method VARCHAR(20) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, customer_id INT NOT NULL, employee_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_FE38F8449395C3F3 (customer_id), INDEX IDX_FE38F8448C03F15C (employee_id), INDEX IDX_FE38F844ED5CA9E6 (service_id), INDEX idx_appointment_start (start_at), INDEX idx_appointment_status (status), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employee_schedule (id INT AUTO_INCREMENT NOT NULL, day_of_week SMALLINT NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, is_active TINYINT NOT NULL, employee_id INT NOT NULL, INDEX IDX_CA07403C8C03F15C (employee_id), UNIQUE INDEX unique_employee_day (employee_id, day_of_week), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(8, 2) NOT NULL, method VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, transaction_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, paid_at DATETIME DEFAULT NULL, appointment_id INT NOT NULL, UNIQUE INDEX UNIQ_6D28840DE5B533F9 (appointment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, duration INT NOT NULL, price NUMERIC(8, 2) NOT NULL, category VARCHAR(50) DEFAULT NULL, active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, surname VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, specialties JSON DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8449395C3F3 FOREIGN KEY (customer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8448C03F15C FOREIGN KEY (employee_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE employee_schedule ADD CONSTRAINT FK_CA07403C8C03F15C FOREIGN KEY (employee_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8449395C3F3');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8448C03F15C');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844ED5CA9E6');
        $this->addSql('ALTER TABLE employee_schedule DROP FOREIGN KEY FK_CA07403C8C03F15C');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DE5B533F9');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE employee_schedule');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
