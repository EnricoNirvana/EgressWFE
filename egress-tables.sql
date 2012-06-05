create table accountblocks (id int unsigned auto_increment primary key, uuid char(36), cronostamp datetime default null,passwordhash varchar(64),notes varchar(512));
create table accountreqs (id int unsigned auto_increment primary key, uuid char(36), cronostamp int(11), avFirst varchar(64),avLast varchar(64),email varchar(64),RLfirst varchar(64),RLlast varchar(64),passwordhash varchar(64),salt varchar(64),notes varchar(512),status char(15));
CREATE TABLE `agreements` (`UserUUID` char(64) DEFAULT NULL,`policyver` decimal(4,2) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `policies` (`policyver` decimal(4,2) DEFAULT NULL,`policytype` char(3) DEFAULT NULL,`policytext` longtext) ENGINE=MyISAM DEFAULT CHARSET=latin1;
