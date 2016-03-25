-- MySQL dump 10.13  Distrib 5.6.26, for osx10.8 (x86_64)
--
-- Host: localhost    Database: project
-- ------------------------------------------------------
-- Server version	5.6.26-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin','2016-02-21 00:00:00','2016-02-21 00:00:00');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  KEY `admins_activation_code_index` (`activation_code`),
  KEY `admins_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin@change.me','$2y$10$vtQZdD2kkPmJ.5ZntTu62O4htHWPVdlTYvKYKoap0ObZEGrMP1gom',NULL,0,NULL,NULL,NULL,NULL,NULL,'c16KwMvI6oWTxhEdbcf0Xq9RU1LMWUpwE3hmThZOfCpXnxlVE5hNKbxGK2OL',NULL,NULL,'2016-03-04 09:29:07','2016-03-04 09:32:07');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Footwear','2016-02-22 00:00:00','2016-02-22 00:00:00'),(5,'Apparel','2016-02-22 15:12:06','2016-02-22 15:45:55'),(9,'electronics','2016-03-01 17:15:02','2016-03-01 17:15:02'),(12,'fasdfafasf2343','2016-03-04 17:14:46','2016-03-04 17:14:58'),(13,'fasfaf23434324242343242','2016-03-08 20:13:02','2016-03-08 20:13:02');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dynamic_attributes`
--

DROP TABLE IF EXISTS `dynamic_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dynamic_attributes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `store_id` int(10) NOT NULL,
  `latest_crawl` tinyint(4) NOT NULL DEFAULT '1',
  `mrp` varchar(25) NOT NULL,
  `offer_price` varchar(25) NOT NULL,
  `discount_percentage` varchar(25) NOT NULL,
  `discount_value` varchar(25) NOT NULL,
  `available_sizes` varchar(200) NOT NULL,
  `delivery_time` varchar(200) NOT NULL,
  `is_cod_available` varchar(100) NOT NULL,
  `product_rating` varchar(50) NOT NULL,
  `product_rating_count` varchar(50) NOT NULL,
  `review_posted_date` varchar(200) DEFAULT NULL,
  `review_title` varchar(150) NOT NULL,
  `review` varchar(500) NOT NULL,
  `seller_name` varchar(100) DEFAULT NULL,
  `seller_rating` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_attributes`
--

