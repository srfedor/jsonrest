DROP TABLE `booking`;
DROP TABLE `tour`;

CREATE TABLE `tour` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`qty` int(11) NOT NULL,
`active` int(1) NOT NULL,
`date_from` date NULL ,
`date_to` date NULL,
`price_per_day` decimal(20,2) DEFAULT NULL,
`description` text) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `booking` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`tour_id` int(11) NOT NULL,
`qty` int(11) NOT NULL,
`date_from` date NULL,
`date_to` date NULL) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `booking` ADD INDEX ( `tour_id` ) ;
ALTER TABLE `booking` ADD FOREIGN KEY ( `tour_id` ) REFERENCES `tour` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;