-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Gen 12, 2015 alle 21:42
-- Versione del server: 5.5.40-0ubuntu0.14.04.1
-- Versione PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `deltabase`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addNewUser`(IN `name` VARCHAR(45), IN `surname` VARCHAR(45), IN `city` VARCHAR(100), IN `country` VARCHAR(100), IN `languages` VARCHAR(500), IN `telephone` VARCHAR(50), IN `favjob` INT(11), IN `picture` VARCHAR(100), IN `bio` TEXT, IN `email` VARCHAR(100), IN `password` VARCHAR(100))
    NO SQL
BEGIN
-- INSERT INTO user 
-- VALUES(NULL, email, password);

INSERT INTO  `deltabase`.`usr_information` (
`id_user` ,
`name` ,
`surname` ,
`city` ,
`country` ,
`languages` ,
`telephone` ,
`id_category` ,
`picture` ,
`bio` ,
`_avg`
)
VALUES(NULL, name, surname, city, country, languages, favjob, telephone, picture, bio, 0);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAdvicesForCompany`(IN `id` INT(11))
    NO SQL
BEGIN

SET @company_city = (SELECT city FROM com_information WHERE id_company=id);
SET @company_country = (SELECT country FROM com_information WHERE id_company=id);
SET @company_cat = (SELECT id_category FROM com_information WHERE id_company=id);
SET @requested_avg = (SELECT requested_avg FROM com_information WHERE id_company=id);

SELECT * FROM
(
SELECT *, description as favjob 
FROM usr_information AS u1 NATURAL JOIN job_category
WHERE _avg >= @requested_avg AND
			(EXISTS(SELECT * FROM usr_information WHERE city = @company_city AND u1.id_user=id_user)
             OR EXISTS(SELECT * FROM usr_information WHERE city = @company_country AND u1.id_user=id_user)
             OR EXISTS(SELECT * FROM usr_information WHERE id_category = @company_cat AND u1.id_user=id_user)
            )
			AND NOT EXISTS (SELECT * FROM collaboration WHERE id_company=id AND id_user=u1.id_user)
ORDER BY city, country, _avg
) AS T
ORDER BY RAND()
LIMIT 0, 100;
          	                      
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAdvicesForUser`(IN `id` INT(11))
    NO SQL
BEGIN

SET @user_city = (SELECT city FROM usr_information WHERE id_user=id);
SET @user_country = (SELECT country FROM usr_information WHERE id_user=id);
SET @user_cat = (SELECT id_category FROM usr_information WHERE id_user=id);
SET @requested_avg = (SELECT _avg FROM usr_information WHERE id_user=id);

SELECT * FROM
(
SELECT *, description as favjob 
FROM com_information AS u1 NATURAL JOIN job_category
WHERE requested_avg <= @requested_avg AND
			(EXISTS(SELECT * FROM com_information WHERE city = @user_city AND u1.id_company=id_company)
             OR EXISTS(SELECT * FROM com_information WHERE city = @user_country  AND u1.id_company=id_company)
             OR EXISTS(SELECT * FROM com_information WHERE id_category = @user_cat AND u1.id_company=id_company)
            )
			AND NOT EXISTS (SELECT * FROM collaboration WHERE id_user=id AND id_company=u1.id_company)
ORDER BY city, country
) AS T
ORDER BY RAND()
LIMIT 0, 100;
          	                      
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCompanyBasicInformation`(IN `id` INT(11))
    NO SQL
SELECT name, city, country, email, telephone
FROM company NATURAL JOIN com_information
WHERE id_company=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCompanyInformation`(IN `id` INT(11))
    NO SQL
SELECT com_information.*, email, job_category.description AS category
FROM company NATURAL JOIN com_information NATURAL JOIN job_category
WHERE id_company = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCompanyMessages`(IN `id` INT(11))
    NO SQL
SELECT com_inbox.*, name AS user_name, surname AS user_surname
FROM com_inbox INNER JOIN usr_information ON (from_usr = id_user)
WHERE id_company=id
ORDER BY send_time DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCompanyNotifications`(IN `id` INT(11))
    NO SQL
SELECT pay_attention.*, name AS user_name, surname AS user_surname, job_category.description as role
FROM pay_attention NATURAL JOIN usr_information NATURAL JOIN job_category
WHERE id_company=id AND received=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCompanyPendingRequests`(IN `id` INT(11))
    NO SQL
SELECT CONCAT_WS(" ", usr_information.name, usr_information.surname) AS name, id_collaboration, date_start
FROM user NATURAL JOIN usr_information NATURAL JOIN collaboration  
WHERE date_end IS NULL AND id_company = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserBasicInformation`(IN `id` INT(11))
    NO SQL
SELECT name, surname, city, country, email, telephone, _avg as avg
FROM user NATURAL JOIN usr_information
WHERE id_user=id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserCollaborations`(IN `id` INT(11))
    NO SQL
SELECT id_collaboration, com_information.name as c_name, date_start, date_end, review 
FROM collaboration NATURAL JOIN com_information 
WHERE date_start IS NOT NULL AND id_user = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserInformation`(IN `id` INT(11))
    NO SQL
SELECT usr_information.*, email, job_category.description AS category, city, country
FROM user NATURAL JOIN usr_information NATURAL JOIN job_category
WHERE id_user = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserMessages`(IN `id` INT(11))
    NO SQL
SELECT usr_inbox.*, name AS company_name
FROM usr_inbox INNER JOIN com_information ON (from_com = id_company)
WHERE id_user=id
ORDER BY send_time DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserNotifications`(IN `id` INT(11))
    NO SQL
SELECT _notification.*, name AS company_name
FROM _notification, com_information
WHERE id_user=id AND received=0 AND com_information.id_company = _notification.id_company
ORDER BY start_time DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserPendingRequests`(IN `id` INT(11))
    NO SQL
SELECT com_information.name AS name, id_collaboration
FROM company NATURAL JOIN com_information NATURAL JOIN collaboration  
WHERE date_start IS NULL AND id_user = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserPortfolio`(IN `id` INT(11))
    NO SQL
SELECT * FROM portfolio WHERE id_user =id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getUserSkills`(IN `id` INT(11))
    NO SQL
SELECT id_category, skill.name, value FROM usr_skill NATURAL JOIN skill WHERE id_usr=id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `collaboration`
--

