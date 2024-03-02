<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220223807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE complaint_response (id INT AUTO_INCREMENT NOT NULL, id_complaint_id INT NOT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_22622279CE6F6DFC (id_complaint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE complaint_response ADD CONSTRAINT FK_22622279CE6F6DFC FOREIGN KEY (id_complaint_id) REFERENCES complaint (id)');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFBCE6F6DFC');
        $this->addSql('DROP TABLE response');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, id_complaint_id INT NOT NULL, date DATE NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_3E7B0BFBCE6F6DFC (id_complaint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBCE6F6DFC FOREIGN KEY (id_complaint_id) REFERENCES complaint (id)');
        $this->addSql('ALTER TABLE complaint_response DROP FOREIGN KEY FK_22622279CE6F6DFC');
        $this->addSql('DROP TABLE complaint_response');
    }
}
