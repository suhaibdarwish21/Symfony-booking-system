<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260523135027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDED62B0FA FOREIGN KEY (time_slot_id) REFERENCES time_slot (id)');
        $this->addSql('ALTER TABLE service ADD image_name VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE time_slot ADD CONSTRAINT FK_1B3294AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDED62B0FA');
        $this->addSql('ALTER TABLE service ADD image VARCHAR(255) NOT NULL, DROP image_name, DROP updated_at');
        $this->addSql('ALTER TABLE time_slot DROP FOREIGN KEY FK_1B3294AED5CA9E6');
    }
}
