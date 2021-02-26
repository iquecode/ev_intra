Sistema web de código aberto para controle financeiro dos pagamentos/depósitos dos ecovileiros.

Simples, leve e funcional, feito com PHP 7.4.1, HTML5 e CSS puros (sem bibliotecas e frameworks por enquanto) e banco de dados MariaDB/MySQL.

Utilizado paradigma de orientação a objetos, com a montagem da classe User com a persistência do banco através da classe UserDaoMysql (que implementa as interfaces declaradas em User).

O projeto deve evoluir para uma solução completa de Intranet. Quem sentir em comtribuir com melhorias é bem vind@.

O banco de dados pode ser criado com os seguintes comandos em mysql (algumas campos ainda não estão sendo utilizados no sistema).
CREATE DATABASE ev_financeiro;

CREATE TABLE users (
    id_user  int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
    quota int(3),	
    name varchar(60),
    nickname varchar(20),  
    email varchar(40) NOT NULL UNIQUE ,
    pass varchar(256) NOT NULL,
    status_pass tinyint,
    time_pass timestamp,
    user_type tinyint(1),  
    PRIMARY KEY (id_user)
);

CREATE TABLE entry_types (
    id_entry_type int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
    type varchar(30),
    sign tinyint(1),
    PRIMARY KEY (id_entry_type)
);

CREATE TABLE entrys (
    id_entry int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
    entry_date date,
    record_time timestamp,
    description varchar(30),
    value decimal(10,2),
    id_user int(10) UNSIGNED,
    id_entry_type int(10) UNSIGNED,
    PRIMARY KEY (id_entry),
    FOREIGN KEY(id_user) REFERENCES users(id_user),
    FOREIGN KEY(id_entry_type) REFERENCES entry_types(id_entry_type)
);


CREATE TABLE logs (
    id_log int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
    log_time timestamp,
    description varchar(60),
    log_type varchar(30),
    id_user int(10) UNSIGNED,
    PRIMARY KEY (id_log),
    FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE log_types (
    id_log_type int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
    log_type varchar(30),
    PRIMARY KEY (id_log_type)
);