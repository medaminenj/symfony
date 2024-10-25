<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025102904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serveur ADD restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_77CC53A6B1E7706E ON serveur (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6B1E7706E');
        $this->addSql('DROP INDEX IDX_77CC53A6B1E7706E ON serveur');
        $this->addSql('ALTER TABLE serveur DROP restaurant_id');
    }
}
