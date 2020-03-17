<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317145901 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lab (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, video_link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lab_material (id INT AUTO_INCREMENT NOT NULL, lab_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, INDEX IDX_A6B25E08628913D5 (lab_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lab_result (id INT AUTO_INCREMENT NOT NULL, lab_id INT DEFAULT NULL, user_id INT DEFAULT NULL, is_complete TINYINT(1) NOT NULL, INDEX IDX_86B24747628913D5 (lab_id), INDEX IDX_86B24747A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lab_material ADD CONSTRAINT FK_A6B25E08628913D5 FOREIGN KEY (lab_id) REFERENCES lab (id)');
        $this->addSql('ALTER TABLE lab_result ADD CONSTRAINT FK_86B24747628913D5 FOREIGN KEY (lab_id) REFERENCES lab (id)');
        $this->addSql('ALTER TABLE lab_result ADD CONSTRAINT FK_86B24747A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lab_material DROP FOREIGN KEY FK_A6B25E08628913D5');
        $this->addSql('ALTER TABLE lab_result DROP FOREIGN KEY FK_86B24747628913D5');
        $this->addSql('DROP TABLE lab');
        $this->addSql('DROP TABLE lab_material');
        $this->addSql('DROP TABLE lab_result');
    }
}