CREATE TABLE IF NOT EXISTS `collaboration` (
  `id_collaboration` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_company` int(11) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `review` float DEFAULT NULL,
  PRIMARY KEY (`id_collaboration`),
  KEY `id_user_idx` (`id_user`),
  KEY `id_company_idx` (`id_company`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Dump dei dati per la tabella `collaboration`
--

INSERT INTO `collaboration` (`id_collaboration`, `id_user`, `id_company`, `date_start`, `date_end`, `review`) VALUES
(12, 1, 1, '2014-11-30', '2014-12-03', 5),
(13, 1, 3, '2014-12-02', '2014-12-05', 5),
(15, 19, 1, '2014-12-01', '2014-12-02', 5),
(16, 24, 1, '2014-12-01', '2014-12-18', 4),
(20, 15, 1, '2014-12-02', '2014-12-01', 1),
(21, 15, 3, '2014-12-01', '2014-12-23', 5),
(22, 42, 5, '2014-12-01', '2014-12-02', 5),
(23, 42, 1, '2014-12-01', '2014-12-20', 4.5),
(24, 45, 5, '2014-11-02', '2014-12-17', 5),
(50, 1, 1, '2014-12-01', NULL, NULL);

--
-- Trigger `collaboration`
--
DROP TRIGGER IF EXISTS `collaboration_AINS`;
DELIMITER //
CREATE TRIGGER `collaboration_AINS` AFTER INSERT ON `collaboration`
 FOR EACH ROW BEGIN
	IF EXISTS(SELECT * FROM pay_attention WHERE id_user = NEW.id_user AND id_company = NEW.id_company) THEN
		DELETE FROM pay_attention WHERE id_user = NEW.id_user AND id_company = NEW.id_company;
	END IF;
 	
	SET @avg = (SELECT AVG(review) FROM collaboration WHERE id_user = NEW.id_user);
	UPDATE usr_information SET _avg = @avg WHERE id_user=NEW.id_user;

	INSERT INTO _notification
	VALUES(NULL, NEW.id_user, NEW.id_company,1, NOW(), false, NEW.id_collaboration);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `collaboration_AUPD`;
DELIMITER //
CREATE TRIGGER `collaboration_AUPD` AFTER UPDATE ON `collaboration`
 FOR EACH ROW BEGIN
IF NEW.review IS NOT NULL THEN
    INSERT INTO _notification
    VALUES(NULL, NEW.id_user, NEW.id_company, 2, NOW(), false, CONCAT('profile.php?u=', NEW.id_user, '&cid=',NEW.id_collaboration,'#collaboration-s'));
	
	SET @avg = (SELECT AVG(review) FROM collaboration WHERE id_user = NEW.id_user);
	UPDATE usr_information SET _avg = @avg WHERE id_user=NEW.id_user;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id_company` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`id_company`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dump dei dati per la tabella `company`
--

INSERT INTO `company` (`id_company`, `email`, `password`) VALUES
(1, 'apple@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(2, 'google@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(3, 'yahoo@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(4, 'design@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(5, 'delta@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(9, 'oasis@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(10, 'faketales@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(11, 'suburbs@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4');

-- --------------------------------------------------------

--
-- Struttura della tabella `com_inbox`
--

CREATE TABLE IF NOT EXISTS `com_inbox` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `from_usr` int(11) NOT NULL,
  `id_company` int(11) NOT NULL,
  `object` varchar(45) DEFAULT 'No Object',
  `text` text NOT NULL,
  `send_time` datetime NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_message`),
  KEY `id_company_idx` (`id_company`),
  KEY `from_idx` (`from_usr`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dump dei dati per la tabella `com_inbox`
--

INSERT INTO `com_inbox` (`id_message`, `from_usr`, `id_company`, `object`, `text`, `send_time`, `read`) VALUES
(9, 1, 9, 'RE: Hi ', 'Ok, that worked. But is this gonna work?', '2014-12-21 14:38:11', 0),
(12, 1, 2, 'Hi!', 'Hi, i&#039;m really interested in your company. I am a really good web designer. Please hire me!', '2014-12-22 13:32:28', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `com_information`
--

CREATE TABLE IF NOT EXISTS `com_information` (
  `id_company` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `id_category` int(11) NOT NULL,
  `telephone` varchar(45) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `requirements` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `requested_avg` int(11) NOT NULL,
  PRIMARY KEY (`id_company`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `com_information`
--

INSERT INTO `com_information` (`id_company`, `name`, `id_category`, `telephone`, `picture`, `bio`, `requirements`, `city`, `country`, `requested_avg`) VALUES
(1, 'Apple Inc.', 2, '1-800-MY-APPLE', '../deltabase/img/com/profile_pic/c4ca4238a0b923820dcc509a6f75849b.jpg', 'Apple Inc. is an American multinational corporation headquartered in Cupertino, California, that designs, develops, and sells consumer electronics, computer software, online services, and personal computers. Its best-known hardware products are the Mac line of computers, the iPod media player, the iPhone smartphone, and the iPad tablet computer. Its online services include iCloud, iTunes Store, and App Store. Apple&#039;s consumer software includes the OS X and iOS operating systems, the iTunes media browser, the Safari web browser, and the iLife and iWork creativity and productivity suites.\r\n\r\nApple was founded by Steve Jobs, Steve Wozniak, and Ronald Wayne on April 1, 1976, to develop and sell personal computers. It was incorporated as Apple Computer, Inc. on January 3, 1977, and was renamed as Apple Inc. on January 9, 2007, to reflect its shifted focus towards consumer electronics.\r\n\r\nApple is the world&#039;s second-largest information technology company by revenue after Samsung Electronics, and the world&#039;s third-largest mobile phone maker. On November 25, 2014, in addition to being the largest publicly traded corporation in the world by market capitalization, Apple became the first U.S. company to be valued at over $700B. As of 2014, Apple employs 72,800 permanent full-time employees, maintains 437 retail stores in fifteen countries, and operates the online Apple Store and iTunes Store, the latter of which is the world&#039;s largest music retailer.\r\n\r\nApple&#039;s worldwide annual revenue in 2014 totaled $182 billion (FY end October 2014). Apple enjoys a high level of brand loyalty and, according to the 2014 edition of the Interbrand Best Global Brands report, is the world&#039;s most valuable brand with a valuation of US$118.9 billion.\r\nHowever, the company has received criticism for its contractors&#039; labor practices, as well as for its own environmental and business practices.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor pulvinar libero, nec lacinia lorem pulvinar ut. Curabitur lorem orci, ornare eget lorem non, vulputate hendrerit eros. Suspendisse erat dui, pretium nec magna at, porttitor faucibus eros. Quisque euismod elementum elit, a varius dui tristique nec. Pellentesque euismod sollicitudin orci, sed varius leo dapibus vel. Etiam eu justo venenatis, venenatis leo non, eleifend orci. Sed vel lobortis nibh. Phasellus semper, nulla porttitor facilisis bibendum, nunc purus vestibulum dolor, id tristique nisl elit a elit. Donec tincidunt augue ac lacus facilisis tincidunt. Curabitur et placerat tellus.\n', 'Cupertino', 'United States', 0),
(2, 'Google', 1, '555 124 123', '../deltabase/img/com/profile_pic/c81e728d9d4c2f636f067f89cc14862c.jpg', 'Google is a U.S. headquartered, multinational corporation specializing in Internet-related services and products. These include online advertising technologies, search, cloud computing, and software.[8] Most of its profits are derived from AdWords,[9][10]an online advertising service that places advertising near the list of search results.\n\nGoogle was founded by Larry Page and Sergey Brin while they were Ph.D. students at Stanford University. Together they own about 14 percent of its shares but control 56 of the stockholder voting power through supervoting stock. They incorporated Google as a privately held company on September 4, 1998. An initial public offering followed on August 19, 2004. Its mission statement from the outset was "to organize the world''s information and make it universally accessible and useful,"[11] and its unofficial slogan was "Don''t be evil."[12][13] In 2004, Google moved to its new headquarters in Mountain View, California, nicknamed the Googleplex.[14]\n\nRapid growth since incorporation has triggered a chain of products, acquisitions and partnerships beyond Google''s core search engine. It offers online productivity software including email (Gmail), a cloud storage service (Google Drive), an office suite (Google Docs) and a social networking service (Google+). Desktop products include applications for web browsing, organizing and editing photos, and instant messaging. The company leads the development of the Android mobile operating system and the browser-only Chrome OS[15] for a netbook known as a Chromebook. Google has moved increasingly into communications hardware: it partners with major electronics manufacturers[16] in the production of its "high-quality low-cost"[17] Nexus devices and acquired Motorola Mobility in May 2012.[18] In 2012, a fiber-optic infrastructure was installed in Kansas City to facilitate a Google Fiber broadband service.[19]\n\nThe corporation has been estimated to run more than one million servers in data centers around the world (as of 2007);[20] and to process over one billion search requests,[21] and about 24 petabytes of user-generated data, each day (as of 2009).[22][23][24][25] In December 2013 Alexa listed google.com as the most visited website in the world. Numerous Google sites in other languages figure in the top one hundred, as do several other Google-owned sites such as YouTube and Blogger.[26] Its market dominance has led to prominent media coverage, including criticism of the company over issues such as search neutrality, copyright, censorship, and privacy.[27][28]', 'Interested in joining us, but not sure where to start? We''ve got you covered. Check out our teams and roles to learn more about opportunities to do cool stuff that matters.\r\nTake a ride on the Google self-guided tour. Stop by our offices around the globe to find what you''re searching for. Keep your arms and legs inside the vehicle at all times.\r\n\r\nFortune Magazine and the Great Place to Work Institute named Google the 2014 “Best Company Work For.” This marks our fifth time at the top of the list. While we’re honored to be included, what makes us proud is the recognition of the great contributions Googlers make to the communities in which we live and work.', 'Mountain View', 'United States', 3),
(3, 'Yahoo', 1, '555 125 666', '../deltabase/img/com/profile_pic/eccbc87e4b5ce2fe28308fd9f2a7baf3.jpg', 'Yahoo! Inc. is an American multinational Internet corporation headquartered in Sunnyvale, California. It is globally known for its Web portal, search engine Yahoo Search, and related services, including Yahoo Directory, Yahoo Mail, Yahoo News, Yahoo Finance, Yahoo Groups, Yahoo Answers, advertising, online mapping, video sharing, fantasy sports and its social media website. It is one of the most popular sites in the United States.[3] According to news sources, roughly 700 million people visit Yahoo websites every month.[4][5] Yahoo itself claims it attracts "more than half a billion consumers every month in more than 30 languages."[6]\r\n\r\nYahoo was founded by Jerry Yang and David Filo in January 1994 and was incorporated on March 1, 1995. On July 16, 2012, former Google executive Marissa Mayer was named as Yahoo CEO and President, effective July 17, 2012.[7]\r\n\r\nAccording to comScore, Yahoo during July 2013 surpassed Google on the number of United States visitors to its Web sites for the first time since May 2011, set at 196 million United States visitors, having increased by 21 percent in a year.[8]', 'User Experience & Design\r\nCollaborate with editors, engineers, designers, and product managers to shape the experience of our products used by millions of individuals around the world every day\r\nProduct Management\r\nWork alongside a top tier product team, a world class development group, designers, business leaders, and sales professionals across Yahoo\r\nEngineering\r\nContribute significant high-quality, reusable, elegant code\r\nLabs/Sciences\r\nAnalyze existing problems, identify potential solutions, and build prototypes\r\nInfrastructure & Support\r\nWork with highly skilled security engineers, help desk and data insight professionals, and systems and business analysts to help secure and support new products as they launch at huge scale', 'Santa Clara', 'United States', 2),
(4, 'Designz', 3, '0586 881134', '../deltabase/img/com/profile_pic/a87ff679a2f3e71d9181a67b7542122c.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra ante vel viverra laoreet. Cras ullamcorper fringilla auctor. Duis imperdiet libero vitae orci lacinia, eget maximus magna elementum. Curabitur tellus eros, imperdiet eget tellus eget, tempor euismod velit. Maecenas id justo maximus, viverra turpis eget, varius tellus. Sed sit amet eros sollicitudin nisl pellentesque convallis lacinia nec purus. Ut tincidunt porta tincidunt. Pellentesque dignissim, velit eu dapibus sollicitudin, lectus sapien auctor nibh, in tincidunt felis elit ac tellus. Nullam vel tristique ex, in tincidunt turpis. Donec luctus eros ac dolor iaculis vulputate in vel nibh.\r\n\r\nDonec vel ultrices leo. Mauris accumsan leo ut gravida accumsan. Maecenas condimentum erat at est cursus, sed finibus augue pharetra. Curabitur nec mauris quis quam cursus tempus. Nam et risus est. Sed egestas urna eget tellus laoreet suscipit. Integer ornare est accumsan ante aliquam, ut facilisis lorem dapibus. Proin placerat erat sit amet nisi mollis, vitae pretium augue efficitur. Quisque pretium dapibus massa, sed efficitur leo elementum malesuada. Praesent consectetur laoreet scelerisque.\r\n\r\nDonec ullamcorper volutpat enim ac venenatis. Cras eu eleifend tellus, id vestibulum arcu. Nullam ut elit quis odio sodales feugiat. In tristique varius magna at dapibus. Curabitur vel lectus elit. Ut et iaculis nisl, ac semper arcu. In eget odio porttitor, tempus mauris non, auctor purus. Curabitur laoreet consectetur sapien id tincidunt. Nulla porta felis blandit cursus vestibulum. Vestibulum metus turpis, sollicitudin eu tincidunt eget, ultricies vitae quam. Mauris euismod, nunc eget semper rutrum, sem justo viverra metus, et venenatis metus orci id lorem.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec viverra ante vel viverra laoreet. Cras ullamcorper fringilla auctor. Duis imperdiet libero vitae orci lacinia, eget maximus magna elementum. Curabitur tellus eros, imperdiet eget tellus eget, tempor euismod velit. Maecenas id justo maximus, viverra turpis eget, varius tellus. Sed sit amet eros sollicitudin nisl pellentesque convallis lacinia nec purus. Ut tincidunt porta tincidunt. Pellentesque dignissim, velit eu dapibus sollicitudin, lectus sapien auctor nibh, in tincidunt felis elit ac tellus. Nullam vel tristique ex, in tincidunt turpis. Donec luctus eros ac dolor iaculis vulputate in vel nibh.\r\n\r\nDonec vel ultrices leo. Mauris accumsan leo ut gravida accumsan. Maecenas condimentum erat at est cursus, sed finibus augue pharetra. Curabitur nec mauris quis quam cursus tempus. Nam et risus est. Sed egestas urna eget tellus laoreet suscipit. Integer ornare est accumsan ante aliquam, ut facilisis lorem dapibus. Proin placerat erat sit amet nisi mollis, vitae pretium augue efficitur. Quisque pretium dapibus massa, sed efficitur leo elementum malesuada. Praesent consectetur laoreet scelerisque.\r\n\r\nDonec ullamcorper volutpat enim ac venenatis. Cras eu eleifend tellus, id vestibulum arcu. Nullam ut elit quis odio sodales feugiat. In tristique varius magna at dapibus. Curabitur vel lectus elit. Ut et iaculis nisl, ac semper arcu. In eget odio porttitor, tempus mauris non, auctor purus. Curabitur laoreet consectetur sapien id tincidunt. Nulla porta felis blandit cursus vestibulum. Vestibulum metus turpis, sollicitudin eu tincidunt eget, ultricies vitae quam. Mauris euismod, nunc eget semper rutrum, sem justo viverra metus, et venenatis metus orci id lorem.', 'Torino', 'Italy', 2),
(5, 'Delta Jobs', 1, '(+39) 0564 831051', '../deltabase/img/com/profile_pic/e4da3b7fbbce2345d7772b0674a318d5.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent imperdiet elit lacus, ut lobortis nisi interdum nec. Nullam id nisl ut dolor commodo fermentum eu vitae ligula. Proin lacinia diam est, blandit placerat ipsum ullamcorper id. Nunc consectetur facilisis odio, ut iaculis nibh eleifend ut. Curabitur elementum ante metus, sed faucibus tortor elementum eget. Vestibulum nec vulputate diam. Etiam at lobortis turpis. In at nisi velit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus non arcu sed justo facilisis gravida ac non dui.\r\n\r\nPellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean dui orci, ultricies hendrerit pulvinar nec, dapibus ut justo. In iaculis nisl ac mi molestie efficitur. In hac habitasse platea dictumst. Vestibulum facilisis sapien lacinia orci tincidunt aliquam et in justo. Quisque vel dolor sed justo rhoncus hendrerit. Integer non mi at leo pretium faucibus ut nec arcu. Nulla diam neque, ornare eget ante et, ultricies pulvinar velit. In feugiat lacus sit amet vehicula laoreet. Fusce interdum vel ipsum eget vehicula. Nam venenatis libero eu vehicula rutrum. Curabitur sagittis, lacus tristique sagittis pellentesque, massa velit venenatis ligula, sit amet molestie massa tortor vitae eros. Donec aliquet augue non ex lobortis elementum.\r\n\r\nMorbi pellentesque nunc magna. Nunc eleifend, dolor ut pellentesque dignissim, orci lacus facilisis massa, in ornare elit est ultrices ligula. Donec mollis volutpat orci, ultricies hendrerit nibh tempus sed. Fusce laoreet ultrices nulla eu molestie. Donec in rutrum mauris. Curabitur vehicula arcu eget accumsan efficitur. Aliquam faucibus erat felis, et malesuada felis iaculis at. Aenean non massa condimentum, fringilla turpis vitae, eleifend ligula.\r\n\r\nAliquam erat volutpat. Nunc orci lorem, placerat non finibus at, auctor tempus lorem. Donec sit amet sagittis ligula, eget finibus quam. Quisque in turpis at libero commodo iaculis non ut magna. Curabitur eget hendrerit dolor. Aliquam vitae condimentum dolor. Aliquam hendrerit, libero nec vulputate fringilla, sapien nulla lacinia lectus, vel tempus nisi lacus quis libero. Phasellus eleifend libero eget erat laoreet, in vestibulum mi cursus. Curabitur eget tellus eleifend, congue neque dictum, semper nibh. Mauris at semper nisi. Maecenas tincidunt, lacus ut lacinia efficitur, nibh urna tempus libero, eu aliquet eros libero quis nibh. Nulla fermentum massa ut sapien blandit tristique. Sed aliquam ipsum ac nibh tristique viverra.\r\n\r\nAenean finibus tellus at mattis fermentum. Suspendisse potenti. Quisque vitae massa mauris. Aliquam erat volutpat. Duis tempor cursus tellus, quis pharetra nulla semper non. Proin vitae sollicitudin metus. Aenean at massa at massa egestas aliquet. Pellentesque pellentesque blandit mattis. Proin suscipit sapien nisi, fermentum ornare justo tempus ac. Proin vitae feugiat dolor, eu eleifend risus. Vivamus turpis velit, pharetra sed erat id, vestibulum sagittis tortor.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent imperdiet elit lacus, ut lobortis nisi interdum nec. Nullam id nisl ut dolor commodo fermentum eu vitae ligula. Proin lacinia diam est, blandit placerat ipsum ullamcorper id. Nunc consectetur facilisis odio, ut iaculis nibh eleifend ut. Curabitur elementum ante metus, sed faucibus tortor elementum eget. Vestibulum nec vulputate diam. Etiam at lobortis turpis. In at nisi velit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus non arcu sed justo facilisis gravida ac non dui.\r\n\r\nPellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean dui orci, ultricies hendrerit pulvinar nec, dapibus ut justo. In iaculis nisl ac mi molestie efficitur. In hac habitasse platea dictumst. Vestibulum facilisis sapien lacinia orci tincidunt aliquam et in justo. Quisque vel dolor sed justo rhoncus hendrerit. Integer non mi at leo pretium faucibus ut nec arcu. Nulla diam neque, ornare eget ante et, ultricies pulvinar velit. In feugiat lacus sit amet vehicula laoreet. Fusce interdum vel ipsum eget vehicula. Nam venenatis libero eu vehicula rutrum. Curabitur sagittis, lacus tristique sagittis pellentesque, massa velit venenatis ligula, sit amet molestie massa tortor vitae eros. Donec aliquet augue non ex lobortis elementum.\r\n\r\nMorbi pellentesque nunc magna. Nunc eleifend, dolor ut pellentesque dignissim, orci lacus facilisis massa, in ornare elit est ultrices ligula. Donec mollis volutpat orci, ultricies hendrerit nibh tempus sed. Fusce laoreet ultrices nulla eu molestie. Donec in rutrum mauris. Curabitur vehicula arcu eget accumsan efficitur. Aliquam faucibus erat felis, et malesuada felis iaculis at. Aenean non massa condimentum, fringilla turpis vitae, eleifend ligula.\r\n\r\nAliquam erat volutpat. Nunc orci lorem, placerat non finibus at, auctor tempus lorem. Donec sit amet sagittis ligula, eget finibus quam. Quisque in turpis at libero commodo iaculis non ut magna. Curabitur eget hendrerit dolor. Aliquam vitae condimentum dolor. Aliquam hendrerit, libero nec vulputate fringilla, sapien nulla lacinia lectus, vel tempus nisi lacus quis libero. Phasellus eleifend libero eget erat laoreet, in vestibulum mi cursus. Curabitur eget tellus eleifend, congue neque dictum, semper nibh. Mauris at semper nisi. Maecenas tincidunt, lacus ut lacinia efficitur, nibh urna tempus libero, eu aliquet eros libero quis nibh. Nulla fermentum massa ut sapien blandit tristique. Sed aliquam ipsum ac nibh tristique viverra.\r\n\r\nAenean finibus tellus at mattis fermentum. Suspendisse potenti. Quisque vitae massa mauris. Aliquam erat volutpat. Duis tempor cursus tellus, quis pharetra nulla semper non. Proin vitae sollicitudin metus. Aenean at massa at massa egestas aliquet. Pellentesque pellentesque blandit mattis. Proin suscipit sapien nisi, fermentum ornare justo tempus ac. Proin vitae feugiat dolor, eu eleifend risus. Vivamus turpis velit, pharetra sed erat id, vestibulum sagittis tortor.', 'Livorno', 'Italy', 3),
(9, 'Oasis', 2, '+44 161 496 0955', '../deltabase/img/com/profile_pic/45c48cce2e2d7fbdea1afc51c7c6ad26.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor pulvinar libero, nec lacinia lorem pulvinar ut. Curabitur lorem orci, ornare eget lorem non, vulputate hendrerit eros. Suspendisse erat dui, pretium nec magna at, porttitor faucibus eros. Quisque euismod elementum elit, a varius dui tristique nec. Pellentesque euismod sollicitudin orci, sed varius leo dapibus vel. Etiam eu justo venenatis, venenatis leo non, eleifend orci. Sed vel lobortis nibh. Phasellus semper, nulla porttitor facilisis bibendum, nunc purus vestibulum dolor, id tristique nisl elit a elit. Donec tincidunt augue ac lacus facilisis tincidunt. Curabitur et placerat tellus.\r\n\r\nUt non lacinia nulla. In sed lorem mauris. Maecenas ornare eros nisl, ut scelerisque tellus cursus et. Pellentesque imperdiet sit amet nulla nec vehicula. Fusce quam dolor, iaculis vitae molestie placerat, congue eu felis. Proin velit augue, rutrum maximus enim ut, lacinia vehicula mi. Quisque hendrerit rutrum ipsum nec ultricies. Sed luctus iaculis malesuada. Aliquam sed magna eu quam luctus tincidunt sit amet ut libero. Curabitur venenatis felis sit amet rhoncus pellentesque. Integer sed velit tristique turpis sodales laoreet at vitae arcu. Morbi id pulvinar nibh.\r\n\r\nAliquam sollicitudin ex nec faucibus hendrerit. Mauris purus eros, porta ac dictum in, mollis at risus. Integer vehicula nulla dapibus diam finibus, finibus ultricies velit egestas. Nullam sagittis porttitor egestas. Donec dictum urna id nulla pulvinar, sit amet dignissim purus accumsan. Nunc vulputate sem lectus, vel accumsan odio tincidunt id. Integer bibendum aliquet suscipit.\r\n\r\nMorbi condimentum condimentum fringilla. Quisque leo nulla, fringilla ac nunc at, hendrerit ornare sem. Mauris ut molestie orci. Etiam placerat bibendum ex non consectetur. Cras malesuada nulla quis magna ultrices, et aliquam odio vulputate. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut ornare sed tortor sed malesuada. Nulla consequat porta tortor sed molestie. Fusce est nulla, posuere id leo vitae, aliquam placerat magna. Nunc velit urna, vehicula id ornare non, molestie aliquam velit. Suspendisse imperdiet dolor nunc, et sollicitudin neque consectetur id. Aenean id laoreet ipsum, nec aliquet leo. Vestibulum sodales condimentum mattis. In hac habitasse platea dictumst. Vestibulum sit amet auctor elit.\r\n\r\nIn non dictum augue. Integer dapibus porttitor dui, sed volutpat nibh pharetra id. Quisque leo libero, efficitur sed congue in, vehicula et orci. Fusce bibendum aliquet ligula, vitae finibus diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam sollicitudin in ipsum ac fermentum. Sed ac fringilla est.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tempor pulvinar libero, nec lacinia lorem pulvinar ut. Curabitur lorem orci, ornare eget lorem non, vulputate hendrerit eros. Suspendisse erat dui, pretium nec magna at, porttitor faucibus eros. Quisque euismod elementum elit, a varius dui tristique nec. Pellentesque euismod sollicitudin orci, sed varius leo dapibus vel. Etiam eu justo venenatis, venenatis leo non, eleifend orci. Sed vel lobortis nibh. Phasellus semper, nulla porttitor facilisis bibendum, nunc purus vestibulum dolor, id tristique nisl elit a elit. Donec tincidunt augue ac lacus facilisis tincidunt. Curabitur et placerat tellus.\n\nUt non lacinia nulla. In sed lorem mauris. Maecenas ornare eros nisl, ut scelerisque tellus cursus et. Pellentesque imperdiet sit amet nulla nec vehicula. Fusce quam dolor, iaculis vitae molestie placerat, congue eu felis. Proin velit augue, rutrum maximus enim ut, lacinia vehicula mi. Quisque hendrerit rutrum ipsum nec ultricies. Sed luctus iaculis malesuada. Aliquam sed magna eu quam luctus tincidunt sit amet ut libero. Curabitur venenatis felis sit amet rhoncus pellentesque. Integer sed velit tristique turpis sodales laoreet at vitae arcu. Morbi id pulvinar nibh.\n\nAliquam sollicitudin ex nec faucibus hendrerit. Mauris purus eros, porta ac dictum in, mollis at risus. Integer vehicula nulla dapibus diam finibus, finibus ultricies velit egestas. Nullam sagittis porttitor egestas. Donec dictum urna id nulla pulvinar, sit amet dignissim purus accumsan. Nunc vulputate sem lectus, vel accumsan odio tincidunt id. Integer bibendum aliquet suscipit.\n\nMorbi condimentum condimentum fringilla. Quisque leo nulla, fringilla ac nunc at, hendrerit ornare sem. Mauris ut molestie orci. Etiam placerat bibendum ex non consectetur. Cras malesuada nulla quis magna ultrices, et aliquam odio vulputate. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut ornare sed tortor sed malesuada. Nulla consequat porta tortor sed molestie. Fusce est nulla, posuere id leo vitae, aliquam placerat magna. Nunc velit urna, vehicula id ornare non, molestie aliquam velit. Suspendisse imperdiet dolor nunc, et sollicitudin neque consectetur id. Aenean id laoreet ipsum, nec aliquet leo. Vestibulum sodales condimentum mattis. In hac habitasse platea dictumst. Vestibulum sit amet auctor elit.\n\nIn non dictum augue. Integer dapibus porttitor dui, sed volutpat nibh pharetra id. Quisque leo libero, efficitur sed congue in, vehicula et orci. Fusce bibendum aliquet ligula, vitae finibus diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam sollicitudin in ipsum ac fermentum. Sed ac fringilla est.', 'Manchester', 'United Kingdom', 0),
(10, 'Fake Tales', 2, '(415) 443 156', '../deltabase/img/com/profile_pic/d3d9446802a44259755d38e6d163e820.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam maximus vitae purus convallis euismod. Aenean id est et nibh porttitor tristique. Mauris congue, sem ac aliquet sollicitudin, libero leo consectetur urna, vel accumsan mi risus et ante. Pellentesque vitae massa eros. Quisque ultricies leo auctor, malesuada augue sagittis, sagittis quam. Phasellus leo sapien, consequat eu metus nec, fringilla fermentum nisi. Aliquam iaculis augue nisi, a iaculis nunc sodales at. Cras faucibus pellentesque dignissim. Curabitur vel fringilla odio, non hendrerit ante. In egestas vestibulum mi vel rutrum. Sed pellentesque, lorem sollicitudin egestas aliquam, mauris sem posuere odio, eget ornare nisl lorem sed ante. Proin et maximus nibh. Pellentesque eget erat mi. Morbi posuere, ante a tincidunt rhoncus, dolor neque elementum ipsum, sit amet rhoncus metus felis sed metus.\n\nPhasellus ultricies, diam eu cursus laoreet, diam tellus vehicula lacus, ullamcorper pharetra libero est ultrices metus. Vivamus pellentesque, diam eget laoreet eleifend, risus nunc fermentum ex, quis faucibus massa nunc nec orci. Curabitur euismod nisl non lectus cursus viverra. Fusce tellus mauris, posuere vel vestibulum eu, pellentesque nec elit. Sed fermentum tellus non posuere sagittis. Ut non lacus vitae libero pretium finibus eget at velit. Morbi vel lectus tempor, dapibus risus a, rhoncus leo.', 'Proin scelerisque neque erat, eu venenatis est porta maximus. Sed dolor elit, ultrices vel eros ut, congue varius nulla. Cras at rutrum neque, id tristique sapien. Vestibulum auctor mauris risus, a viverra ex mollis sit amet. Proin tincidunt, odio et blandit euismod, lectus ligula tincidunt tellus, sed lacinia erat nisl ut quam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut tortor orci, gravida a vulputate ut, rutrum vel libero. Mauris suscipit consequat dictum. Quisque fermentum tortor sed lobortis tempus. Vivamus condimentum ipsum enim, vel commodo mi tempor volutpat. Morbi aliquam tincidunt massa, at porttitor elit condimentum id. Donec enim eros, auctor vel facilisis ac, faucibus et massa. Quisque quis mattis felis. Aenean eget risus et neque lobortis tincidunt. Etiam vel orci a enim faucibus commodo. In hac habitasse platea dictumst.\r\n\r\nAliquam dictum sed dolor eu dignissim. In vel vestibulum lorem. Pellentesque imperdiet sem a venenatis sagittis. Sed venenatis ipsum nec venenatis interdum. Mauris interdum in sem vel congue. Nulla lectus purus, ornare sit amet malesuada eu, dictum ut orci. Sed nibh urna, pulvinar nec accumsan at, volutpat eu augue. Suspendisse purus mauris, laoreet non consequat in, aliquam et dui. Morbi a orci ex. In a interdum purus, ac tempus tortor. Nunc vulputate at erat maximus iaculis. Vestibulum scelerisque enim felis, interdum ultrices nisi molestie in. Quisque quis risus maximus risus pellentesque blandit id sit amet justo. Integer non justo fringilla metus interdum lacinia.', 'San Francisco', 'United States', 0),
(11, 'The Suburbs', 3, '514-872-0311', '../deltabase/img/com/profile_pic/6512bd43d9caa6e02c990b0a82652dca.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit mattis turpis. Cras sit amet fringilla enim. Donec condimentum egestas enim, sed ornare dolor vulputate id. Nullam sapien ante, rhoncus nec ullamcorper quis, pretium et ipsum. Quisque eu quam mi. Nam porta sed tellus eu bibendum. Duis viverra auctor ligula, eu lacinia eros gravida id. Integer hendrerit ligula vitae arcu facilisis commodo. Ut sit amet elementum nisl, vel facilisis diam. Ut ut rhoncus tortor. Nunc volutpat lacinia ultricies. Proin luctus cursus lorem sit amet porta. Vivamus ut dolor quam.\r\n\r\nCurabitur iaculis malesuada urna non blandit. Pellentesque efficitur felis sed mattis bibendum. Aliquam gravida, nulla sit amet luctus hendrerit, mi magna rhoncus est, in facilisis ligula mauris vitae lacus. Aenean ut metus dui. Sed felis ex, faucibus id mi sit amet, elementum faucibus risus. Nunc gravida magna a risus tincidunt aliquet. Sed eleifend viverra tortor condimentum fermentum. Sed porttitor diam sit amet nisi pellentesque aliquam. Phasellus nec molestie est, id sollicitudin mi. Curabitur porta dapibus volutpat. Ut rutrum lacinia orci. Aliquam erat volutpat.', 'Vivamus leo nisl, euismod eu pretium et, dapibus vitae erat. Nullam faucibus tempus tincidunt. Sed maximus eget dui id luctus. Nunc sed pretium lectus, quis aliquet quam. Vivamus sem nulla, pretium sed cursus et, elementum eget sapien. Integer sed metus mi. Nunc ut nisl a arcu egestas facilisis vitae nec erat. Phasellus eget mauris at lacus congue pharetra eget vitae dolor. Curabitur mattis ligula ac fringilla aliquet. Maecenas iaculis ut nisl in lobortis. Donec non nisi sapien.\r\n\r\nAliquam et ex id odio convallis ultricies. Nulla facilisi. Vivamus porta, risus sed laoreet fringilla, lectus nulla feugiat ex, vel sollicitudin massa nisi vel tellus. Ut ultricies finibus pulvinar. Proin ligula ex, aliquet sit amet justo quis, vestibulum consectetur tellus. Nam ante justo, tincidunt non aliquet pretium, malesuada eget urna. Mauris fermentum lectus finibus neque rutrum, non egestas diam blandit. Sed eget accumsan neque. Aliquam gravida lorem condimentum vulputate faucibus.\r\n\r\nCras sit amet eros iaculis, rhoncus sem laoreet, accumsan neque. Vestibulum quis dolor eu turpis ultrices posuere. Phasellus quis imperdiet diam, in fringilla augue. Integer placerat orci sit amet tempor placerat. Mauris id justo fringilla, molestie purus vitae, auctor mi. Pellentesque lacinia posuere ipsum id mollis. Morbi condimentum, diam sit amet rutrum dictum, ante quam ultrices risus, vitae tincidunt velit urna finibus magna. Vestibulum scelerisque dui nec purus convallis, in molestie lorem pellentesque.', 'MontrÃ©al', 'Canada', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `job_category`
--

CREATE TABLE IF NOT EXISTS `job_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `job_category`
--

INSERT INTO `job_category` (`id_category`, `description`) VALUES
(1, 'Web Designer'),
(2, 'Developer'),
(3, 'Designer');

-- --------------------------------------------------------

--
-- Struttura della tabella `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id_lang` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `language`
--

INSERT INTO `language` (`id_lang`, `value`) VALUES
(1, 'English'),
(2, 'Italian'),
(3, 'French'),
(4, 'Spanish'),
(5, 'German'),
(6, 'Portuguese'),
(7, 'Arabic'),
(8, 'Chinese'),
(9, 'Russian'),
(10, 'Japanese');

-- --------------------------------------------------------

--
-- Struttura della tabella `pay_attention`
--

CREATE TABLE IF NOT EXISTS `pay_attention` (
  `id_user` int(11) NOT NULL,
  `id_company` int(11) NOT NULL,
  `received` int(11) NOT NULL DEFAULT '0',
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id_user`,`id_company`),
  KEY `id_company_idx` (`id_company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `pay_attention`
--

INSERT INTO `pay_attention` (`id_user`, `id_company`, `received`, `link`) VALUES
(26, 1, 0, 'profile.php?u=26');

-- --------------------------------------------------------

--
-- Struttura della tabella `portfolio`
--

CREATE TABLE IF NOT EXISTS `portfolio` (
  `id_portfolio` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `website` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_portfolio`),
  KEY `id_user_idx` (`id_user`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dump dei dati per la tabella `portfolio`
--

INSERT INTO `portfolio` (`id_portfolio`, `id_user`, `id_category`, `name`, `picture`, `description`, `website`) VALUES
(28, 1, 1, 'Logo 1', 'deltabase/img/usr/portfolio/33e75ff09dd601bbe69f351039152189.jpg', 'A simple logo for a small company.', 'www.us-consulting.it/about'),
(34, 24, 3, 'Nonno laser', 'deltabase/img/usr/portfolio/e369853df766fa44e1ed0ff613f563bd.jpg', 'A logo for a cartoon.', 'www.sito.it'),
(35, 42, 1, 'Social Network', 'deltabase/img/usr/portfolio/1c383cd30b7c298ab50293adfecb7b18.jpg', 'Social network.', 'http://www.socialnetwork.it'),
(48, 1, 1, 'US-Consulting.it', 'deltabase/img/usr/portfolio/642e92efb79421734881b53e1e1b18b6.jpg', 'A small website.', 'www.us-consulting.it/about'),
(49, 1, 2, 'iPhone App 1', 'deltabase/img/usr/portfolio/f457c545a9ded88f18ecee47145a72c0.jpg', 'A small app for iOS', 'www.sito.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `skill`
--

CREATE TABLE IF NOT EXISTS `skill` (
  `id_skill` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id_skill`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dump dei dati per la tabella `skill`
--

INSERT INTO `skill` (`id_skill`, `id_category`, `name`) VALUES
(1, 1, 'HTML'),
(2, 1, 'CSS'),
(3, 1, 'Javascript'),
(4, 1, 'PHP'),
(5, 1, 'SQL and Databases'),
(6, 2, 'C/C++'),
(7, 2, 'Java'),
(8, 2, 'Objective-C/Swift'),
(9, 2, 'iOS'),
(10, 2, 'Android'),
(11, 2, 'Windows Phone'),
(12, 2, 'Ruby'),
(13, 2, 'Python'),
(14, 3, 'Photoshop'),
(15, 3, 'Illustrator');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`) VALUES
(1, 'o.wilde@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(15, 'steve@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(19, 'putin@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(24, 'sio@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(26, 'emma@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(39, 'bill@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(40, 'steve@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(42, 'daniele@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(44, 'natalie@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(45, 'gabriele@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4'),
(46, 'paolo@delta.com', '0c88028bf3aa6a6a143ed846f2be1ea4');

--
-- Trigger `user`
--
DROP TRIGGER IF EXISTS `user_afterins`;
DELIMITER //
CREATE TRIGGER `user_afterins` AFTER INSERT ON `user`
 FOR EACH ROW INSERT INTO _review VALUES (NEW.id_user, 3)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `usr_inbox`
--

CREATE TABLE IF NOT EXISTS `usr_inbox` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `from_com` int(11) NOT NULL,
  `object` varchar(45) DEFAULT 'No Object',
  `text` text NOT NULL,
  `send_time` datetime NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_message`),
  KEY `id_user_idx` (`id_user`),
  KEY `from_com_idx` (`from_com`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `usr_inbox`
--

INSERT INTO `usr_inbox` (`id_message`, `id_user`, `from_com`, `object`, `text`, `send_time`, `read`) VALUES
(8, 1, 9, 'Hi Olivia', 'We are really interested in you.', '2014-12-21 13:13:38', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `usr_information`
--

CREATE TABLE IF NOT EXISTS `usr_information` (
  `id_user` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `languages` varchar(500) NOT NULL,
  `telephone` varchar(45) NOT NULL,
  `id_category` int(11) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `bio` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `_avg` float NOT NULL DEFAULT '3',
  PRIMARY KEY (`id_user`),
  KEY `fav_job_idx` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `usr_information`
--

INSERT INTO `usr_information` (`id_user`, `name`, `surname`, `city`, `country`, `languages`, `telephone`, `id_category`, `picture`, `bio`, `_avg`) VALUES
(1, 'Olivia', 'Wilde', 'Livorno', 'Italy', 'English, Italian', '0564831051', 1, '../deltabase/img/usr/profile_pic/c4ca4238a0b923820dcc509a6f75849b.jpg', 'Wilde was born in New York City. Her mother, Leslie Cockburn, is an American-born 60 Minutes producer and journalist. Her father, Andrew Cockburn, a journalist, was born in London England to British parents and raised in Ireland; her uncles, Alexander Cockburn and Patrick Cockburn, also worked as journalists. Wilde&#039;s older sister, Chloe Cockburn, is a civil rights attorney in New York; her aunt, Sarah Caudwell, was a writer, and her paternal grandfather, Claud Cockburn, was a novelist and journalist.\r\n\r\nWilde&#039;s father&#039;s upper-class English ancestors lived in several places at the height of the British Empire, including Peking (where her paternal grandfather was born), Calcutta, Bombay, Cairo, and Tasmania (one of her paternal great-great-grandfathers, Henry Arthur Blake, was Governor of Hong Kong).\r\n\r\nWilde has said that as a result of her parents&#039; occupations, she has a &quot;strong journalistic streak,&quot; being &quot;really critical and analytical&quot;. She has wanted to become an actress since the age of two. For a short time, Wilde&#039;s family had a house in Guilford, Vermont. She attended Georgetown Day School in Washington, D.C., as well as Phillips Academy in Andover, Massachusetts, graduating in 2002. She also studied acting at the Gaiety School of Acting in Dublin, Ireland. Wilde had writer Christopher Hitchens as a babysitter.', 4.66667),
(15, 'Steve', 'Ballmer', 'Redmond', 'United States', 'English', '1234567788', 2, '../deltabase/img/usr/profile_pic/9bf31c7ff062936a96d3c8bd1f8f2ff3.jpg', 'DEVELOPERS!!', 3),
(19, 'Vladimir', 'Putin', 'Moscow', 'Russian Federation', 'English', '11111111111111', 2, '../deltabase/img/usr/profile_pic/1f0e3dad99908345f7439f8ffabdffc4.jpg', 'Vladimir Vladimirovich Putin has been the President of Russia since 7 May 2012. Putin previously served as President from 2000 to 2008, and as Prime Minister of Russia from 1999 to 2000 and again from 2008 to 2012. During his last term as Prime Minister, he was also the Chairman of United Russia, the ruling party.\n\nFor 16 years Putin was an officer in the KGB, rising to the rank of Lieutenant Colonel before he retired to enter politics in his native Saint Petersburg in 1991. He moved to Moscow in 1996 and joined President Boris Yeltsin''s administration where he rose quickly, becoming Acting President on 31 December 1999 when Yeltsin unexpectedly resigned. Putin won the subsequent 2000 presidential election and was reelected in 2004. Because of constitutionally mandated term limits, Putin was ineligible to run for a third consecutive presidential term in 2008. Dmitry Medvedev won the 2008 presidential election and appointed Putin as Prime Minister, beginning a period of so-called "tandemocracy".[2] In September 2011, following a change in the law extending the presidential term from four years to six,[3] Putin announced that he would seek a third, non-consecutive term as President in the 2012 presidential election, an announcement which led to large-scale protests in many Russian cities. He won the election in March 2012 and is serving a six-year term.[4][5]\n\nMany of Putin''s actions are regarded by the domestic opposition and foreign observers as undemocratic.[6] The 2011 Democracy Index stated that Russia was in "a long process of regression [that] culminated in a move from a hybrid to an authoritarian regime" in view of Putin''s candidacy and flawed parliamentary elections.[7] In 2014, Russia was excluded from the G8 group as a result of its annexation of Crimea.[8]\n\nDuring Putin''s first premiership and presidency (1999–2008), real incomes increased by a factor of 2.5, real wages more than tripled; unemployment and poverty more than halved, and the Russians'' self-assessed life satisfaction rose significantly.[9] Putin''s first presidency was marked by high economic growth: the Russian economy grew for eight straight years, seeing GDP increase by 72% in PPP (as for nominal GDP, 600%).[9][10][11][12][13] As Russia''s president, Putin and the Federal Assembly passed into law a flat income tax of 13%, a reduced profits tax, and new land and legal codes.[14][15] As Prime Minister, Putin oversaw large-scale military and police reform. His energy policy has affirmed Russia''s position as an energy superpower.[citation needed] Putin supported high-tech industries such as the nuclear and defence industries. A rise in foreign investment[16] contributed to a boom in such sectors as the automotive industry. However, capital investment recently dropped 2.5% because of the crisis in Ukraine according to forecasts by economists from the IMF.[17] Putin has cultivated an image of a strongman and a popular cultural icon in Russia.', 5),
(24, 'Scottecs', 'Canemagico', 'Sapporo', 'Japan', 'Italian, English, Japanese', '777 777 777', 3, '../deltabase/img/usr/profile_pic/1ff1de774005f8da13f42943881c655f.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque consequat justo mi, id tincidunt ex rutrum eget. Proin nibh metus, hendrerit nec rhoncus eu, pretium eget sapien. Nunc id bibendum sapien. In vitae pharetra tortor, a bibendum massa. Nullam non finibus neque, id vulputate augue. Duis eu consequat ligula, non ornare nisl. Praesent ultrices dui quis euismod malesuada. Ut metus sem, ornare mattis consequat vel, molestie quis sem. Donec ac purus congue erat viverra pretium.\n', 4),
(26, 'Emma', 'Stone', 'Los Angeles', 'United States', 'English', '555 123 123 ', 1, '../deltabase/img/usr/profile_pic/4e732ced3463d06de0ca9a15b6153677.jpg', 'Stone was born in Scottsdale, Arizona, to Jeff Stone, a founder and CEO of a general-contracting company, and Krista , a homemaker.\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque consequat justo mi, id tincidunt ex rutrum eget. Proin nibh metus, hendrerit nec rhoncus eu, pretium eget sapien. Nunc id bibendum sapien. In vitae pharetra tortor, a bibendum massa. Nullam non finibus neque, id vulputate augue. Duis eu consequat ligula, non ornare nisl. Praesent ultrices dui quis euismod malesuada. Ut metus sem, ornare mattis consequat vel, molestie quis sem. Donec ac purus congue erat viverra pretium.\n', 3),
(39, 'Bill', 'Gates', 'Redmond', 'United States', 'English', '555 123 123 ', 2, '../deltabase/img/usr/profile_pic/d67d8ab4f4c10bf22aa353e27879133c.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam maximus vitae purus convallis euismod. Aenean id est et nibh porttitor tristique. Mauris congue, sem ac aliquet sollicitudin, libero leo consectetur urna, vel accumsan mi risus et ante. Pellentesque vitae massa eros. Quisque ultricies leo auctor, malesuada augue sagittis, sagittis quam. Phasellus leo sapien, consequat eu metus nec, fringilla fermentum nisi. Aliquam iaculis augue nisi, a iaculis nunc sodales at. Cras faucibus pellentesque dignissim. Curabitur vel fringilla odio, non hendrerit ante. In egestas vestibulum mi vel rutrum. Sed pellentesque, lorem sollicitudin egestas aliquam, mauris sem posuere odio, eget ornare nisl lorem sed ante. Proin et maximus nibh. Pellentesque eget erat mi. Morbi posuere, ante a tincidunt rhoncus, dolor neque elementum ipsum, sit amet rhoncus metus felis sed metus.', 3),
(40, 'Steve', 'Jobs', 'Cupertino', 'United States', 'English', '555 213 123', 3, '../deltabase/img/usr/profile_pic/d645920e395fedad7bbbed0eca3fe2e0.jpg', 'Jobs&#039;s birth parents met at the University of Wisconsin at Madison, where his Syrian-born biological father, Abdulfattah &quot;John&quot; Jandali (Arabic: Ø¹Ø¨Ø¯Ø§Ù„ÙØªØ§Ø­ Ø¬Ù†Ø¯Ù„ÙŠâ€Ž),[32][33][34][35][36] was an undergraduate and then graduate student, and where his biological mother, Swiss-American Joanne Carole Schieble, studied for a degree in speech language pathology. Jandali, who emigrated to the U.S. from Homs, Syria at the age of 19, was a graduate student studying political science when he met and became involved with Schieble. When Schieble became pregnant, her fundamentalist father vehemently refused to let her marry Jandali, and Schieble ended up going to California to have the baby and give it up for adoption. About six months later, Schieble&#039;s father died suddenly, so she married Jandali in December 1955. Jandali swiftly finished his Ph.D. and got a teaching position at the University of Wisconsin, Green Bay. The couple moved there and then had another child, Mona Simpson, who is Steve Jobs&#039;s full sister. Their marriage ended in 1962, and then Schieble moved with her daughter to Los Angeles, and later remarried.[37][38]\n\nJobs was born in San Francisco, California on February 24, 1955.[39][40] He was adopted at birth by Paul Reinhold Jobs (1922â€“1993) and Clara Jobs (nÃ©e Hagopian) (1924â€“1986), an Armenian American.[41][42] Paul and Clara had gotten married in March 1946, ten days after they met. Clara had an ectopic pregnancy and couldn&#039;t bear children. In 1955, nine years after their marriage, they decided to adopt a child.[43] According to Steve Jobs&#039;s commencement address at Stanford, Schieble wanted Jobs to be adopted only by a college graduate couple. Schieble learned that Clara Jobs had not graduated from college and Paul Jobs had only attended high school, but signed final adoption papers after they promised her that the child would definitely be encouraged and supported to attend college. Later, when asked about his &quot;adoptive parents&quot;, Jobs replied emphatically that Paul and Clara Jobs &quot;were my parents.&quot;[44] He stated in his authorized biography that they &quot;were my parents 1,000%.&quot;[38] Walter Isaacson wrote in his authorized biography about Steve Jobs that Steve had told him, &quot;Paul and Clara are 100% my parents. And Joanna and Abdulfatahâ€”are only a sperm and an egg bank. It&#039;s not rude, it is the truth.&quot;[43]\n\nThe Jobs family moved from San Francisco to Mountain View, California when Jobs was five years old.[39][40] The parents later adopted a daughter, Patty.[39] Paul worked as a mechanic and a carpenter, and taught his son rudimentary electronics and how to work with his hands.[39] Paul showed Steve how to work on electronics in the family garage, demonstrating to his son how to take apart and rebuild electronics such as radios and televisions. As a result, he became interested in and developed a hobby of technical tinkering.[45]\n\nClara was an accountant[44] who taught him to read before he went to school.[39] Clara Jobs had been a payroll clerk for Varian Associates, one of the first high-tech firms in what became known as Silicon Valley.[46]\n\nJobs&#039;s youth was riddled with frustrations over formal schooling. At Monta Loma Elementary school in Mountain View, he frequently played pranks on others.[47] Though school officials recommended that he skip two grades on account of his test scores, his parents elected for him to skip only one grade.[38][47]\n\nJobs then attended Cupertino Junior High and Homestead High School in Cupertino, California.[40] At Homestead, Jobs became friends with Bill Fernandez, a neighbor who shared the same interests in electronics. Fernandez introduced Jobs to his neighbor, Steve Wozniak, a computer and electronics whiz kid, who was also known as &quot;Woz&quot;. In 1969 Wozniak started building a little computer board with Fernandez that they named &quot;The Cream Soda Computer&quot;, which they showed to Jobs; he seemed really interested.[48] Wozniak has stated that they called it the Cream Soda Computer because he and Fernandez drank cream soda all the time whilst they worked on it and that he and Jobs had gone to the same high school, although they did not know each other there.[49]\n\nFollowing high school graduation in 1972, Jobs enrolled at Reed College in Portland, Oregon. Reed was an expensive college which Paul and Clara could ill afford. They were spending much of their life savings on their son&#039;s higher education.[48] Jobs dropped out of college after six months and spent the next 18 months dropping in on creative classes, including a course on calligraphy.[50] In the commencement address he gave at Stanford, Jobs said that, while he continued to audit classes at Reed, he slept on the floor in friends&#039; dorm rooms, returned Coke bottles for food money, and got weekly free meals at the local Hare Krishna temple.[51] In that same speech, Jobs said: &quot;If I had never dropped in on that single calligraphy course in college, the Mac would have never had multiple typefaces or proportionally spaced fonts.&quot;', 4),
(42, 'Daniele', 'Del Giudice', 'Livorno', 'Italy', 'Italian, English', '586 124 556', 2, '../deltabase/img/usr/profile_pic/a1d0c6e83f027327d8461063f4ac58a6.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque consequat justo mi, id tincidunt ex rutrum eget. Proin nibh metus, hendrerit nec rhoncus eu, pretium eget sapien. Nunc id bibendum sapien. In vitae pharetra tortor, a bibendum massa. Nullam non finibus neque, id vulputate augue. Duis eu consequat ligula, non ornare nisl. Praesent ultrices dui quis euismod malesuada. Ut metus sem, ornare mattis consequat vel, molestie quis sem. Donec ac purus congue erat viverra pretium.\n\nNam elementum justo et nisi iaculis, quis sagittis velit consequat. Suspendisse lacinia, ligula ut lobortis ullamcorper, sapien nibh auctor dui, et tempor justo massa quis nisi. In et mauris ex. Nullam nisi tellus, pharetra non purus vel, ultrices congue odio. Morbi ac porta enim. Ut ipsum elit, consequat ut elementum eget, dictum at justo. Aenean sollicitudin sodales augue. Donec sed lacus sit amet ante consequat fermentum eget a est.\n\nCras sit amet mauris lacinia, commodo dolor a, suscipit dolor. Sed eu tempor erat. Donec eget urna risus. Maecenas eu fermentum dolor. Curabitur ornare vitae turpis at feugiat. Nunc at ante a enim consequat condimentum eget a dui. Aenean eget efficitur lectus. Curabitur ligula lacus, porta nec ultricies eget, posuere ac sem.\n\nIn ut libero non erat lacinia auctor ac ornare sapien. Phasellus at nisl vel lectus faucibus pellentesque. Integer id molestie eros, ut convallis turpis. Cras sed elementum nunc. Fusce porta maximus velit, pretium aliquam sapien varius et. Vivamus congue interdum turpis. In hac habitasse platea dictumst. Pellentesque vehicula consectetur ex, quis mollis neque pretium non. In eget lectus at est laoreet facilisis. Nullam laoreet, enim at interdum aliquet, ipsum nunc laoreet arcu, sed semper dui lorem vel quam. Nulla ac dignissim velit, quis vestibulum turpis. Proin commodo urna id vehicula sollicitudin. Nulla gravida, elit et lacinia volutpat, tellus lectus pretium est, sit amet rutrum tellus erat quis odio. In hac habitasse platea dictumst.\n\nMorbi id tempor lorem. Aliquam consectetur lacus non est laoreet elementum. In ut enim orci. Sed tincidunt libero dui, in cursus metus consequat quis. Pellentesque blandit venenatis augue, quis mattis quam iaculis quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam suscipit libero id vestibulum mattis. Vestibulum in egestas magna. Quisque laoreet condimentum semper. Suspendisse blandit augue lacus, et bibendum ex fermentum interdum. Donec porttitor velit metus, nec auctor metus iaculis ac.', 4.75),
(44, 'Natalie', 'Portman', 'Los Angeles', 'United States', 'English', '(555) 123 425 ', 1, '../deltabase/img/usr/profile_pic/f7177163c833dff4b38fc8d2872f1ec6.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id pretium nunc. Nam elementum nunc sed ex venenatis suscipit. Nulla eu quam metus. Nam venenatis scelerisque odio, quis faucibus libero hendrerit id. Vestibulum in dolor ante. Cras et nulla egestas quam fermentum consectetur. Duis enim nisi, suscipit venenatis dolor luctus, gravida tempus ipsum. Pellentesque bibendum tempus lacus in scelerisque. Quisque ac sem condimentum, viverra mauris vitae, maximus mauris.\r\n\r\nMaecenas nec neque at mi posuere facilisis. Sed ultricies est sit amet quam efficitur, at varius eros ullamcorper. Quisque vitae felis eget ligula malesuada mattis. Maecenas consequat sapien ac pulvinar maximus. Mauris eu malesuada justo. Proin convallis vulputate consectetur. Donec semper, dui et venenatis tincidunt, velit felis tristique urna, dictum placerat urna purus id leo. Praesent laoreet eleifend ex non convallis.\r\n\r\nVestibulum nec lorem faucibus, tempus quam eu, vestibulum libero. Donec id quam tellus. Mauris sed commodo odio. Cras consectetur orci vitae neque mollis aliquet. Praesent dignissim faucibus massa sed varius. In vel neque at nisi rhoncus accumsan. Sed lacinia lectus nec eleifend malesuada. Nunc rhoncus mi nulla, quis placerat nunc malesuada quis. Nulla id nulla ut nisi accumsan fermentum et vitae nibh. Nunc aliquam dictum fringilla. Vestibulum dignissim condimentum sapien mollis congue. Fusce tempus ac enim eget feugiat.\r\n\r\nPellentesque ut interdum leo. Donec vitae auctor mi. Praesent at volutpat risus. Aenean risus eros, interdum fringilla ante vel, condimentum fringilla tortor. Ut quis placerat odio. Quisque id congue lacus. In ornare scelerisque eros, nec ullamcorper quam.\r\n\r\nNulla facilisi. Maecenas tempor erat pulvinar leo condimentum facilisis id quis sapien. Cras ac gravida sem, vel ultrices mi. Sed accumsan urna ut tellus finibus, et tempor mi viverra. Sed ullamcorper at turpis at porttitor. Fusce id mi auctor felis vehicula tristique. Aenean in aliquet ex. Morbi at nulla quis ipsum vulputate vulputate. Duis quis ultrices lorem, nec elementum dui. Sed ut est suscipit, tincidunt leo id, ornare purus.', 0),
(45, 'Gabriele', 'Serra', 'Livorno', 'Italy', 'Italian, English', '0564 861122', 2, '../deltabase/img/usr/profile_pic/6c8349cc7260ae62e3b1396831a8398f.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam maximus vitae purus convallis euismod. Aenean id est et nibh porttitor tristique. Mauris congue, sem ac aliquet sollicitudin, libero leo consectetur urna, vel accumsan mi risus et ante. Pellentesque vitae massa eros. Quisque ultricies leo auctor, malesuada augue sagittis, sagittis quam. Phasellus leo sapien, consequat eu metus nec, fringilla fermentum nisi. Aliquam iaculis augue nisi, a iaculis nunc sodales at. Cras faucibus pellentesque dignissim. Curabitur vel fringilla odio, non hendrerit ante. In egestas vestibulum mi vel rutrum. Sed pellentesque, lorem sollicitudin egestas aliquam, mauris sem posuere odio, eget ornare nisl lorem sed ante. Proin et maximus nibh. Pellentesque eget erat mi. Morbi posuere, ante a tincidunt rhoncus, dolor neque elementum ipsum, sit amet rhoncus metus felis sed metus.', 5),
(46, 'Paolo', 'Paolini', 'Milano', 'Italy', 'English, Italian, Chinese', '045 657 424', 2, '../deltabase/img/usr/profile_pic/d9d4f495e875a2e075a1a4a6e1b9770f.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus a purus elit. Phasellus vulputate sapien nec ornare tempus. Morbi et finibus nulla, nec porta dui. Fusce in augue arcu. Sed quis cursus felis, ut fringilla tellus. Nunc non purus cursus, placerat urna eget, bibendum lorem. Donec tempor porttitor tortor, vel dapibus elit molestie a. Praesent eget felis molestie, luctus sapien in, ultrices eros. Proin vel justo dui. Nulla vitae nulla in lectus fermentum porttitor non convallis tortor. Donec placerat viverra metus, sit amet posuere purus condimentum vitae.\r\n\r\nCras in feugiat magna. Quisque at finibus orci, ac volutpat neque. Quisque a eros eros. Praesent condimentum pellentesque lacus sit amet accumsan. Mauris a sodales orci, quis hendrerit magna. Praesent hendrerit mattis arcu nec fermentum. Nulla nec neque nec libero varius pretium non ut magna. Mauris sit amet vehicula quam. Nulla id tortor a mi vulputate lobortis. Proin a ultricies velit. Suspendisse maximus molestie fermentum. Mauris laoreet bibendum nisi, sed tristique urna. Nunc elementum ex vel dolor porttitor, non varius odio viverra. Maecenas ultricies, odio eu pretium luctus, lectus justo ultricies nisi, et malesuada orci dui sed ante. Cras gravida lorem velit, eget consectetur ligula molestie a.\r\n\r\nPhasellus eu ex nec tortor lacinia condimentum vitae ac purus. Etiam molestie tortor eget odio gravida, laoreet volutpat lorem aliquet. Nunc congue, arcu eget mollis laoreet, neque erat gravida sapien, id convallis quam nibh id ante. Ut ultricies dui nulla, ac lobortis dui ornare eu. Integer lobortis egestas eros sit amet ultrices. Nunc blandit semper velit ac egestas. Vivamus nec sagittis risus. Fusce gravida condimentum urna eget euismod.\r\n\r\nNunc sollicitudin fringilla suscipit. Nullam euismod nisi nunc, eget tempor enim tincidunt a. Donec dui magna, efficitur sed blandit in, tristique eget dolor. Cras consectetur purus ac nisi volutpat hendrerit. Pellentesque aliquet id mauris a posuere. Proin lobortis efficitur erat, quis luctus tellus. Curabitur mattis nunc libero, vitae tristique nibh dictum eget. Proin bibendum elit ut imperdiet aliquet. Proin interdum justo in lobortis semper. Nunc in eleifend est. Mauris vestibulum nulla quis nisl hendrerit porta.', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `usr_skill`
--

CREATE TABLE IF NOT EXISTS `usr_skill` (
  `id_skill` int(11) NOT NULL,
  `id_usr` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id_skill`,`id_usr`),
  KEY `value` (`id_usr`),
  KEY `value_2` (`id_usr`),
  KEY `id_usr` (`id_usr`),
  KEY `id_skill` (`id_skill`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `usr_skill`
--

INSERT INTO `usr_skill` (`id_skill`, `id_usr`, `value`) VALUES
(1, 1, 40),
(1, 15, 60),
(1, 19, 60),
(1, 24, 60),
(1, 26, 60),
(1, 39, 60),
(1, 40, 60),
(1, 42, 90),
(1, 44, 74),
(1, 45, 90),
(1, 46, 1),
(2, 1, 40),
(2, 15, 60),
(2, 19, 60),
(2, 24, 60),
(2, 26, 60),
(2, 39, 60),
(2, 40, 60),
(2, 42, 90),
(2, 44, 74),
(2, 45, 90),
(3, 1, 50),
(3, 15, 60),
(3, 19, 60),
(3, 24, 60),
(3, 26, 60),
(3, 39, 60),
(3, 40, 60),
(3, 42, 90),
(3, 44, 74),
(3, 45, 90),
(4, 1, 44),
(4, 15, 60),
(4, 19, 60),
(4, 24, 60),
(4, 26, 60),
(4, 39, 60),
(4, 40, 60),
(4, 42, 90),
(4, 44, 74),
(4, 45, 90),
(5, 1, 60),
(5, 15, 60),
(5, 19, 60),
(5, 24, 60),
(5, 26, 60),
(5, 39, 60),
(5, 40, 60),
(5, 42, 90),
(5, 44, 74),
(5, 45, 90),
(6, 1, 60),
(6, 15, 60),
(6, 19, 60),
(6, 24, 60),
(6, 26, 60),
(6, 39, 60),
(6, 40, 60),
(6, 42, 90),
(6, 44, 74),
(6, 45, 90),
(7, 1, 60),
(7, 15, 60),
(7, 19, 60),
(7, 24, 60),
(7, 26, 60),
(7, 39, 60),
(7, 40, 60),
(7, 42, 90),
(7, 44, 74),
(7, 45, 90),
(8, 1, 60),
(8, 15, 60),
(8, 19, 60),
(8, 24, 60),
(8, 26, 60),
(8, 39, 60),
(8, 40, 60),
(8, 42, 90),
(8, 44, 74),
(8, 45, 90),
(9, 1, 40),
(9, 15, 60),
(9, 24, 60),
(10, 1, 50),
(10, 15, 60),
(10, 19, 60),
(10, 24, 60),
(10, 26, 60),
(10, 39, 60),
(10, 40, 60),
(10, 42, 90),
(10, 44, 74),
(10, 45, 90),
(11, 1, 50),
(11, 15, 60),
(11, 19, 60),
(11, 24, 60),
(11, 26, 60),
(11, 39, 60),
(11, 40, 60),
(11, 42, 90),
(11, 44, 74),
(11, 45, 90),
(12, 1, 45),
(12, 15, 60),
(12, 19, 60),
(12, 24, 60),
(12, 26, 60),
(12, 39, 60),
(12, 40, 60),
(12, 42, 90),
(12, 44, 74),
(12, 45, 90),
(13, 1, 50),
(13, 15, 60),
(13, 19, 45),
(13, 24, 45),
(13, 26, 45),
(13, 39, 45),
(13, 40, 45),
(13, 42, 45),
(13, 44, 45),
(13, 45, 45),
(14, 1, 60),
(14, 15, 60),
(14, 19, 60),
(14, 24, 60),
(14, 26, 60),
(14, 39, 60),
(14, 40, 60),
(14, 42, 90),
(14, 44, 74),
(14, 45, 90),
(15, 1, 60),
(15, 15, 60),
(15, 19, 60),
(15, 24, 60),
(15, 26, 60),
(15, 39, 60),
(15, 40, 60),
(15, 42, 90),
(15, 44, 74),
(15, 45, 90);

-- --------------------------------------------------------

--
-- Struttura della tabella `_notification`
--

CREATE TABLE IF NOT EXISTS `_notification` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_company` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `received` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(45) NOT NULL,
  PRIMARY KEY (`id_notification`,`id_user`,`id_company`),
  KEY `usr_not` (`id_user`),
  KEY `compusr_not` (`id_company`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dump dei dati per la tabella `_notification`
--

INSERT INTO `_notification` (`id_notification`, `id_user`, `id_company`, `id_category`, `start_time`, `received`, `link`) VALUES
(1, 1, 1, 1, '2014-11-29 21:13:56', 1, '11'),
(12, 1, 1, 2, '2014-12-07 14:06:07', 1, 'profile.php?u=1&cid=13#collaboration-s'),
(13, 1, 1, 2, '2014-12-07 14:14:07', 1, 'profile.php?u=1&cid=13#collaboration-s'),
(15, 19, 1, 1, '2014-12-14 00:35:19', 1, '15'),
(16, 24, 1, 1, '2014-12-14 21:09:45', 1, '16'),
(17, 26, 1, 1, '2014-12-14 21:09:45', 1, '17'),
(18, 39, 1, 1, '2014-12-14 21:09:45', 1, '18'),
(19, 40, 1, 1, '2014-12-14 21:09:45', 1, '19'),
(20, 15, 1, 1, '2014-12-14 21:09:45', 1, '20'),
(22, 15, 3, 1, '2014-12-14 23:37:58', 0, '21'),
(23, 42, 5, 1, '2014-12-15 14:09:47', 0, '22'),
(24, 42, 1, 1, '2014-12-15 14:09:47', 0, '23'),
(26, 45, 5, 1, '2014-12-18 13:57:45', 0, '24'),
(31, 1, 1, 1, '2014-12-21 15:09:12', 1, '38'),
(32, 1, 2, 1, '2014-12-21 15:50:50', 1, '45'),
(33, 1, 1, 1, '2014-12-21 17:45:47', 1, '46'),
(36, 1, 1, 1, '2014-12-21 17:57:15', 1, '49'),
(37, 1, 1, 1, '2014-12-21 18:00:28', 1, '50'),
(38, 1, 1, 2, '2014-12-21 21:13:34', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(39, 1, 1, 2, '2014-12-21 21:13:51', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(40, 1, 1, 2, '2014-12-21 21:13:54', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(41, 1, 1, 2, '2014-12-21 21:14:11', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(42, 1, 1, 2, '2014-12-21 21:14:54', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(43, 1, 1, 2, '2014-12-21 21:14:57', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(44, 1, 1, 2, '2014-12-21 21:15:10', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(45, 1, 1, 2, '2014-12-21 21:15:28', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(46, 1, 1, 2, '2014-12-21 21:15:35', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(47, 1, 1, 2, '2014-12-21 21:15:37', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(48, 1, 1, 2, '2014-12-21 21:15:47', 1, 'profile.php?u=1&cid=50#collaboration-s'),
(49, 1, 1, 2, '2014-12-22 17:20:44', 1, 'profile.php?u=1&cid=50#collaboration-s');

-- --------------------------------------------------------

--
-- Struttura della tabella `_review`
--

CREATE TABLE IF NOT EXISTS `_review` (
  `id_user` int(11) NOT NULL,
  `avg` float NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `_review`
--

INSERT INTO `_review` (`id_user`, `avg`) VALUES
(1, 3.75),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3),
(49, 3),
(50, 3),
(51, 3),
(52, 3),
(53, 3);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `collaboration`
--
ALTER TABLE `collaboration`
  ADD CONSTRAINT `collaboration_id_company` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `collaboration_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `com_inbox`
--
ALTER TABLE `com_inbox`
  ADD CONSTRAINT `cominbox_from` FOREIGN KEY (`from_usr`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cominbox_id_company` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `com_information`
--
ALTER TABLE `com_information`
  ADD CONSTRAINT `company_category` FOREIGN KEY (`id_category`) REFERENCES `job_category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `info_id_company` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `pay_attention`
--
ALTER TABLE `pay_attention`
  ADD CONSTRAINT `att_id_company` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `att_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `portfolio`
--
ALTER TABLE `portfolio`
  ADD CONSTRAINT `port_category` FOREIGN KEY (`id_category`) REFERENCES `job_category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `port_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `skill_category` FOREIGN KEY (`id_category`) REFERENCES `job_category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `usr_inbox`
--
ALTER TABLE `usr_inbox`
  ADD CONSTRAINT `usrinbox_from_com` FOREIGN KEY (`from_com`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usrinbox_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `usr_information`
--
ALTER TABLE `usr_information`
  ADD CONSTRAINT `info_fav_job` FOREIGN KEY (`id_category`) REFERENCES `job_category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `info_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `usr_skill`
--
ALTER TABLE `usr_skill`
  ADD CONSTRAINT `skill_usrskill` FOREIGN KEY (`id_skill`) REFERENCES `skill` (`id_skill`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usrskill_usr` FOREIGN KEY (`id_usr`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `_notification`
--
ALTER TABLE `_notification`
  ADD CONSTRAINT `compusr_not` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usr_not` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
