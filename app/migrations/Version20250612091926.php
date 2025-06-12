<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612091926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE users_addresses (users_id INT NOT NULL, addresses_id INT NOT NULL, INDEX IDX_9B70FF767B3B43D (users_id), INDEX IDX_9B70FF75713BC80 (addresses_id), PRIMARY KEY(users_id, addresses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses ADD CONSTRAINT FK_9B70FF767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses ADD CONSTRAINT FK_9B70FF75713BC80 FOREIGN KEY (addresses_id) REFERENCES addresses (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders ADD users_id INT DEFAULT NULL, ADD addresses_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5713BC80 FOREIGN KEY (addresses_id) REFERENCES addresses (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E52FFDEE67B3B43D ON orders (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E52FFDEE5713BC80 ON orders (addresses_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE packages ADD orders_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE packages ADD CONSTRAINT FK_9BB5C0A7CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9BB5C0A7CFFE9AD6 ON packages (orders_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_orders ADD orders_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_orders ADD CONSTRAINT FK_586555A3CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_586555A3CFFE9AD6 ON status_orders (orders_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_packages ADD packages_id INT DEFAULT NULL, CHANGE title title VARCHAR(50) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_packages ADD CONSTRAINT FK_66F677FCA871E03 FOREIGN KEY (packages_id) REFERENCES packages (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_66F677FCA871E03 ON status_packages (packages_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses DROP FOREIGN KEY FK_9B70FF767B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_addresses DROP FOREIGN KEY FK_9B70FF75713BC80
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_addresses
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_orders DROP FOREIGN KEY FK_586555A3CFFE9AD6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_586555A3CFFE9AD6 ON status_orders
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_orders DROP orders_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE packages DROP FOREIGN KEY FK_9BB5C0A7CFFE9AD6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9BB5C0A7CFFE9AD6 ON packages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE packages DROP orders_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE5713BC80
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E52FFDEE67B3B43D ON orders
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E52FFDEE5713BC80 ON orders
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE orders DROP users_id, DROP addresses_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_packages DROP FOREIGN KEY FK_66F677FCA871E03
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_66F677FCA871E03 ON status_packages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_packages DROP packages_id, CHANGE title title VARCHAR(100) NOT NULL
        SQL);
    }
}
