<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510201330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1EDB3A7AE');
        $this->addSql('DROP INDEX IDX_C87653B1EDB3A7AE ON terrain');
        $this->addSql('ALTER TABLE terrain DROP id_equipe_id, CHANGE nom_terrain nom_terrain VARCHAR(25) NOT NULL, CHANGE type_terrain type_terrain VARCHAR(25) NOT NULL, CHANGE description_terrain description_terrain VARCHAR(255) NOT NULL, CHANGE adresse_terrain adresse_terrain VARCHAR(50) NOT NULL, CHANGE disponibilite disponibilite INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE terrain ADD id_equipe_id INT NOT NULL, CHANGE nom_terrain nom_terrain VARCHAR(25) DEFAULT NULL, CHANGE type_terrain type_terrain VARCHAR(25) DEFAULT NULL, CHANGE description_terrain description_terrain VARCHAR(255) DEFAULT NULL, CHANGE adresse_terrain adresse_terrain VARCHAR(50) DEFAULT NULL, CHANGE disponibilite disponibilite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_C87653B1EDB3A7AE ON terrain (id_equipe_id)');
    }
}
