<?php

namespace DIPinvoices\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160125172245 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema)
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('CREATE TABLE user_roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, parent_role INT DEFAULT NULL, access VARCHAR(1000) DEFAULT NULL, access_ban VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, user_roles_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, register_date DATETIME NOT NULL, last_log_date DATETIME DEFAULT NULL, last_activity_date DATETIME DEFAULT NULL, img_base64 TEXT DEFAULT NULL, INDEX IDX_1483A5E9D84AB5C4 (user_roles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
		$this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D84AB5C4 FOREIGN KEY (user_roles_id) REFERENCES user_roles (id)');
                $this->addSql('INSERT INTO user_roles (description, id, name, parent_role) VALUES ("", 1, "SuperAdmin", null)');
                $this->addSql('INSERT INTO user_roles (description, id, name, parent_role, access_ban) VALUES ("", 2, "Guest", null, "Admin:Settings:default,Admin:Settings:editUser,Admin:Settings:role,Admin:Settings:editRole")');
                $register_date = new \DateTime();
                $this->addSql('INSERT INTO users (id, user_roles_id, name, email, phone, password, register_date, last_log_date, last_activity_date) VALUES (NULL, 1, "Admin", "admin@admin.cz", NULL, "$2y$10$JEqS9HiXqAf6ExCwUy7uEOy07S1sgBg4TGkbuWzDSQhrNuf/mNlem", "'.$register_date->format('Y-m-d H:i:s').'", NULL, NULL)');

                
        }

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema)
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D84AB5C4');
		$this->addSql('DROP TABLE user_roles');
		$this->addSql('DROP TABLE users');
	}
}
