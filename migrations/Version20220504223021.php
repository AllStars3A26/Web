<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504223021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE date_inscrit_u date_inscrit_u DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE desc_u desc_u VARCHAR(300) DEFAULT NULL, CHANGE type_e type_e VARCHAR(255) DEFAULT NULL, CHANGE date_e date_e DATE DEFAULT NULL, CHANGE code code VARCHAR(255) DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE FULLTEXT INDEX IDX_8D93D649C8DC6071 ON user (psudo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8D93D649C8DC6071 ON user');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE image image VARCHAR(255) DEFAULT \'NULL\', CHANGE date_inscrit_u date_inscrit_u DATETIME DEFAULT \'current_timestamp()\', CHANGE desc_u desc_u VARCHAR(300) DEFAULT \'NULL\', CHANGE type_e type_e VARCHAR(255) DEFAULT \'NULL\', CHANGE date_e date_e DATE DEFAULT \'NULL\', CHANGE code code VARCHAR(255) DEFAULT \'NULL\', CHANGE token token VARCHAR(255) DEFAULT \'NULL\'');
    }
}
