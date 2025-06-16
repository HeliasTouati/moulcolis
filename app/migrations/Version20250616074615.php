<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616074615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses DROP FOREIGN KEY FK_9B70FF75713BC80
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses DROP FOREIGN KEY FK_9B70FF767B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_addresses
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE users_addresses (users_id INT NOT NULL, addresses_id INT NOT NULL, INDEX IDX_9B70FF767B3B43D (users_id), INDEX IDX_9B70FF75713BC80 (addresses_id), PRIMARY KEY(users_id, addresses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses ADD CONSTRAINT FK_9B70FF75713BC80 FOREIGN KEY (addresses_id) REFERENCES addresses (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses ADD CONSTRAINT FK_9B70FF767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
    }
}
