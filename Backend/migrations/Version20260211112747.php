<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211112747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD stripe_payment_intent_id VARCHAR(255) NOT NULL, DROP method, DROP transaction_id, DROP paid_at, CHANGE amount amount NUMERIC(10, 2) NOT NULL, CHANGE status status VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD method VARCHAR(20) NOT NULL, ADD transaction_id VARCHAR(255) DEFAULT NULL, ADD paid_at DATETIME DEFAULT NULL, DROP stripe_payment_intent_id, CHANGE amount amount NUMERIC(8, 2) NOT NULL, CHANGE status status VARCHAR(20) NOT NULL');
    }
}
