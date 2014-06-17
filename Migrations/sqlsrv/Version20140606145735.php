<?php

namespace Icap\PortfolioBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/06/06 02:57:37
 */
class Version20140606145735 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE icap__portfolio_users (
                id INT IDENTITY NOT NULL, 
                user_id INT NOT NULL, 
                portfolio_id INT NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_3980F8F8A76ED395 ON icap__portfolio_users (user_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_3980F8F8B96B5643 ON icap__portfolio_users (portfolio_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX portfolio_users_unique_idx ON icap__portfolio_users (portfolio_id, user_id) 
            WHERE portfolio_id IS NOT NULL 
            AND user_id IS NOT NULL
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio (
                id INT IDENTITY NOT NULL, 
                user_id INT NOT NULL, 
                visibility INT NOT NULL, 
                deletedAt DATETIME2(6), 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_8B1895DA76ED395 ON icap__portfolio (user_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_abstract_widget (
                id INT IDENTITY NOT NULL, 
                portfolio_id INT NOT NULL, 
                createdAt DATETIME2(6) NOT NULL, 
                updatedAt DATETIME2(6) NOT NULL, 
                widget_type NVARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_3E7AEFBBB96B5643 ON icap__portfolio_abstract_widget (portfolio_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_widget_title (
                id INT NOT NULL, 
                title NVARCHAR(128) NOT NULL, 
                slug NVARCHAR(128) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_1431A01D989D9B62 ON icap__portfolio_widget_title (slug) 
            WHERE slug IS NOT NULL
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_widget_type (
                id INT IDENTITY NOT NULL, 
                name NVARCHAR(255) NOT NULL, 
                is_unique BIT NOT NULL, 
                is_deletable BIT NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_3E00FC8F5E237E06 ON icap__portfolio_widget_type (name) 
            WHERE name IS NOT NULL
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_widget_skills_skill (
                id INT IDENTITY NOT NULL, 
                skills_widget_id INT NOT NULL, 
                name NVARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_98EF40A32F7BE59D ON icap__portfolio_widget_skills_skill (skills_widget_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_widget_user_information (
                id INT NOT NULL, 
                city NVARCHAR(255), 
                description VARCHAR(MAX), 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_widget_skills (
                id INT NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_users 
            ADD CONSTRAINT FK_3980F8F8A76ED395 FOREIGN KEY (user_id) 
            REFERENCES claro_user (id)
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_users 
            ADD CONSTRAINT FK_3980F8F8B96B5643 FOREIGN KEY (portfolio_id) 
            REFERENCES icap__portfolio (id)
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio 
            ADD CONSTRAINT FK_8B1895DA76ED395 FOREIGN KEY (user_id) 
            REFERENCES claro_user (id)
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_abstract_widget 
            ADD CONSTRAINT FK_3E7AEFBBB96B5643 FOREIGN KEY (portfolio_id) 
            REFERENCES icap__portfolio (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_title 
            ADD CONSTRAINT FK_1431A01DBF396750 FOREIGN KEY (id) 
            REFERENCES icap__portfolio_abstract_widget (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_skills_skill 
            ADD CONSTRAINT FK_98EF40A32F7BE59D FOREIGN KEY (skills_widget_id) 
            REFERENCES icap__portfolio_widget_skills (id)
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_user_information 
            ADD CONSTRAINT FK_E2BFAA03BF396750 FOREIGN KEY (id) 
            REFERENCES icap__portfolio_abstract_widget (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_skills 
            ADD CONSTRAINT FK_6C68C5A1BF396750 FOREIGN KEY (id) 
            REFERENCES icap__portfolio_abstract_widget (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE icap__portfolio_users 
            DROP CONSTRAINT FK_3980F8F8B96B5643
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_abstract_widget 
            DROP CONSTRAINT FK_3E7AEFBBB96B5643
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_title 
            DROP CONSTRAINT FK_1431A01DBF396750
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_user_information 
            DROP CONSTRAINT FK_E2BFAA03BF396750
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_skills 
            DROP CONSTRAINT FK_6C68C5A1BF396750
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_skills_skill 
            DROP CONSTRAINT FK_98EF40A32F7BE59D
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_users
        ");
        $this->addSql("
            DROP TABLE icap__portfolio
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_abstract_widget
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_widget_title
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_widget_type
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_widget_skills_skill
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_widget_user_information
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_widget_skills
        ");
    }
}