LOCK TABLES `dynamic_attributes` WRITE;
/*!40000 ALTER TABLE `dynamic_attributes` DISABLE KEYS */;
INSERT INTO `dynamic_attributes` VALUES (2,1,2,1,1,1,'5000','3000','40','','8,9,10','7-10 Business days.','May be available.Depends on locality','4.3/5','12','2016-02-01','Average product','Facing some issue with the quality of product.','WS retail','4','2016-02-21 00:00:00','2016-02-21 00:00:00'),(3,100006,1,1,3,1,'2499','1494','40','','6,7,8,10','Delivered by Thu, 3rd Mar','Available','4.6','19','Feb 19, 2016','AWESOME PRODUCT. TOTAL VALUE FOR MONEY','this pair of shoes are pretty good regarding their price tag. the feel is certainly brandy and also fits quite well. a must buy for all.','WS Retail','4.2','2016-02-22 18:43:07','2016-02-22 18:43:07'),(4,100006,1,1,4,1,'2499','1799','28','700','6,7','3 - 7 business days.','Available','4','3','16 August 2015','','May be another halfbinch would be perfect. Else good.','Dream Deals1970 ','4.1','2016-02-22 18:49:10','2016-02-22 18:49:10'),(5,100006,1,1,5,1,'','1799','','','9','Estimated by Wed, 24-Feb ','Not Available','','','','','','themsglap_worldwide','97.8% Positive feedback','2016-02-22 18:54:20','2016-02-22 18:54:20'),(6,100010,1,0,3,0,'Rs. 3,999','Rs. 2,195',' 45%','','6,7,8,9,10,11','Usually Delivered in 2-3 business days.','\n                May be available! Enter Pincode to confirm.\n        ','5 stars','\n\n                    ','','','','WS Retail','\n                            4.2 / 5\n             ','2016-02-28 17:39:32','2016-02-28 17:39:32'),(7,100010,1,0,3,1,'Rs. 3,999','Rs. 2,195',' 45%','','6,7,8,9,10,11','Usually Delivered in 2-3 business days.','\n                May be available! Enter Pincode to confirm.\n        ','5 stars','\n\n                    ','','','','WS Retail','\n                            4.2 / 5\n             ','2016-02-28 17:42:04','2016-02-28 17:42:04');
/*!40000 ALTER TABLE `dynamic_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footwear`
--

DROP TABLE IF EXISTS `footwear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `footwear` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Unisex','') NOT NULL,
  `images_path` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footwear`
--

LOCK TABLES `footwear` WRITE;
/*!40000 ALTER TABLE `footwear` DISABLE KEYS */;
INSERT INTO `footwear` VALUES (1,'Reebok Men\'s Exclusive Runner Lp Running Shoes\r\n','Reebok','Male','http://www.amazon.in','2016-03-02 00:00:00','2016-03-02 00:00:00'),(2,'Nike Men Black Air Max Vista Sports Shoes\r\n','Nike','Male','http://www.amazon.in','2016-03-02 00:00:00','2016-03-02 00:00:00');
/*!40000 ALTER TABLE `footwear` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table',1),('2014_10_12_100000_create_password_resets_table',1),('2014_11_16_205658_create_admins_table',2),('2014_12_02_152920_create_password_reminders_table',2),('2015_02_20_130902_create_url_table',2),('2015_03_15_123956_edit_url_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reminders`
--

DROP TABLE IF EXISTS `password_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reminders` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_reminders_email_index` (`email`),
  KEY `password_reminders_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reminders`
--

LOCK TABLES `password_reminders` WRITE;
/*!40000 ALTER TABLE `password_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('shubhamsharma160792@gmail.com','839ffbaec8e7877a71d152cf6002d7325e5a29e5e0fb27a4f29f69ab362b62e8','2016-03-25 07:02:33');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL,
  `subcategory_id` int(10) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'footwear','2016-03-02 00:00:00','2016-03-02 00:00:00');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_product_url`
--

DROP TABLE IF EXISTS `store_product_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_product_url` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `subcategory_id` int(10) NOT NULL,
  `store_id` int(10) NOT NULL,
  `product_url` varchar(500) NOT NULL,
  `store_product_unique_identifier` varchar(200) NOT NULL,
  `is_active` tinyint(10) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_category_id` (`category_id`),
  KEY `fk_store_id` (`store_id`),
  KEY `fk_subcategory_id` (`subcategory_id`),
  CONSTRAINT `fk_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_store_id` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
  CONSTRAINT `fk_subcategory_id` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_product_url`
--

LOCK TABLES `store_product_url` WRITE;
/*!40000 ALTER TABLE `store_product_url` DISABLE KEYS */;
INSERT INTO `store_product_url` VALUES (1,100001,1,1,4,'http://www.amazon.in/dp/B013DTSY3O','B013DTSY3O',1,'2016-02-22 17:24:50','2016-02-22 17:24:50'),(2,100002,1,1,4,'http://www.amazon.in/dp/B00YMJNBCY','B00YMJNBCY',1,'2016-02-22 17:26:13','2016-02-22 17:26:13'),(3,2,1,1,4,'http://www.amazon.in/dp/B00NJOOZBO','B00NJOOZBO',1,'2016-02-22 17:26:47','2016-02-22 17:26:47'),(4,100004,1,1,4,'http://www.amazon.in/dp/B018TYYN3I','B018TYYN3I',1,'2016-02-22 17:29:52','2016-02-22 17:29:52'),(5,100005,1,1,4,'http://www.amazon.in/dp/B00JYGU4CU','B00JYGU4CU',1,'2016-02-22 17:30:29','2016-02-22 17:30:29'),(6,1,1,1,4,'http://www.amazon.in/dp/B0111QEW6E','B0111QEW6E',1,'2016-02-22 17:33:01','2016-02-22 17:33:01'),(7,1,1,1,5,'http://www.ebay.in/itm/182030347910','182030347910',1,'2016-02-22 17:33:49','2016-02-22 17:33:49'),(8,1,1,1,3,'http://www.flipkart.com/reebok-exclusive-runner-lp-running-shoes/p/itmedtgzvgkxphfw','',1,'2016-02-22 17:34:19','2016-02-22 17:34:19'),(9,100010,1,1,3,'http://www.flipkart.com/puma-agility-dp-running-shoes/p/itmedgdvzfgzgwrr?pid=SHOEDGDVSHKABJR5&al=rIHwDzviBvEROAvQgcOcscldugMWZuE7eGHgUTGjVrqay%2FBj6AKju8%2F%2BWIOw37b09nlTXKYmdA0%3D&ref=L%3A-5156052665722187940&srno=b_16','SHOEDGDVSHKABJR5',1,'2016-02-28 00:00:00','2016-02-28 00:00:00'),(10,100010,1,1,3,'http://www.flipkart.com/puma-agility-dp-running-shoes/p/itmedgdvzfgzgwrr?pid=SHOEDGDVSHKABJR5&al=rIHwDzviBvEROAvQgcOcscldugMWZuE7eGHgUTGjVrqay%2FBj6AKju8%2F%2BWIOw37b09nlTXKYmdA0%3D&ref=L%3A-5156052665722187940&srno=b_16','SHOEDGDVSHKABJR5',1,'2016-03-04 13:09:28','2016-03-04 13:21:13');
/*!40000 ALTER TABLE `store_product_url` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `revenue_type` enum('CPC','CPS') NOT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,'snapdeal','CPC',1,'2016-02-22 16:29:00','2016-02-22 16:29:00'),(3,'flipkart','CPC',1,'2016-02-22 16:31:05','2016-02-22 16:31:05'),(4,'amazon','CPC',1,'2016-02-22 16:31:22','2016-02-22 16:31:22'),(5,'ebay','CPS',1,'2016-02-22 16:31:29','2016-02-22 16:31:29'),(6,'shopclues','CPS',1,'2016-02-22 16:31:34','2016-02-22 16:31:34');
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category_id` int(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category` (`category_id`),
  CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (1,'sports shoes',1,'2016-02-22 00:00:00','2016-02-22 00:00:00'),(3,'sandal',1,'2016-02-22 16:03:57','2016-02-22 16:03:57'),(6,'fasfaf',9,'2016-03-01 12:35:40','2016-03-01 21:13:45'),(7,'fasdfdasfa',13,'2016-03-08 20:13:13','2016-03-08 20:13:13');
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribed_members`
--

DROP TABLE IF EXISTS `subscribed_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribed_members` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `sent_newsletter_count` int(10) NOT NULL,
  `is_valid` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribed_members`
--

LOCK TABLES `subscribed_members` WRITE;
/*!40000 ALTER TABLE `subscribed_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscribed_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login`
--

DROP TABLE IF EXISTS `user_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login`
--

LOCK TABLES `user_login` WRITE;
/*!40000 ALTER TABLE `user_login` DISABLE KEYS */;
INSERT INTO `user_login` VALUES (1,'user','root@123.com','123','2016-01-25 12:48:29','2016-01-25 12:48:29');
/*!40000 ALTER TABLE `user_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_query`
--

DROP TABLE IF EXISTS `user_query`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_query` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(250) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `is_resolved` tinyint(4) NOT NULL DEFAULT '0',
  `remark` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_query`
--

LOCK TABLES `user_query` WRITE;
/*!40000 ALTER TABLE `user_query` DISABLE KEYS */;
INSERT INTO `user_query` VALUES (1,'user1','test@123.com','sample subject','sample message',0,'','2016-01-25 15:15:14','2016-01-25 15:15:14');
/*!40000 ALTER TABLE `user_query` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Shubham Sharma','shubhamsharma160792@gmail.com','$2y$10$SSwELpZzpaWkUTbWHJSZ2e9d1Z5tuC02kJCFa7ZFLgZf.Whde8nIa','V67RuZIErpoB9BXJuBRRzgeplvhSCUYuK5ImpKl6fyl3vu3YFD4s9mWZsCAz','2016-03-04 12:27:17','2016-03-25 07:00:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-25 16:24:58
