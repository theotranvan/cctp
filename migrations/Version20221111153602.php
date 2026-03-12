<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111153602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE cctp_lot ADD CONSTRAINT FK_8FD76EAC573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE cctp_lot ADD CONSTRAINT FK_8FD76EACA8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) ON DELETE CASCADE');
        //$this->addSql('DROP INDEX id_cctp ON cctp_lot');
        //$this->addSql('CREATE INDEX IDX_8FD76EAC573F0E43 ON cctp_lot (cctp_id)');
        //$this->addSql('DROP INDEX id_lot ON cctp_lot');
        //$this->addSql('CREATE INDEX IDX_8FD76EACA8CBA5F7 ON cctp_lot (lot_id)');
        //$this->addSql('ALTER TABLE cctp_entreprise ADD CONSTRAINT FK_2FFEAA9D573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE cctp_entreprise ADD CONSTRAINT FK_2FFEAA9DA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        //$this->addSql('DROP INDEX cctp_id ON cctp_entreprise');
        //$this->addSql('CREATE INDEX IDX_2FFEAA9D573F0E43 ON cctp_entreprise (cctp_id)');
        ///$this->addSql('DROP INDEX entreprise_id ON cctp_entreprise');
        //$this->addSql('CREATE INDEX IDX_2FFEAA9DA4AEAFEA ON cctp_entreprise (entreprise_id)');
        //$this->addSql('ALTER TABLE chantier ADD num_affaire INT NOT NULL');
        //$this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F67D396812 FOREIGN KEY (typeusage_id) REFERENCES typeusage (id)');
        //$this->addSql('DROP INDEX typeusage_id ON chantier');
        //$this->addSql('CREATE INDEX IDX_636F27F67D396812 ON chantier (typeusage_id)');
        //$this->addSql('ALTER TABLE devis DROP installeur_id, CHANGE created_At created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        //$this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        //$this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        //$this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        //$this->addSql('DROP INDEX utilisateur_id ON devis');
        //$this->addSql('CREATE INDEX IDX_8B27C52BFB88E14F ON devis (utilisateur_id)');
        //$this->addSql('DROP INDEX chantier_id ON devis');
        //$this->addSql('CREATE INDEX IDX_8B27C52BD0C0049D ON devis (chantier_id)');
        //$this->addSql('DROP INDEX entreprise_id ON devis');
        //$this->addSql('CREATE INDEX IDX_8B27C52BA4AEAFEA ON devis (entreprise_id)');
        //$this->addSql('DROP INDEX cctp_id ON doc_final');
        //$this->addSql('ALTER TABLE doc_final ADD CONSTRAINT FK_833C3190F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        //$this->addSql('DROP INDEX produit_id ON doc_final');
        //$this->addSql('CREATE INDEX IDX_833C3190F347EFB ON doc_final (produit_id)');
        //$this->addSql('ALTER TABLE installeur DROP entreprise_id, CHANGE tel tel VARCHAR(50) DEFAULT NULL');
        //$this->addSql('ALTER TABLE lignedevis DROP INDEX specification_id, ADD UNIQUE INDEX UNIQ_CC0A89B2908E2FFE (specification_id)');
        //$this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B241DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        //$this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        //$this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B2908E2FFE FOREIGN KEY (specification_id) REFERENCES specification (id)');
        //$this->addSql('DROP INDEX devis_id ON lignedevis');
        //$this->addSql('CREATE INDEX IDX_CC0A89B241DEFADA ON lignedevis (devis_id)');
        //$this->addSql('DROP INDEX produit_id ON lignedevis');
        //$this->addSql('CREATE INDEX IDX_CC0A89B2F347EFB ON lignedevis (produit_id)');
        //$this->addSql('ALTER TABLE moa DROP entreprise_id, CHANGE tel_moa tel_moa VARCHAR(50) NOT NULL');
        //$this->addSql('ALTER TABLE moe DROP entreprise_id');
        //$this->addSql('ALTER TABLE optionnel CHANGE content content VARCHAR(255) NOT NULL');
        //$this->addSql('ALTER TABLE optionnel ADD CONSTRAINT FK_200355F6BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        //$this->addSql('DROP INDEX categorie_id ON optionnel');
        //$this->addSql('CREATE INDEX IDX_200355F6BCF5E72D ON optionnel (categorie_id)');
        //$this->addSql('ALTER TABLE produit CHANGE modifiable modifiable LONGTEXT DEFAULT NULL');
        //$this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id)');
        //$this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27346F772E FOREIGN KEY (systeme_id) REFERENCES systeme (id)');
        //$this->addSql('DROP INDEX nom_produit ON produit');
        //$this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC27DC2AE7EF ON produit (nom_produit)');
        //$this->addSql('DROP INDEX id_lot ON produit');
        //$this->addSql('CREATE INDEX IDX_29A5EC27A8CBA5F7 ON produit (lot_id)');
        //$this->addSql('DROP INDEX id_systeme ON produit');
        //$this->addSql('CREATE INDEX IDX_29A5EC27346F772E ON produit (systeme_id)');
        //$this->addSql('ALTER TABLE produit_cctp DROP PRIMARY KEY');
        //$this->addSql('ALTER TABLE produit_cctp ADD CONSTRAINT FK_708842A9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE produit_cctp ADD CONSTRAINT FK_708842A9573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        //$this->addSql('ALTER TABLE produit_cctp ADD PRIMARY KEY (produit_id, cctp_id)');
        //$this->addSql('DROP INDEX produit_id ON produit_cctp');
        //$this->addSql('CREATE INDEX IDX_708842A9F347EFB ON produit_cctp (produit_id)');
        //$this->addSql('DROP INDEX cctp_id ON produit_cctp');
        //$this->addSql('CREATE INDEX IDX_708842A9573F0E43 ON produit_cctp (cctp_id)');
        //$this->addSql('ALTER TABLE specification ADD CONSTRAINT FK_E3F1A9AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        //$this->addSql('DROP INDEX produit_id ON specification');
        //$this->addSql('CREATE INDEX IDX_E3F1A9AF347EFB ON specification (produit_id)');
        //$this->addSql('ALTER TABLE typeusage ADD CONSTRAINT FK_5AC1EB6375A985B1 FOREIGN KEY (catusage_id) REFERENCES catusage (id)');
        //$this->addSql('DROP INDEX catusage_id ON typeusage');
        $this->addSql('CREATE INDEX IDX_5AC1EB6375A985B1 ON typeusage (catusage_id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cctp_entreprise DROP FOREIGN KEY FK_2FFEAA9D573F0E43');
        $this->addSql('ALTER TABLE cctp_entreprise DROP FOREIGN KEY FK_2FFEAA9DA4AEAFEA');
        $this->addSql('ALTER TABLE cctp_entreprise DROP FOREIGN KEY FK_2FFEAA9D573F0E43');
        $this->addSql('ALTER TABLE cctp_entreprise DROP FOREIGN KEY FK_2FFEAA9DA4AEAFEA');
        $this->addSql('DROP INDEX idx_2ffeaa9d573f0e43 ON cctp_entreprise');
        $this->addSql('CREATE INDEX cctp_id ON cctp_entreprise (cctp_id)');
        $this->addSql('DROP INDEX idx_2ffeaa9da4aeafea ON cctp_entreprise');
        $this->addSql('CREATE INDEX entreprise_id ON cctp_entreprise (entreprise_id)');
        $this->addSql('ALTER TABLE cctp_entreprise ADD CONSTRAINT FK_2FFEAA9D573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cctp_entreprise ADD CONSTRAINT FK_2FFEAA9DA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cctp_lot DROP FOREIGN KEY FK_8FD76EAC573F0E43');
        $this->addSql('ALTER TABLE cctp_lot DROP FOREIGN KEY FK_8FD76EACA8CBA5F7');
        $this->addSql('ALTER TABLE cctp_lot DROP FOREIGN KEY FK_8FD76EAC573F0E43');
        $this->addSql('ALTER TABLE cctp_lot DROP FOREIGN KEY FK_8FD76EACA8CBA5F7');
        $this->addSql('DROP INDEX idx_8fd76eac573f0e43 ON cctp_lot');
        $this->addSql('CREATE INDEX id_cctp ON cctp_lot (cctp_id)');
        $this->addSql('DROP INDEX idx_8fd76eaca8cba5f7 ON cctp_lot');
        $this->addSql('CREATE INDEX id_lot ON cctp_lot (lot_id)');
        $this->addSql('ALTER TABLE cctp_lot ADD CONSTRAINT FK_8FD76EAC573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cctp_lot ADD CONSTRAINT FK_8FD76EACA8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F67D396812');
        $this->addSql('ALTER TABLE chantier DROP FOREIGN KEY FK_636F27F67D396812');
        $this->addSql('ALTER TABLE chantier DROP num_affaire, CHANGE nom_chantier nom_chantier VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE type_lgt type_lgt VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX idx_636f27f67d396812 ON chantier');
        $this->addSql('CREATE INDEX typeusage_id ON chantier (typeusage_id)');
        $this->addSql('ALTER TABLE chantier ADD CONSTRAINT FK_636F27F67D396812 FOREIGN KEY (typeusage_id) REFERENCES typeusage (id)');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BFB88E14F');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BD0C0049D');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA4AEAFEA');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BFB88E14F');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BD0C0049D');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BA4AEAFEA');
        $this->addSql('ALTER TABLE devis ADD installeur_id INT NOT NULL, CHANGE created_at created_At DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX idx_8b27c52bd0c0049d ON devis');
        $this->addSql('CREATE INDEX chantier_id ON devis (chantier_id)');
        $this->addSql('DROP INDEX idx_8b27c52ba4aeafea ON devis');
        $this->addSql('CREATE INDEX entreprise_id ON devis (entreprise_id)');
        $this->addSql('DROP INDEX idx_8b27c52bfb88e14f ON devis');
        $this->addSql('CREATE INDEX utilisateur_id ON devis (utilisateur_id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantier (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE doc_final DROP FOREIGN KEY FK_833C3190F347EFB');
        $this->addSql('ALTER TABLE doc_final DROP FOREIGN KEY FK_833C3190F347EFB');
        $this->addSql('ALTER TABLE doc_final CHANGE type type VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE localisation localisation VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('CREATE INDEX cctp_id ON doc_final (cctp_id)');
        $this->addSql('DROP INDEX idx_833c3190f347efb ON doc_final');
        $this->addSql('CREATE INDEX produit_id ON doc_final (produit_id)');
        $this->addSql('ALTER TABLE doc_final ADD CONSTRAINT FK_833C3190F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE doctype CHANGE content content LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE title title VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE entreprise CHANGE nom_entreprise nom_entreprise VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE num_rue_entreprise num_rue_entreprise VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE nom_rue_entreprise nom_rue_entreprise VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE cp_entreprise cp_entreprise VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE ville_entreprise ville_entreprise VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE role role VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE installeur ADD entreprise_id INT DEFAULT NULL, CHANGE nom_installeur nom_installeur VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE tel tel INT DEFAULT NULL, CHANGE mail mail VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE lignedevis DROP INDEX UNIQ_CC0A89B2908E2FFE, ADD INDEX specification_id (specification_id)');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B241DEFADA');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B2F347EFB');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B2908E2FFE');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B241DEFADA');
        $this->addSql('ALTER TABLE lignedevis DROP FOREIGN KEY FK_CC0A89B2F347EFB');
        $this->addSql('DROP INDEX idx_cc0a89b2f347efb ON lignedevis');
        $this->addSql('CREATE INDEX produit_id ON lignedevis (produit_id)');
        $this->addSql('DROP INDEX idx_cc0a89b241defada ON lignedevis');
        $this->addSql('CREATE INDEX devis_id ON lignedevis (devis_id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B241DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE lignedevis ADD CONSTRAINT FK_CC0A89B2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE moa ADD entreprise_id INT NOT NULL, CHANGE nom_moa nom_moa VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE prenom_moa prenom_moa VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE tel_moa tel_moa VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE mail_moa mail_moa VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE moe ADD entreprise_id INT NOT NULL, CHANGE nom_moe nom_moe VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE prenom_moe prenom_moe VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE tel_moe tel_moe VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE mail_moe mail_moe VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE optionnel DROP FOREIGN KEY FK_200355F6BCF5E72D');
        $this->addSql('ALTER TABLE optionnel DROP FOREIGN KEY FK_200355F6BCF5E72D');
        $this->addSql('ALTER TABLE optionnel CHANGE content content LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE title title VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX idx_200355f6bcf5e72d ON optionnel');
        $this->addSql('CREATE INDEX categorie_id ON optionnel (categorie_id)');
        $this->addSql('ALTER TABLE optionnel ADD CONSTRAINT FK_200355F6BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A8CBA5F7');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27346F772E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A8CBA5F7');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27346F772E');
        $this->addSql('ALTER TABLE produit CHANGE nom_produit nom_produit VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE modifiable modifiable TEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE content content LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE title title VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE unite unite VARCHAR(25) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX idx_29a5ec27a8cba5f7 ON produit');
        $this->addSql('CREATE INDEX id_lot ON produit (lot_id)');
        $this->addSql('DROP INDEX idx_29a5ec27346f772e ON produit');
        $this->addSql('CREATE INDEX id_systeme ON produit (systeme_id)');
        $this->addSql('DROP INDEX uniq_29a5ec27dc2ae7ef ON produit');
        $this->addSql('CREATE UNIQUE INDEX nom_produit ON produit (nom_produit)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27346F772E FOREIGN KEY (systeme_id) REFERENCES systeme (id)');
        $this->addSql('ALTER TABLE produit_cctp DROP FOREIGN KEY FK_708842A9F347EFB');
        $this->addSql('ALTER TABLE produit_cctp DROP FOREIGN KEY FK_708842A9573F0E43');
        $this->addSql('ALTER TABLE produit_cctp DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE produit_cctp DROP FOREIGN KEY FK_708842A9F347EFB');
        $this->addSql('ALTER TABLE produit_cctp DROP FOREIGN KEY FK_708842A9573F0E43');
        $this->addSql('ALTER TABLE produit_cctp ADD PRIMARY KEY (cctp_id, produit_id)');
        $this->addSql('DROP INDEX idx_708842a9f347efb ON produit_cctp');
        $this->addSql('CREATE INDEX produit_id ON produit_cctp (produit_id)');
        $this->addSql('DROP INDEX idx_708842a9573f0e43 ON produit_cctp');
        $this->addSql('CREATE INDEX cctp_id ON produit_cctp (cctp_id)');
        $this->addSql('ALTER TABLE produit_cctp ADD CONSTRAINT FK_708842A9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_cctp ADD CONSTRAINT FK_708842A9573F0E43 FOREIGN KEY (cctp_id) REFERENCES cctp (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specification DROP FOREIGN KEY FK_E3F1A9AF347EFB');
        $this->addSql('ALTER TABLE specification DROP FOREIGN KEY FK_E3F1A9AF347EFB');
        $this->addSql('ALTER TABLE specification CHANGE marque marque VARCHAR(60) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE type type VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX idx_e3f1a9af347efb ON specification');
        $this->addSql('CREATE INDEX produit_id ON specification (produit_id)');
        $this->addSql('ALTER TABLE specification ADD CONSTRAINT FK_E3F1A9AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE typeusage DROP FOREIGN KEY FK_5AC1EB6375A985B1');
        $this->addSql('ALTER TABLE typeusage DROP FOREIGN KEY FK_5AC1EB6375A985B1');
        $this->addSql('ALTER TABLE typeusage CHANGE nom_usage nom_usage VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE color color VARCHAR(7) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX idx_5ac1eb6375a985b1 ON typeusage');
        $this->addSql('CREATE INDEX catusage_id ON typeusage (catusage_id)');
        $this->addSql('ALTER TABLE typeusage ADD CONSTRAINT FK_5AC1EB6375A985B1 FOREIGN KEY (catusage_id) REFERENCES catusage (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
