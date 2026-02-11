<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211113131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cash_register (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, opening_balance NUMERIC(10, 2) NOT NULL, closing_balance NUMERIC(10, 2) NOT NULL, expected_balance NUMERIC(10, 2) NOT NULL, total_card NUMERIC(10, 2) NOT NULL, total_cash NUMERIC(10, 2) NOT NULL, total_online NUMERIC(10, 2) NOT NULL, notes VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cash_register');
    }
}
