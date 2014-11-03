<?php

namespace Icap\PortfolioBundle\Migrations\pdo_pgsql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/10/31 04:51:42
 */
class Version20141031165140 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE icap__portfolio_teams (
                id SERIAL NOT NULL, 
                team_id INT NOT NULL, 
                portfolio_id INT NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BBC17F49296CD8AE ON icap__portfolio_teams (team_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_BBC17F49B96B5643 ON icap__portfolio_teams (portfolio_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX portfolio_teams_unique_idx ON icap__portfolio_teams (portfolio_id, team_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_comments (
                id SERIAL NOT NULL, 
                portfolio_id INT NOT NULL, 
                sender_id INT NOT NULL, 
                message TEXT NOT NULL, 
                sending_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_D4662DE3B96B5643 ON icap__portfolio_comments (portfolio_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_D4662DE3F624B39D ON icap__portfolio_comments (sender_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_guides (
                id SERIAL NOT NULL, 
                user_id INT NOT NULL, 
                portfolio_id INT NOT NULL, 
                comments_view_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_27EAB640A76ED395 ON icap__portfolio_guides (user_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_27EAB640B96B5643 ON icap__portfolio_guides (portfolio_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX portfolio_users_unique_idx ON icap__portfolio_guides (portfolio_id, user_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio_widget_text (
                id INT NOT NULL, 
                text TEXT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_teams 
            ADD CONSTRAINT FK_BBC17F49296CD8AE FOREIGN KEY (team_id) 
            REFERENCES claro_team (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_teams 
            ADD CONSTRAINT FK_BBC17F49B96B5643 FOREIGN KEY (portfolio_id) 
            REFERENCES icap__portfolio (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_comments 
            ADD CONSTRAINT FK_D4662DE3B96B5643 FOREIGN KEY (portfolio_id) 
            REFERENCES icap__portfolio (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_comments 
            ADD CONSTRAINT FK_D4662DE3F624B39D FOREIGN KEY (sender_id) 
            REFERENCES claro_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_guides 
            ADD CONSTRAINT FK_27EAB640A76ED395 FOREIGN KEY (user_id) 
            REFERENCES claro_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_guides 
            ADD CONSTRAINT FK_27EAB640B96B5643 FOREIGN KEY (portfolio_id) 
            REFERENCES icap__portfolio (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_text 
            ADD CONSTRAINT FK_89550C61BF396750 FOREIGN KEY (id) 
            REFERENCES icap__portfolio_abstract_widget (id) 
            ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio 
            ADD comments_view_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_abstract_widget 
            ADD label VARCHAR(255) NOT NULL
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_badges_badge 
            DROP CONSTRAINT FK_25D41B98F7A2C2FC
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_badges_badge 
            ADD CONSTRAINT FK_25D41B98F7A2C2FC FOREIGN KEY (badge_id) 
            REFERENCES claro_badge (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE icap__portfolio_teams
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_comments
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_guides
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_widget_text
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio 
            DROP comments_view_at
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_abstract_widget 
            DROP label
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_badges_badge 
            DROP CONSTRAINT FK_25D41B98F7A2C2FC
        ");
        $this->addSql("
            ALTER TABLE icap__portfolio_widget_badges_badge 
            ADD CONSTRAINT FK_25D41B98F7A2C2FC FOREIGN KEY (badge_id) 
            REFERENCES claro_user_badge (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ");
    }
}