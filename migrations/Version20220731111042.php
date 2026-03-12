<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220731111042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catusage (id INT AUTO_INCREMENT NOT NULL, nom_catusage VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cctp (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, titre VARCHAR(150) NOT NULL, nom_operation VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', close TINYINT(1) NOT NULL, INDEX IDX_59103F46FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cctp_lot (cctp_id INT NOT NULL, lot_id INT NOT NULL, INDEX IDX_8FD76EAC573F0E43 (cctp_id), INDEX IDX_8FD76EACA8CBA5F7 (lot_id), PRIMARY KEY(cctp_id, lot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cctp_entreprise (cctp_id INT NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_2FFEAA9D573F0E43 (cctp_id), INDEX IDX_2FFEAA9DA4AEAFEA (entreprise_id), PRIMARY KEY(cctp_id, entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chantier (id INT AUTO_INCREMENT NOT NULL, typeusage_id INT NOT NULL, nom_chantier VARCHAR(100) NOT NULL, surface NUMERIC(10, 2) DEFAULT NULL, type_lgt VARCHAR(50) DEFAULT NULL, nb_lgt INT DEFAULT NULL, INDEX IDX_636F27F67D396812 (typeusage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, chantier_id INT NOT NULL, entreprise_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8B27C52BFB88E14F (utilisateur_id), INDEX IDX_8B27C52BD0C0049D (chantier_id), INDEX IDX_8B27C52BA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doc_final (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, type VARCHAR(100) DEFAULT NULL, quantite NUMERIC(10, 2) NOT NULL, localisation VARCHAR(255) DEFAULT NULL, cctp_id INT NOT NULL, moyenne NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_833C3190F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctype (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, ordre INT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom_entreprise VARCHAR(100) NOT NULL, num_rue_entreprise VARCHAR(50) NOT NULL, nom_rue_entreprise VARCHAR(100) NOT NULL, cp_entreprise VARCHAR(50) NOT NULL, ville_entreprise VARCHAR(100) NOT NULL, role VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE installeur (id INT AUTO_INCREMENT NOT NULL, nom_installeur VARCHAR(100) NOT NULL, tel VARCHAR(50) DEFAULT NULL, mail VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lignedevis (id INT AUTO_INCREMENT NOT NULL, devis_id INT NOT NULL, produit_id INT NOT NULL, specification_id INT NOT NULL, quantite NUMERIC(10, 2) NOT NULL, INDEX IDX_CC0A89B241DEFADA (devis_id), INDEX IDX_CC0A89B2F347EFB (produit_id), UNIQUE INDEX UNIQ_CC0A89B2908E2FFE (specification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lot (id INT AUTO_INCREMENT NOT NULL, nom_lot VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moa (id INT AUTO_INCREMENT NOT NULL, nom_moa VARCHAR(50) NOT NULL, prenom_moa VARCHAR(50) NOT NULL, tel_moa VARCHAR(50) NOT NULL, mail_moa VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moe (id INT AUTO_INCREMENT NOT NULL, nom_moe VARCHAR(50) NOT NULL, prenom_moe VARCHAR(50) NOT NULL, tel_moe VARCHAR(50) DEFAULT NULL, mail_moe VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE optionnel (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, content VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_200355F6BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, lot_id INT NOT NULL, systeme_id INT DEFAULT NULL, nom_produit VARCHAR(100) NOT NULL, modifiable LONGTEXT DEFAULT NULL, content LONGTEXT NOT NULL, title VARCHAR(255) DEFAULT NULL, unite VARCHAR(25) DEFAULT NULL, ordre INT DEFAULT NULL, UNIQUE INDEX UNIQ_29A5EC27DC2AE7EF (nom_produit), INDEX IDX_29A5EC27A8CBA5F7 (lot_id), INDEX IDX_29A5EC27346F772E (systeme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_cctp (produit_id INT NOT NULL, cctp_id INT NOT NULL, INDEX IDX_708842A9F347EFB (produit_id), INDEX IDX_708842A9573F0E43 (cctp_id), PRIMARY KEY(produit_id, cctp_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specification (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, marque VARCHAR(60) DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_E3F1A9AF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE systeme (id INT AUTO_INCREMENT NOT NULL, nom_systeme VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE systeme_lot (systeme_id INT NOT NULL, lot_id INT NOT NULL, INDEX IDX_BCB72330346F772E (systeme_id), INDEX IDX_BCB72330A8CBA5F7 (lot_id), PRIMARY KEY(systeme_id, lot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typeusage (id INT AUTO_INCREMENT NOT NULL, catusage_id INT NOT NULL, nom_usage VARCHAR(50) NOT NULL, color VARCHAR(7) DEFAULT NULL, INDEX IDX_5AC1EB6375A985B1 (catusage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nom_user VARCHAR(50) NOT NULL, prenom_user VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cctp ADD CONSTRAINT FK_59103F46FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE cctp_lot ADD CONSTRAINT FK_8FD76EAC573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cctp_lot ADD CONSTRAINT FK_8FD76EACA8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cctp_entreprise ADD CONSTRAINT FK_2FFEAA9D573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cctp_entreprise ADD CONSTRAINT FK_2FFEAA9DA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F67D396812 FOREIGN KEY (typeusage_id) REFERENCES typeusage (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE doc_final ADD CONSTRAINT FK_833C3190F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B241DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B2908E2FFE FOREIGN KEY (specification_id) REFERENCES specification (id)');
        $this->addSql('ALTER TABLE optionnel ADD CONSTRAINT FK_200355F6BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27346F772E FOREIGN KEY (systeme_id) REFERENCES systeme (id)');
        $this->addSql('ALTER TABLE produit_cctp ADD CONSTRAINT FK_708842A9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_cctp ADD CONSTRAINT FK_708842A9573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE specification ADD CONSTRAINT FK_E3F1A9AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE systeme_lot ADD CONSTRAINT FK_BCB72330346F772E FOREIGN KEY (systeme_id) REFERENCES systeme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE systeme_lot ADD CONSTRAINT FK_BCB72330A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE typeusage ADD CONSTRAINT FK_5AC1EB6375A985B1 FOREIGN KEY (catusage_id) REFERENCES catusage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE optionnel DROP FOREIGN KEY FK_200355F6BCF5E72D');
        $this->addSql('ALTER TABLE typeusage DROP FOREIGN KEY FK_5AC1EB6375A985B1');
        $this->addSql('ALTER TABLE cctp_lot DROP FOREIGN KEY FK_8FD76EAC573F0E43');
        $this->addSql('ALTER TABLE cctp_entreprise DROP FOREIGN KEY FK_2FFEAA9D573F0E43');
        $this->addSql('ALTER TABLE produit_cctp DROP FOREIGN KEY FK_708842A9573F0E43');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BD0C0049D');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B241DEFADA');
        $this->addSql('ALTER TABLE cctp_entreprise DROP FOREIGN KEY FK_2FFEAA9DA4AEAFEA');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA4AEAFEA');
        $this->addSql('ALTER TABLE cctp_lot DROP FOREIGN KEY FK_8FD76EACA8CBA5F7');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A8CBA5F7');
        $this->addSql('ALTER TABLE systeme_lot DROP FOREIGN KEY FK_BCB72330A8CBA5F7');
        $this->addSql('ALTER TABLE doc_final DROP FOREIGN KEY FK_833C3190F347EFB');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B2F347EFB');
        $this->addSql('ALTER TABLE produit_cctp DROP FOREIGN KEY FK_708842A9F347EFB');
        $this->addSql('ALTER TABLE specification DROP FOREIGN KEY FK_E3F1A9AF347EFB');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B2908E2FFE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27346F772E');
        $this->addSql('ALTER TABLE systeme_lot DROP FOREIGN KEY FK_BCB72330346F772E');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F67D396812');
        $this->addSql('ALTER TABLE cctp DROP FOREIGN KEY FK_59103F46FB88E14F');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BFB88E14F');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE catusage');
        $this->addSql('DROP TABLE cctp');
        $this->addSql('DROP TABLE cctp_lot');
        $this->addSql('DROP TABLE cctp_entreprise');
        $this->addSql('DROP TABLE chantier');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE doc_final');
        $this->addSql('DROP TABLE doctype');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE installeur');
        $this->addSql('DROP TABLE lignedevis');
        $this->addSql('DROP TABLE lot');
        $this->addSql('DROP TABLE moa');
        $this->addSql('DROP TABLE moe');
        $this->addSql('DROP TABLE optionnel');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_cctp');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE specification');
        $this->addSql('DROP TABLE systeme');
        $this->addSql('DROP TABLE systeme_lot');
        $this->addSql('DROP TABLE typeusage');
        $this->addSql('DROP TABLE utilisateur');
    }
}
