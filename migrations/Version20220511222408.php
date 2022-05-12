<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511222408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cours');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1EDB3A7AE');
        $this->addSql('ALTER TABLE terrain CHANGE id_equipe_id id_equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id_cours INT AUTO_INCREMENT NOT NULL, titre VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nome VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, imc VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nb_heure INT NOT NULL, PRIMARY KEY(id_cours)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE terrain DROP FOREIGN KEY FK_C87653B1EDB3A7AE');
        $this->addSql('ALTER TABLE terrain CHANGE id_equipe_id id_equipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE terrain ADD CONSTRAINT FK_C87653B1EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
