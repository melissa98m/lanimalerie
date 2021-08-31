<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210829132313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_de_commande ADD commande_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL, ADD total INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_de_commande ADD CONSTRAINT FK_7982ACE682EA2E54 FOREIGN KEY (commande_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE ligne_de_commande ADD CONSTRAINT FK_7982ACE619EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7982ACE682EA2E54 ON ligne_de_commande (commande_id)');
        $this->addSql('CREATE INDEX IDX_7982ACE619EB6921 ON ligne_de_commande (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_de_commande DROP FOREIGN KEY FK_7982ACE682EA2E54');
        $this->addSql('ALTER TABLE ligne_de_commande DROP FOREIGN KEY FK_7982ACE619EB6921');
        $this->addSql('DROP INDEX IDX_7982ACE682EA2E54 ON ligne_de_commande');
        $this->addSql('DROP INDEX IDX_7982ACE619EB6921 ON ligne_de_commande');
        $this->addSql('ALTER TABLE ligne_de_commande DROP commande_id, DROP client_id, DROP total');
    }
}
