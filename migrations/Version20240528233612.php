<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528233612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_post_like (recette_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_65D6AA5C89312FE9 (recette_id), INDEX IDX_65D6AA5CA76ED395 (user_id), PRIMARY KEY(recette_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_post_like ADD CONSTRAINT FK_65D6AA5C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post_like ADD CONSTRAINT FK_65D6AA5CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_user DROP FOREIGN KEY FK_C0933C1289312FE9');
        $this->addSql('ALTER TABLE recette_user DROP FOREIGN KEY FK_C0933C12A76ED395');
        $this->addSql('DROP TABLE recette_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recette_user (recette_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C0933C12A76ED395 (user_id), INDEX IDX_C0933C1289312FE9 (recette_id), PRIMARY KEY(recette_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recette_user ADD CONSTRAINT FK_C0933C1289312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_user ADD CONSTRAINT FK_C0933C12A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post_like DROP FOREIGN KEY FK_65D6AA5C89312FE9');
        $this->addSql('ALTER TABLE user_post_like DROP FOREIGN KEY FK_65D6AA5CA76ED395');
        $this->addSql('DROP TABLE user_post_like');
    }
}
