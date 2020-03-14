<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311165327 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_question (id INT AUTO_INCREMENT NOT NULL, tests_id INT DEFAULT NULL, questions_id INT DEFAULT NULL, INDEX IDX_23944218F5D80971 (tests_id), INDEX IDX_23944218BCB134CE (questions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_result (id INT AUTO_INCREMENT NOT NULL, students_id INT DEFAULT NULL, tests_id INT DEFAULT NULL, INDEX IDX_84B3C63D1AD8D010 (students_id), INDEX IDX_84B3C63DF5D80971 (tests_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE test_question ADD CONSTRAINT FK_23944218F5D80971 FOREIGN KEY (tests_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE test_question ADD CONSTRAINT FK_23944218BCB134CE FOREIGN KEY (questions_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63D1AD8D010 FOREIGN KEY (students_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE test_result ADD CONSTRAINT FK_84B3C63DF5D80971 FOREIGN KEY (tests_id) REFERENCES test (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE test_question DROP FOREIGN KEY FK_23944218F5D80971');
        $this->addSql('ALTER TABLE test_result DROP FOREIGN KEY FK_84B3C63DF5D80971');
        $this->addSql('ALTER TABLE test_question DROP FOREIGN KEY FK_23944218BCB134CE');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE test_question');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE test_result');
    }
}
