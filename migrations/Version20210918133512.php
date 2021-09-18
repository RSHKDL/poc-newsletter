<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210918133512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'REL-2';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_newsletter (subscription_id INT NOT NULL, newsletter_id INT NOT NULL, INDEX IDX_BC60C5AD9A1887DC (subscription_id), INDEX IDX_BC60C5AD22DB1917 (newsletter_id), PRIMARY KEY(subscription_id, newsletter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, subscription_id INT DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6499A1887DC (subscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription_newsletter ADD CONSTRAINT FK_BC60C5AD9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_newsletter ADD CONSTRAINT FK_BC60C5AD22DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription_newsletter DROP FOREIGN KEY FK_BC60C5AD22DB1917');
        $this->addSql('ALTER TABLE subscription_newsletter DROP FOREIGN KEY FK_BC60C5AD9A1887DC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499A1887DC');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE subscription_newsletter');
        $this->addSql('DROP TABLE user');
    }
}
