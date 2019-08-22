<?php

define('DB_SCHEMA', 'CREATE DATABASE IF NOT EXISTS :db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');

define('METADATA_TABLE_SCHEMA', 'CREATE TABLE IF NOT EXISTS`' . METADATA_TABLE . '` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vnum` varchar(50) DEFAULT NULL, 
  `word` varchar(100) NOT NULL, 
  `description` longtext NOT NULL,
  `aliasWord` varchar(100) NOT NULL, 
  PRIMARY KEY (`id`)
) AUTO_INCREMENT = 10001 ENGINE=MyISAM DEFAULT CHARSET=utf8mb4');

define('CHAR_ENCODING_SCHEMA', 'SET NAMES utf8mb4');

?>
