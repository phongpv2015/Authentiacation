create database if not exists site CHARACTER SET utf8 COLLATE utf8_unicode_ci;
use site;
create table if not exists users (
 id int(11) AUTO_INCREMENT primary key,
email varchar(255) not null ,
username varchar(20) not null,
password varchar(255) not null,
created_at timestamp not null,
active tinyint(1)not null,
active_hash varchar(255),
updated_at timestamp,
first_name varchar(50),
last_name varchar(50),
recover_hash varchar(255),
remember_identifier varchar(255),
remember_token varchar(255)
)engine = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;