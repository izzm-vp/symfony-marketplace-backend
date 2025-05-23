<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522163123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f09fcdaeaaa
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f09ae945c60
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f09e88cce5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_52ea1f09e88cce5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_52ea1f09ae945c60
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_52ea1f09fcdaeaaa
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD order_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD size_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD color_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP order_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP size_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP color_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09498DA827 FOREIGN KEY (size_id) REFERENCES size (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F097ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F09498DA827 ON order_item (size_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F097ADA1FB5 ON order_item (color_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09498DA827
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F097ADA1FB5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_52EA1F098D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_52EA1F09498DA827
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_52EA1F097ADA1FB5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD order_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD size_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD color_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP order_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP size_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP color_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f09fcdaeaaa FOREIGN KEY (order_id_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f09ae945c60 FOREIGN KEY (size_id_id) REFERENCES size (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f09e88cce5 FOREIGN KEY (color_id_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_52ea1f09e88cce5 ON order_item (color_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_52ea1f09ae945c60 ON order_item (size_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_52ea1f09fcdaeaaa ON order_item (order_id_id)
        SQL);
    }
}
