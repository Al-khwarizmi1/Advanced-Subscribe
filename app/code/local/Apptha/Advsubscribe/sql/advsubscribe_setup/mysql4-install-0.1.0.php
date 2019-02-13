<?php

$installer = $this;

$installer->startSetup();

$installer->run("

   DROP TABLE IF EXISTS {$this->getTable('advsubscribe')};
   
		CREATE TABLE {$this->getTable('advsubscribe')} (
		 `advsubscribe_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `email_id` varchar(30) NOT NULL,
		  `categori_id` varchar(50) NOT NULL,
		  `encryption_key` varchar(35) NOT NULL,
		  `status` varchar(10) NOT NULL DEFAULT 'In Active',
		  `follower` tinyint(1) NOT NULL DEFAULT '0',
		  `created_time` varchar(20) NOT NULL,
		  `update_time` varchar(20) NOT NULL,
		  PRIMARY KEY (`advsubscribe_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 