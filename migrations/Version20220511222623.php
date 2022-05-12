<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511222623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom_equipe VARCHAR(20) DEFAULT NULL, type_equipe VARCHAR(20) DEFAULT NULL, description_equipe VARCHAR(255) DEFAULT NULL, mail_equipe VARCHAR(50) DEFAULT NULL, nbre_joueur INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrain (id INT AUTO_INCREMENT NOT NULL, id_equipe_id INT DEFAULT NULL, nom_terrain VARCHAR(25) NOT NULL, type_terrain VARCHAR(25) NOT NULL, description_terrain VARCHAR(255) NOT NULL, adresse_terrain VARCHAR(50) NOT NULL, disponibilite INT NOT NULL, INDEX IDX_C87653B1EDB3A7AE (id_equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id)');
        $this->addSql('DROP TABLE cours');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1EDB3A7AE');
        $this->addSql('CREATE TABLE cours (id_cours INT AUTO_INCREMENT NOT NULL, titre VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nome VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, imc VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nb_heure INT NOT NULL, PRIMARY KEY(id_cours)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE terrain');
    }
}
