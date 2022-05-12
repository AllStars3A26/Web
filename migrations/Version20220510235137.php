<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510235137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id_cours INT AUTO_INCREMENT NOT NULL, titre VARCHAR(20) NOT NULL, nome VARCHAR(20) NOT NULL, type VARCHAR(20) NOT NULL, imc VARCHAR(20) NOT NULL, nb_heure INT NOT NULL, PRIMARY KEY(id_cours)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id_seance INT AUTO_INCREMENT NOT NULL, cours_cp INT DEFAULT NULL, date_seance DATE NOT NULL, heure_seance VARCHAR(20) NOT NULL, nom_T VARCHAR(20) NOT NULL, nom_E VARCHAR(20) NOT NULL, nb_participants INT DEFAULT NULL, borderColor VARCHAR(20) NOT NULL, textColor VARCHAR(20) NOT NULL, backgroundColor VARCHAR(20) NOT NULL, INDEX fk (cours_cp), PRIMARY KEY(id_seance)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E9EFA4447 FOREIGN KEY (cours_cp) REFERENCES cours (id_cours)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E9EFA4447');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE seance');
    }
}
