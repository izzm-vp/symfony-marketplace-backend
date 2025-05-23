<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519190511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE attachment (id SERIAL NOT NULL, product_id_id INT NOT NULL, path VARCHAR(255) NOT NULL, is_image BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_795FD9BBDE18E50B ON attachment (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cart_item (id SERIAL NOT NULL, product_id_id INT NOT NULL, user_id_id INT NOT NULL, size_id_id INT NOT NULL, color_id_id INT NOT NULL, add_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527DE18E50B ON cart_item (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE25279D86650F ON cart_item (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527AE945C60 ON cart_item (size_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527E88CCE5 ON cart_item (color_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE color (id SERIAL NOT NULL, product_id_id INT NOT NULL, hex_code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_665648E9DE18E50B ON color (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "order" (id SERIAL NOT NULL, user_id_id INT NOT NULL, status VARCHAR(255) NOT NULL, date_placed TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F52993989D86650F ON "order" (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_item (id SERIAL NOT NULL, product_id_id INT NOT NULL, order_id_id INT NOT NULL, size_id_id INT NOT NULL, color_id_id INT NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F09DE18E50B ON order_item (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F09FCDAEAAA ON order_item (order_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F09AE945C60 ON order_item (size_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F09E88CCE5 ON order_item (color_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id SERIAL NOT NULL, category_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04AD9777D11E ON product (category_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE size (id SERIAL NOT NULL, product_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, in_stock BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F7C0246ADE18E50B ON size (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25279D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527AE945C60 FOREIGN KEY (size_id_id) REFERENCES size (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527E88CCE5 FOREIGN KEY (color_id_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color ADD CONSTRAINT FK_665648E9DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT FK_F52993989D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09AE945C60 FOREIGN KEY (size_id_id) REFERENCES size (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09E88CCE5 FOREIGN KEY (color_id_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size ADD CONSTRAINT FK_F7C0246ADE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BBDE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527DE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE25279D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527AE945C60
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527E88CCE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color DROP CONSTRAINT FK_665648E9DE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT FK_F52993989D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09DE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09FCDAEAAA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09AE945C60
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09E88CCE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP CONSTRAINT FK_D34A04AD9777D11E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size DROP CONSTRAINT FK_F7C0246ADE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE attachment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE color
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "order"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE size
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
    }
}
