<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522150328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment DROP CONSTRAINT fk_795fd9bbde18e50b
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_795fd9bbde18e50b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment RENAME COLUMN product_id_id TO product_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_795FD9BB4584665A ON attachment (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT fk_f0fe2527de18e50b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT fk_f0fe25279d86650f
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT fk_f0fe2527ae945c60
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT fk_f0fe2527e88cce5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f0fe2527e88cce5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f0fe2527ae945c60
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f0fe25279d86650f
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f0fe2527de18e50b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD product_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD size_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD color_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP product_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP user_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP size_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP color_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527498DA827 FOREIGN KEY (size_id) REFERENCES size (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25277ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE25274584665A ON cart_item (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527A76ED395 ON cart_item (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527498DA827 ON cart_item (size_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE25277ADA1FB5 ON cart_item (color_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color DROP CONSTRAINT fk_665648e9de18e50b
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_665648e9de18e50b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color RENAME COLUMN product_id_id TO product_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color ADD CONSTRAINT FK_665648E94584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_665648E94584665A ON color (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT fk_f52993989d86650f
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f52993989d86650f
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" RENAME COLUMN user_id_id TO user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f09de18e50b
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_52ea1f09de18e50b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item RENAME COLUMN product_id_id TO product_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP CONSTRAINT fk_d34a04ad9777d11e
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_d34a04ad9777d11e
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product RENAME COLUMN category_id_id TO category_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size DROP CONSTRAINT fk_f7c0246ade18e50b
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f7c0246ade18e50b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size RENAME COLUMN product_id_id TO product_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size ADD CONSTRAINT FK_F7C0246A4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F7C0246A4584665A ON size (product_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color DROP CONSTRAINT FK_665648E94584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_665648E94584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color RENAME COLUMN product_id TO product_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE color ADD CONSTRAINT fk_665648e9de18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_665648e9de18e50b ON color (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size DROP CONSTRAINT FK_F7C0246A4584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F7C0246A4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size RENAME COLUMN product_id TO product_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE size ADD CONSTRAINT fk_f7c0246ade18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f7c0246ade18e50b ON size (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment DROP CONSTRAINT FK_795FD9BB4584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_795FD9BB4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment RENAME COLUMN product_id TO product_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE attachment ADD CONSTRAINT fk_795fd9bbde18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_795fd9bbde18e50b ON attachment (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE25274584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527498DA827
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE25277ADA1FB5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE25274584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE2527A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE2527498DA827
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE25277ADA1FB5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD product_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD user_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD size_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD color_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP product_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP size_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP color_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT fk_f0fe2527de18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT fk_f0fe25279d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT fk_f0fe2527ae945c60 FOREIGN KEY (size_id_id) REFERENCES size (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT fk_f0fe2527e88cce5 FOREIGN KEY (color_id_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f0fe2527e88cce5 ON cart_item (color_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f0fe2527ae945c60 ON cart_item (size_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f0fe25279d86650f ON cart_item (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f0fe2527de18e50b ON cart_item (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_52EA1F094584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item RENAME COLUMN product_id TO product_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f09de18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_52ea1f09de18e50b ON order_item (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" RENAME COLUMN user_id TO user_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT fk_f52993989d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f52993989d86650f ON "order" (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP CONSTRAINT FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product RENAME COLUMN category_id TO category_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT fk_d34a04ad9777d11e FOREIGN KEY (category_id_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_d34a04ad9777d11e ON product (category_id_id)
        SQL);
    }
}
