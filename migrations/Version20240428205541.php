<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428205541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_fk_idAct');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_fk_idUser');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_act id_act INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC6B3CA4B FOREIGN KEY (id_user) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC31481D1E FOREIGN KEY (id_act) REFERENCES actualite (id)');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY fk_userr');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY fk_cat');
        $this->addSql('ALTER TABLE formation CHANGE cat_id cat_id INT DEFAULT NULL, CHANGE artiste_id artiste_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF21D25844 FOREIGN KEY (artiste_id) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFE6ADA943 FOREIGN KEY (cat_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE oeuvre DROP FOREIGN KEY fk_artiste');
        $this->addSql('ALTER TABLE oeuvre CHANGE artiste_id artiste_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT FK_35FE2EFE21D25844 FOREIGN KEY (artiste_id) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY ratings_ibfk_1');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY ratings_ibfk_2');
        $this->addSql('ALTER TABLE ratings CHANGE user_id user_id INT DEFAULT NULL, CHANGE formation_id formation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9A76ED395 FOREIGN KEY (user_id) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C95200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_rec');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY inscription_ibfk_1');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY inscription_ibfk_2');
        $this->addSql('DROP INDEX formation_id ON inscription');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (ID)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D65200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC6B3CA4B');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC31481D1E');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT NOT NULL, CHANGE id_act id_act INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_fk_idAct FOREIGN KEY (id_act) REFERENCES actualite (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_fk_idUser FOREIGN KEY (id_user) REFERENCES user (ID) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF21D25844');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFE6ADA943');
        $this->addSql('ALTER TABLE formation CHANGE artiste_id artiste_id INT NOT NULL, CHANGE cat_id cat_id INT NOT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT fk_userr FOREIGN KEY (artiste_id) REFERENCES user (ID) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT fk_cat FOREIGN KEY (cat_id) REFERENCES categorie (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6A76ED395');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D65200282E');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT inscription_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (ID) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT inscription_ibfk_2 FOREIGN KEY (formation_id) REFERENCES formation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX formation_id ON inscription (formation_id, user_id)');
        $this->addSql('ALTER TABLE oeuvre DROP FOREIGN KEY FK_35FE2EFE21D25844');
        $this->addSql('ALTER TABLE oeuvre CHANGE artiste_id artiste_id INT NOT NULL');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT fk_artiste FOREIGN KEY (artiste_id) REFERENCES user (ID) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9A76ED395');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C95200282E');
        $this->addSql('ALTER TABLE ratings CHANGE user_id user_id INT NOT NULL, CHANGE formation_id formation_id INT NOT NULL');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT ratings_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (ID) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT ratings_ibfk_2 FOREIGN KEY (formation_id) REFERENCES formation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_rec FOREIGN KEY (id_user) REFERENCES user (ID) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
