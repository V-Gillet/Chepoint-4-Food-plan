<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201091312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weight_history (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX IDX_D87E442FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weight_history ADD CONSTRAINT FK_D87E442FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE height height DOUBLE PRECISION DEFAULT NULL, CHANGE goal goal VARCHAR(50) DEFAULT NULL, CHANGE sexe sexe VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weight_history DROP FOREIGN KEY FK_D87E442FA76ED395');
        $this->addSql('DROP TABLE weight_history');
        $this->addSql('ALTER TABLE user CHANGE height height DOUBLE PRECISION NOT NULL, CHANGE goal goal VARCHAR(50) NOT NULL, CHANGE sexe sexe VARCHAR(50) NOT NULL');
    }
}
