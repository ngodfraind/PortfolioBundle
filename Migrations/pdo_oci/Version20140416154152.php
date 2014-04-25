<?php

namespace Icap\PortfolioBundle\Migrations\pdo_oci;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2014/04/16 03:41:54
 */
class Version20140416154152 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE icap__portfolio_users (
                id NUMBER(10) NOT NULL, 
                user_id NUMBER(10) NOT NULL, 
                portfolio_id NUMBER(10) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'ICAP__PORTFOLIO_USERS' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE ICAP__PORTFOLIO_USERS ADD CONSTRAINT ICAP__PORTFOLIO_USERS_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE ICAP__PORTFOLIO_USERS_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER ICAP__PORTFOLIO_USERS_AI_PK BEFORE INSERT ON ICAP__PORTFOLIO_USERS FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT ICAP__PORTFOLIO_USERS_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT ICAP__PORTFOLIO_USERS_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'ICAP__PORTFOLIO_USERS_ID_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT ICAP__PORTFOLIO_USERS_ID_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE INDEX IDX_3980F8F8A76ED395 ON icap__portfolio_users (user_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_3980F8F8B96B5643 ON icap__portfolio_users (portfolio_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX portfolio_users_unique_idx ON icap__portfolio_users (portfolio_id, user_id)
        ");
        $this->addSql("
            CREATE TABLE icap__portfolio (
                id NUMBER(10) NOT NULL, 
                user_id NUMBER(10) NOT NULL, 
                title VARCHAR2(128) NOT NULL, 
                slug VARCHAR2(128) NOT NULL, 
                visibility NUMBER(10) NOT NULL, 
                createdAt TIMESTAMP(0) NOT NULL, 
                updatedAt TIMESTAMP(0) NOT NULL, 
                deletedAt TIMESTAMP(0) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'ICAP__PORTFOLIO' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE ICAP__PORTFOLIO ADD CONSTRAINT ICAP__PORTFOLIO_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE ICAP__PORTFOLIO_ID_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER ICAP__PORTFOLIO_AI_PK BEFORE INSERT ON ICAP__PORTFOLIO FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT ICAP__PORTFOLIO_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT ICAP__PORTFOLIO_ID_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'ICAP__PORTFOLIO_ID_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT ICAP__PORTFOLIO_ID_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_8B1895D989D9B62 ON icap__portfolio (slug)
        ");
        $this->addSql("
            CREATE INDEX IDX_8B1895DA76ED395 ON icap__portfolio (user_id)
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
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE icap__portfolio_users 
            DROP CONSTRAINT FK_3980F8F8B96B5643
        ");
        $this->addSql("
            DROP TABLE icap__portfolio_users
        ");
        $this->addSql("
            DROP TABLE icap__portfolio
        ");
    }
}