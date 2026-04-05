-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: kdnvpp
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (7,'default','{\"uuid\":\"190da848-203d-49a5-a344-bd6a581a50aa\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:26;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774343667,1774343667),(8,'default','{\"uuid\":\"c227cf34-1b16-4644-a2bc-e8f3caed6057\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:27;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774345495,1774345495),(9,'default','{\"uuid\":\"6d41684e-2fab-421f-8ca8-05bf7204c4b4\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:28;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774345704,1774345704),(10,'default','{\"uuid\":\"afb54d38-003f-40b5-8be3-fe85b9c06b8e\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:29;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774360969,1774360969),(11,'default','{\"uuid\":\"5b4ebca5-0053-4378-9993-2f80c37773bb\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:30;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774362669,1774362669),(12,'default','{\"uuid\":\"fd22588f-d190-4389-a628-13411adc262c\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:31;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774402844,1774402844),(13,'default','{\"uuid\":\"94ed6561-f67e-4aed-b5b1-b3c97428e294\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:32;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774402975,1774402975),(14,'default','{\"uuid\":\"b5d9e714-d403-48ec-a550-79802a693fc7\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:33;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774404172,1774404172),(15,'default','{\"uuid\":\"1f7b4c9c-fffd-4660-907a-d005751f763a\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:34;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774404696,1774404696),(16,'default','{\"uuid\":\"89b12194-6fa1-4920-ac95-a041e8971de6\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:35;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774405223,1774405223),(17,'default','{\"uuid\":\"ca8003bf-54ab-4863-8dd9-ea7b3474f8c1\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:36;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774405366,1774405366),(18,'default','{\"uuid\":\"ba79a148-124b-40e5-9d98-3c872a124547\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:37;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1774414471,1774414471),(19,'default','{\"uuid\":\"80c7a820-f572-4b84-b372-0fedfc2ad4c2\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:38;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1775035312,1775035312),(20,'default','{\"uuid\":\"8e16e2e4-2ac4-446b-be71-323472275011\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:39;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1775098912,1775098912),(21,'default','{\"uuid\":\"54e10e4f-5c81-4e47-86dd-9ec319a7aa9e\",\"displayName\":\"App\\\\Mail\\\\NewStopCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:27:\\\"App\\\\Mail\\\\NewStopCreatedMail\\\":3:{s:4:\\\"stop\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Stop\\\";s:2:\\\"id\\\";i:40;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:3:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"admin@pvgas.com.vn\\\";}i:1;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:21:\\\"ngoc.tta@pvgas.com.vn\\\";}i:2;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"khoa.pd@pvgas.com.vn\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}',0,NULL,1775099176,1775099176);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_resets_table',1),(2,'2019_08_19_000000_create_failed_jobs_table',1),(3,'2019_12_14_000001_create_personal_access_tokens_table',1),(4,'2024_01_01_000000_create_users_table',1),(5,'2026_01_08_070132_create_office_supplies_table',1),(6,'2026_01_08_070346_create_supply_requests_table',1),(7,'2026_01_08_070403_create_request_items_table',1),(8,'2026_01_08_143543_add_requester_department_to_supply_requests_table',1),(9,'2026_01_09_023012_add_period_to_supply_requests_table',1),(10,'2026_01_09_024023_add_requester_position_to_supply_requests_table',1),(11,'2026_01_09_025040_fix_supply_requests_table_columns',1),(12,'2026_01_09_030701_fix_department_column_in_supply_requests',1),(13,'2026_01_12_015759_add_tchc_workflow_columns',1),(14,'2026_01_12_021737_fix_status_column_length',1),(15,'2026_01_26_073459_add_indexes_for_performance',1),(16,'2026_01_26_170000_create_admin_user',1),(17,'2026_01_28_142706_update_users_role_enum',1),(18,'2026_03_04_103030_create_stops_table',2),(19,'2026_03_04_105845_add_issue_category_to_stops_table',3),(20,'2026_03_04_161154_add_observer_phone_to_stops_table',4),(22,'2026_03_05_100523_add_priority_level_to_stops_table',5),(23,'2026_03_05_152019_add_priority_scorer_to_stops_table',6),(24,'2026_03_22_111751_create_jobs_table',7),(25,'2026_03_22_123240_add_indexes_to_stops_for_performance',8),(26,'2026_03_22_200000_add_zalo_user_id_to_users_table',9),(27,'2026_03_24_090000_create_stop_score_histories_table',9),(28,'2026_03_24_120000_add_role_based_score_fields_to_stops_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_supplies`
--

DROP TABLE IF EXISTS `office_supplies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_supplies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `office_supplies_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_supplies`
--

LOCK TABLES `office_supplies` WRITE;
/*!40000 ALTER TABLE `office_supplies` DISABLE KEYS */;
INSERT INTO `office_supplies` VALUES (1,'Bút bi xanh','Bút bi ngòi 1.0mm, mực xanh','cái',5000.00,500,'Dụng cụ viết',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(2,'Bút bi đỏ','Bút bi ngòi 1.0mm, mực đỏ','cái',5000.00,300,'Dụng cụ viết',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(3,'Bút chì 2B','Bút chì gỗ độ cứng 2B','cái',3000.00,200,'Dụng cụ viết',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(4,'Giấy A4','Giấy A4 80gsm (1 ream = 500 tờ)','ream',95000.00,100,'Giấy in',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(5,'Giấy A3','Giấy A3 80gsm (1 ream = 500 tờ)','ream',180000.00,50,'Giấy in',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(6,'Kẹp giấy','Kẹp giấy kim loại cỡ nhỏ','hộp',15000.00,80,'Văn phòng phẩm',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(7,'Thước kẻ 30cm','Thước kẻ nhựa trong suốt 30cm','cái',8000.00,150,'Dụng cụ đo',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(8,'Keo dán UHU','Keo dán đa năng UHU 40g','tuýp',25000.00,60,'Văn phòng phẩm',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(9,'Băng dính trong','Băng dính trong suốt 2cm x 30m','cuộn',12000.00,120,'Văn phòng phẩm',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(10,'Tẩy trắng','Tẩy trắng bút chì','cái',4000.00,200,'Dụng cụ viết',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(11,'Bìa lưu trữ','Bìa lưu trữ hồ sơ A4','cái',35000.00,80,'Lưu trữ',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(12,'Kéo văn phòng','Kéo văn phòng cỡ trung','cái',45000.00,40,'Dụng cụ cắt',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(13,'Ghim bấm','Ghim bấm kim loại cỡ nhỏ','hộp',8000.00,100,'Văn phòng phẩm',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(14,'Máy bấm ghim','Máy bấm ghim cỡ trung','cái',120000.00,25,'Thiết bị văn phòng',1,'2026-02-02 09:04:31','2026-02-02 09:04:31'),(15,'Bút highlight vàng','Bút đánh dấu màu vàng','cái',12000.00,80,'Dụng cụ viết',1,'2026-02-02 09:04:31','2026-02-02 09:04:31');
/*!40000 ALTER TABLE `office_supplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_items`
--

DROP TABLE IF EXISTS `request_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_request_id` bigint(20) unsigned NOT NULL,
  `office_supply_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_items_supply_request_id_index` (`supply_request_id`),
  KEY `request_items_office_supply_id_index` (`office_supply_id`),
  KEY `request_items_supply_request_id_office_supply_id_index` (`supply_request_id`,`office_supply_id`),
  CONSTRAINT `request_items_office_supply_id_foreign` FOREIGN KEY (`office_supply_id`) REFERENCES `office_supplies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `request_items_supply_request_id_foreign` FOREIGN KEY (`supply_request_id`) REFERENCES `supply_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_items`
--

LOCK TABLES `request_items` WRITE;
/*!40000 ALTER TABLE `request_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stop_score_histories`
--

DROP TABLE IF EXISTS `stop_score_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stop_score_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stop_id` bigint(20) unsigned NOT NULL,
  `scored_by` bigint(20) unsigned DEFAULT NULL,
  `scorer_type` varchar(50) NOT NULL,
  `scorer_role` varchar(50) DEFAULT NULL,
  `previous_priority_level` tinyint(3) unsigned DEFAULT NULL,
  `priority_level` tinyint(3) unsigned DEFAULT NULL,
  `note` text DEFAULT NULL,
  `scored_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stop_score_histories_scored_by_foreign` (`scored_by`),
  KEY `stop_score_histories_stop_scored_at_idx` (`stop_id`,`scored_at`),
  KEY `stop_score_histories_type_scored_at_idx` (`scorer_type`,`scored_at`),
  CONSTRAINT `stop_score_histories_scored_by_foreign` FOREIGN KEY (`scored_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stop_score_histories_stop_id_foreign` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stop_score_histories`
--

LOCK TABLES `stop_score_histories` WRITE;
/*!40000 ALTER TABLE `stop_score_histories` DISABLE KEYS */;
INSERT INTO `stop_score_histories` VALUES (1,31,6,'shift_leader','approver',NULL,0,'Ok đề xuất','2026-03-25 01:41:06','2026-03-25 01:41:06','2026-03-25 01:41:06'),(2,31,9,'safety_officer','tchc_manager',0,1,'Thẻ này không vấn đề gì cả','2026-03-25 01:41:49','2026-03-25 01:41:49','2026-03-25 01:41:49'),(3,32,9,'safety_officer','tchc_manager',NULL,1,'Ok đã check','2026-03-25 01:43:15','2026-03-25 01:43:15','2026-03-25 01:43:15'),(4,33,6,'shift_leader','approver',NULL,2,'Ok','2026-03-25 02:03:25','2026-03-25 02:03:25','2026-03-25 02:03:25'),(5,33,9,'safety_officer','tchc_manager',2,1,'Ok','2026-03-25 02:05:27','2026-03-25 02:05:27','2026-03-25 02:05:27'),(6,34,9,'safety_officer','tchc_manager',NULL,1,'CBAT đã kiểm tra đề nghị khắc phục sớm','2026-03-25 02:18:15','2026-03-25 02:18:15','2026-03-25 02:18:15'),(7,35,9,'safety_officer','tchc_manager',NULL,3,'Cần cải thiện','2026-03-25 02:20:37','2026-03-25 02:20:37','2026-03-25 02:20:37'),(8,36,6,'shift_leader','approver',NULL,1,'Ok chấm điểm an toàn','2026-03-25 02:23:14','2026-03-25 02:23:14','2026-03-25 02:23:14'),(9,36,6,'shift_leader','approver',1,0,'Chấm lại','2026-03-25 02:30:02','2026-03-25 02:30:02','2026-03-25 02:30:02'),(10,37,6,'shift_leader','approver',NULL,2,NULL,'2026-03-25 04:54:57','2026-03-25 04:54:57','2026-03-25 04:54:57'),(11,37,9,'safety_officer','tchc_manager',2,0,'Đã check','2026-03-25 04:55:53','2026-03-25 04:55:53','2026-03-25 04:55:53'),(12,36,9,'safety_officer','tchc_manager',0,1,'Mức độ nguy hiểm cần xử lý','2026-03-25 06:47:46','2026-03-25 06:47:46','2026-03-25 06:47:46'),(13,39,9,'safety_officer','tchc_manager',NULL,1,'chấm điểm','2026-04-02 03:03:36','2026-04-02 03:03:36','2026-04-02 03:03:36'),(14,40,9,'safety_officer','tchc_manager',NULL,NULL,'.','2026-04-02 03:07:58','2026-04-02 03:07:58','2026-04-02 03:07:58'),(15,40,9,'safety_officer','tchc_manager',NULL,1,NULL,'2026-04-02 04:12:20','2026-04-02 04:12:20','2026-04-02 04:12:20');
/*!40000 ALTER TABLE `stop_score_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stops`
--

DROP TABLE IF EXISTS `stops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stops` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `issue_category` varchar(255) NOT NULL COMMENT 'Loại vấn đề STOP',
  `priority_level` tinyint(4) DEFAULT NULL COMMENT 'Mức độ quan trọng: 0=Cao nhất, 1=Cao, 2=Trung bình, 3=Thấp. NULL=Chưa chấm',
  `priority_scored_by` bigint(20) unsigned DEFAULT NULL,
  `priority_scored_at` timestamp NULL DEFAULT NULL,
  `shift_leader_scored_by` bigint(20) unsigned DEFAULT NULL,
  `shift_leader_scored_at` timestamp NULL DEFAULT NULL,
  `shift_leader_priority_level` tinyint(3) unsigned DEFAULT NULL,
  `shift_leader_note` text DEFAULT NULL,
  `safety_officer_scored_by` bigint(20) unsigned DEFAULT NULL,
  `safety_officer_scored_at` timestamp NULL DEFAULT NULL,
  `safety_officer_priority_level` tinyint(3) unsigned DEFAULT NULL,
  `safety_officer_note` text DEFAULT NULL,
  `observer_name` varchar(255) NOT NULL COMMENT 'Tên người quan sát',
  `observer_phone` varchar(255) DEFAULT NULL COMMENT 'Ca/kíp người quan sát',
  `observation_date` date NOT NULL COMMENT 'Ngày quan sát',
  `observation_time` time DEFAULT NULL COMMENT 'Giờ quan sát',
  `location` varchar(255) NOT NULL COMMENT 'Vị trí',
  `equipment_name` varchar(255) DEFAULT NULL COMMENT 'Tên thiết bị',
  `issue_description` text NOT NULL COMMENT 'Vấn đề ghi nhận',
  `corrective_action` text NOT NULL COMMENT 'Hành động khắc phục',
  `status` enum('open','in-progress','completed') NOT NULL DEFAULT 'open' COMMENT 'Trạng thái',
  `completion_date` date DEFAULT NULL COMMENT 'Ngày hoàn thành',
  `notes` text DEFAULT NULL COMMENT 'Ghi chú',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stops_user_id_foreign` (`user_id`),
  KEY `stops_priority_scored_by_foreign` (`priority_scored_by`),
  KEY `stops_observer_phone_idx` (`observer_phone`),
  KEY `stops_issue_category_idx` (`issue_category`),
  KEY `stops_status_idx` (`status`),
  KEY `stops_observation_date_idx` (`observation_date`),
  KEY `stops_created_at_idx` (`created_at`),
  KEY `stops_priority_level_idx` (`priority_level`),
  KEY `stops_shift_date_idx` (`observer_phone`,`observation_date`),
  KEY `stops_shift_leader_scored_by_foreign` (`shift_leader_scored_by`),
  KEY `stops_safety_officer_scored_by_foreign` (`safety_officer_scored_by`),
  CONSTRAINT `stops_priority_scored_by_foreign` FOREIGN KEY (`priority_scored_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stops_safety_officer_scored_by_foreign` FOREIGN KEY (`safety_officer_scored_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stops_shift_leader_scored_by_foreign` FOREIGN KEY (`shift_leader_scored_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stops_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stops`
--

LOCK TABLES `stops` WRITE;
/*!40000 ALTER TABLE `stops` DISABLE KEYS */;
INSERT INTO `stops` VALUES (3,8,'moi_truong',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-05','13:12:00','jetty1',NULL,'Ô nhiễm môi trường','Khói bụi','completed','2026-03-11',NULL,'2026-03-05 06:12:47','2026-03-11 07:00:27'),(6,8,'dieu_kien_khong_an_toan',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-05','13:46:00','test',NULL,'test','test','completed','2026-03-11',NULL,'2026-03-05 06:46:50','2026-03-11 07:00:27'),(7,8,'moi_truong',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-05','13:49:00','Môi trường',NULL,'test','test','completed','2026-03-11',NULL,'2026-03-05 06:50:00','2026-03-11 07:00:27'),(8,8,'moi_truong',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-06','07:58:00','jetty2','Jetty2','Jetty2','Jetty2','completed','2026-03-11',NULL,'2026-03-06 00:58:28','2026-03-11 07:00:27'),(9,8,'quy_trinh_noi_quy',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-06','08:05:00','Test TF','TF','test TF','Test TF','completed','2026-03-11',NULL,'2026-03-06 01:05:54','2026-03-11 07:00:27'),(10,8,'van_de_khac',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-06','09:32:00','Test TF','test','test','Thank Triều;\r\nKg chị Hậu/c Quyên: các quy trình sau khi ban hành trên qlcv,  lưu lên phapche để theo dõi/tra cứu hiệu lực hệ thống các quy trình của Chi nhánh nhé.','completed','2026-03-11',NULL,'2026-03-06 02:32:46','2026-03-11 07:00:27'),(11,8,'moi_truong',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nguyễn Văn Tráng','VH01','2026-03-06','09:38:00','Văn phòng',NULL,'Ô nhiễm môi trường.','Khói bụi','completed','2026-03-11','ưew','2026-03-06 02:36:54','2026-03-11 07:00:27'),(12,10,'bao_ho_lao_dong',1,5,'2026-03-11 07:00:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-08','17:24:00','TankFarm','Chúng tôi thấy thiếu bộ đồ bảo hộ lao động...','Chúng tôi thấy thiếu bộ đồ bảo hộ lao động cho CNCNV Chúng tôi thấy thiếu bộ đồ bảo hộ lao động cho CNCNV','Cần trang bị thêm để sử dụng công Chúng tôi thấy thiếu bộ đồ bảo hộ lao động cho CNCNV','completed','2026-03-11',NULL,'2026-03-08 10:25:16','2026-03-11 07:00:27'),(13,10,'moi_truong',2,9,'2026-03-09 07:30:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-08','17:35:00','Văn phòng',NULL,'Mô tả chi tiết vấn đề ghi nhận nhưu thế nào','đề xuất phương án hành động khắc phục','completed','2026-03-09',NULL,'2026-03-08 10:36:09','2026-03-09 07:30:40'),(14,10,'moi_truong',1,9,'2026-03-09 07:04:13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-09','10:11:00','TankFarm',NULL,'Mô tả chi tiết vấn đề an toàn, sức khỏe môi trường làm việc','Đề xuất phương án giải phép để giải quyết mô tả chi tiết vấn đề an toàn, sức khỏe môi trường làm việc','completed','2026-03-09',NULL,'2026-03-09 03:11:56','2026-03-09 07:04:13'),(17,10,'moi_truong',2,9,'2026-03-09 07:03:34',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-09','13:57:00','PDK',NULL,'Ô nhiễm tiếng ồn','Hạn chế tiếng ồn','completed','2026-03-09',NULL,'2026-03-09 06:58:15','2026-03-09 07:03:34'),(18,9,'moi_truong',0,9,'2026-03-09 07:10:46',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-09','14:10:00','HTSX',NULL,'HTSX','HTSX','completed','2026-03-09',NULL,'2026-03-09 07:10:36','2026-03-09 07:10:46'),(20,9,'moi_truong',1,9,'2026-03-09 07:41:34',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-09','14:41:00','HTSX',NULL,'HTSX','HTSX','completed','2026-03-09',NULL,'2026-03-09 07:41:18','2026-03-09 07:41:34'),(21,10,'moi_truong',0,9,'2026-03-11 07:10:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-11','14:01:00','test tích chọn','chọn','chọn','chọn','completed','2026-03-11',NULL,'2026-03-11 07:02:08','2026-03-11 07:10:40'),(22,9,'quy_trinh_noi_quy',1,9,'2026-03-22 04:11:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-22','11:08:00','HTSX','Không khí','Ô nhiễm không khí','Đề xuất vệ sinh khu vực làm việc','completed','2026-03-22',NULL,'2026-03-22 04:08:44','2026-03-22 04:11:10'),(23,9,'moi_truong',1,9,'2026-03-22 04:10:47',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-22','11:09:00','HTSX Văn phòng',NULL,'Ô nhiễm môi trường','Vệ sinh khu vực','completed','2026-03-22',NULL,'2026-03-22 04:09:35','2026-03-22 04:10:47'),(24,10,'dung_cu_thiet_bi',0,9,'2026-03-24 09:16:42',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-22','11:11:00','Jeetyy',NULL,'Tàu không ổn định','Đề xuất tàu vô khu vực ổn định','completed','2026-03-24',NULL,'2026-03-22 04:12:12','2026-03-24 09:16:42'),(25,10,'bao_ho_lao_dong',0,9,'2026-03-24 09:37:43',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-22','11:18:00','PĐK',NULL,'Chưa có đồ bảo hộ lao động','Cung cấp đồ bảo hộ lao động','completed','2026-03-24',NULL,'2026-03-22 04:19:07','2026-03-24 09:37:43'),(26,9,'ro_ri_hc',0,9,'2026-03-24 09:40:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-24','16:14:00','Test',NULL,'test','test','completed','2026-03-24',NULL,'2026-03-24 09:14:27','2026-03-24 09:40:27'),(27,9,'bao_ho_lao_dong',0,9,'2026-03-24 09:45:47',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-24','16:44:00','test',NULL,'test','test','completed','2026-03-24',NULL,'2026-03-24 09:44:55','2026-03-24 09:45:47'),(28,9,'dung_cu_thiet_bi',0,9,'2026-03-24 09:49:16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-24','16:48:00','test',NULL,'Ok check kiểm tra','kiểm tra','completed','2026-03-24',NULL,'2026-03-24 09:48:24','2026-03-24 09:49:16'),(29,9,'quy_trinh_noi_quy',0,9,'2026-03-24 14:30:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Phạm Đăng Khoa','HTSX','2026-03-24','21:02:00','test',NULL,'test','test','completed','2026-03-24',NULL,'2026-03-24 14:02:49','2026-03-24 14:30:21'),(30,10,'dung_cu_thiet_bi',0,9,'2026-03-24 14:31:50',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Lê Cảnh Bình','VH04','2026-03-24','21:30:00','Văn phòng',NULL,'Mô tả','mô tả','completed','2026-03-24',NULL,'2026-03-24 14:31:09','2026-03-24 14:31:51'),(31,10,'bao_ho_lao_dong',1,9,'2026-03-25 01:41:49',6,'2026-03-25 01:41:06',0,'Ok đề xuất',9,'2026-03-25 01:41:49',1,'Thẻ này không vấn đề gì cả','Lê Cảnh Bình','VH04','2026-03-25','08:40:00','TF',NULL,'Chưa được phát','Bổ sung đồ bảo hộ','completed','2026-03-25',NULL,'2026-03-25 01:40:43','2026-03-25 01:41:49'),(32,10,'bao_ho_lao_dong',1,9,'2026-03-25 01:43:15',NULL,NULL,NULL,NULL,9,'2026-03-25 01:43:15',1,'Ok đã check','Lê Cảnh Bình','VH04','2026-03-25','08:42:00','jetty1',NULL,'Test','Check kiểm tra','completed','2026-03-25',NULL,'2026-03-25 01:42:55','2026-03-25 01:43:15'),(33,10,'tu_the_hanh_dong',1,9,'2026-03-25 02:05:27',6,'2026-03-25 02:03:25',2,'Ok',9,'2026-03-25 02:05:27',1,'Ok','Lê Cảnh Bình','VH04','2026-03-25','09:02:00','PDĐK',NULL,'Sai tư thế','Sai tư thế','completed','2026-03-25',NULL,'2026-03-25 02:02:52','2026-03-25 02:05:27'),(34,9,'moi_truong',1,9,'2026-03-25 02:18:15',NULL,NULL,NULL,NULL,9,'2026-03-25 02:18:15',1,'CBAT đã kiểm tra đề nghị khắc phục sớm','Phạm Đăng Khoa','HTSX','2026-03-25','09:11:00','HTSX',NULL,'ô nhiễm môi trường','Tiếng ồn hạn chế','completed','2026-03-25',NULL,'2026-03-25 02:11:36','2026-03-25 02:18:15'),(35,9,'bieu_duong_an_toan',3,9,'2026-03-25 02:20:37',NULL,NULL,NULL,NULL,9,'2026-03-25 02:20:37',3,'Cần cải thiện','Phạm Đăng Khoa','HTSX','2026-03-25','09:20:00','HTSX',NULL,'Test','Test','completed','2026-03-25',NULL,'2026-03-25 02:20:23','2026-03-25 02:20:37'),(36,9,'bieu_duong_an_toan',1,9,'2026-03-25 06:47:46',6,'2026-03-25 02:30:02',0,'Chấm lại',9,'2026-03-25 06:47:46',1,'Mức độ nguy hiểm cần xử lý','Phạm Đăng Khoa','HTSX','2026-03-25','09:22:00','HTSX',NULL,'Test','test','completed','2026-03-25',NULL,'2026-03-25 02:22:46','2026-03-25 06:47:46'),(37,11,'ro_ri_hc',0,9,'2026-03-25 04:55:53',6,'2026-03-25 04:54:57',2,NULL,9,'2026-03-25 04:55:53',0,'Đã check','Thái Việt Hùng','VH01','2026-03-25','11:54:00','Trạm nạp',NULL,'Rò rỉ','Hạn chế','completed','2026-03-25',NULL,'2026-03-25 04:54:31','2026-03-25 04:55:53'),(39,9,'dung_cu_thiet_bi',1,9,'2026-04-02 03:03:36',NULL,NULL,NULL,NULL,9,'2026-04-02 03:03:36',1,'chấm điểm','Phạm Đăng Khoa','HTSX','2026-04-02','10:01:00','test',NULL,'test','test','completed','2026-04-02',NULL,'2026-04-02 03:01:51','2026-04-02 03:03:36'),(40,9,'quy_trinh_noi_quy',1,9,'2026-04-02 04:12:20',NULL,NULL,NULL,NULL,9,'2026-04-02 04:12:20',1,NULL,'Phạm Đăng Khoa','HTSX','2026-04-02','10:06:00','test',NULL,'test','test','completed','2026-04-02',NULL,'2026-04-02 03:06:16','2026-04-02 04:12:20');
/*!40000 ALTER TABLE `stops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supply_requests`
--

DROP TABLE IF EXISTS `supply_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supply_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `request_code` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `requester_name` varchar(255) NOT NULL,
  `requester_email` varchar(255) NOT NULL,
  `requester_position` varchar(255) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `requester_department` varchar(255) NOT NULL,
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `needed_date` date DEFAULT NULL,
  `period` int(11) DEFAULT NULL COMMENT 'Kỳ (tháng 1-12)',
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `forwarded_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `tchc_checked_at` timestamp NULL DEFAULT NULL,
  `tchc_approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `tchc_checker_id` bigint(20) unsigned DEFAULT NULL,
  `tchc_manager_id` bigint(20) unsigned DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `tchc_check_notes` text DEFAULT NULL,
  `tchc_approval_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supply_requests_request_code_unique` (`request_code`),
  KEY `supply_requests_user_id_foreign` (`user_id`),
  KEY `supply_requests_approved_by_foreign` (`approved_by`),
  KEY `supply_requests_tchc_checker_id_foreign` (`tchc_checker_id`),
  KEY `supply_requests_tchc_manager_id_foreign` (`tchc_manager_id`),
  KEY `supply_requests_status_approved_at_index` (`status`,`approved_at`),
  KEY `supply_requests_requester_department_status_index` (`requester_department`,`status`),
  KEY `supply_requests_created_at_index` (`created_at`),
  KEY `supply_requests_approved_at_index` (`approved_at`),
  KEY `supply_requests_tchc_checked_at_index` (`tchc_checked_at`),
  CONSTRAINT `supply_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `supply_requests_tchc_checker_id_foreign` FOREIGN KEY (`tchc_checker_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `supply_requests_tchc_manager_id_foreign` FOREIGN KEY (`tchc_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `supply_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supply_requests`
--

LOCK TABLES `supply_requests` WRITE;
/*!40000 ALTER TABLE `supply_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `supply_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `role` enum('employee','approver','admin','tchc_checker','tchc_manager') DEFAULT 'employee',
  `is_tchc_checker` tinyint(1) NOT NULL DEFAULT 0,
  `is_tchc_manager` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(255) DEFAULT NULL,
  `zalo_user_id` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_index` (`department`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'admin','Administrator','admin@pvgas.com.vn',NULL,'$2y$10$OQaVPYRJG0GqgTtp42YEM.3xIpgrWqJlSx5/4Iffbl8MSy6ONSkfi','IT','Administrator','admin',0,0,'HTSX',NULL,1,'Wy8evAduJ8zWwa0jvWhYoUUwipbg2eZDKfawcTpSvuOUScJzhL0td7QY9aA0','2026-02-02 09:06:23','2026-03-25 06:56:16'),(6,'Phạm Thái Sơn',NULL,'son.pt3@pvgas.com.vn',NULL,'$2y$10$uPmDP.8rs8sgEKOAn8ZMiOW/ANGzAE55uBfKPWZufhehc1tslGOK6','KCTV','Trưởng ca KCTV','approver',0,0,'VH01',NULL,1,'7QSZaDyMUjhARhLQaHSt7ndvgeOrBkQ380qT27IKTktLN6HVrx7xbnGrOYul','2026-02-02 10:04:47','2026-03-05 06:14:44'),(7,'Triệu Thị Ánh Ngọc',NULL,'ngoc.tta@pvgas.com.vn',NULL,'$2y$10$dNCa4WedywwudR8YbE0EGO/j1vf8WnCr0xQ10vAztAgd6R/xV0YZe','KCTV','Tổ trưởng KCTV','tchc_checker',0,0,'HTSX',NULL,1,'QVdWqgZK40fiMEgO8eOSaJ22bD0TYjL1BdvsTYEa7EMp3MAMJeeNG3x5ZdLX','2026-02-02 10:09:54','2026-03-08 15:33:42'),(8,'Nguyễn Văn Tráng',NULL,'trang.nv@pvgas.com.vn',NULL,'$2y$10$w0PecSrtHM7yoAsSj0X1Tu/wteeEKCoATkLcs8C5AwF14CZ/ShUom','KCTV','Công nhân','employee',0,0,'VH01',NULL,1,'wmArXWSHnTmmvzzHOYkHpq34DjFTg4ByUiLMtduc8Xf5nwjN7DbXVy5svEuv','2026-03-04 04:01:37','2026-03-04 04:30:47'),(9,'Phạm Đăng Khoa',NULL,'khoa.pd@pvgas.com.vn',NULL,'$2y$10$IZoMFSEnzcArjuwALpKKKeQdz93zNSFlE04/6n8Kr0LmVRidic9fO','KCTV','Chuyên Viên AT','tchc_manager',0,0,'HTSX',NULL,1,'kS9pRlEdvYMwt9ov0wnxj335nRfnS1bflFJ8naTohk6uQXodkQqixxMv0U1R','2026-03-05 08:25:40','2026-04-02 07:09:01'),(10,'Lê Cảnh Bình',NULL,'binh.lc@pvgas.com.vn',NULL,'$2y$10$H/do4TUvF1oGAXjZFys4O.G/Zf2eF.peAv0DsHQFsPZMeenzsxBvu','KCTV','Công nhân VH','employee',0,0,'VH04',NULL,1,'omACxkqlNTqOVIYFeo3WVZFldY4atv8aRjjdtqa0kGCOfAP0DyZF1LZq8epI','2026-03-08 10:23:48','2026-03-08 10:23:48'),(11,'Thái Việt Hùng',NULL,'hung.tv2@pvgas.com.vn',NULL,'$2y$10$Joa6MlxnTJgUvp4FAqYUwuhMzU8GMJ.u3U3gTIT2leB9wT4wa527S','KCTV','Công nhân VH','employee',0,0,'VH01',NULL,1,NULL,'2026-03-25 04:38:15','2026-03-25 04:38:15'),(12,'Nguyễn Hữu Tuấn Anh',NULL,'anh.nht@pvgas.com.vn',NULL,'$2y$10$1REdMG0PAGU7METflhTH..ix7Q3St5H6s6TBsS1tphl1De/u/dNqW','KCTV','Dự phòng Đốc công','employee',0,0,'HTSX',NULL,1,NULL,'2026-03-25 06:58:29','2026-03-25 06:58:29'),(13,'Nguyễn Văn Tuân','Nguyễn Văn Tuân','tuan.nv@pvgas.com.vn',NULL,'$2y$10$m/ylRwlrPMEti8DybrsttuFnhXFJIjS7c3RcZyQEp7Vza5DW23IPO','KCTV','Trưởng ca','approver',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(14,'Phạm Thanh Quỳnh','Phạm Thanh Quỳnh','quynh.pt@pvgas.com.vn',NULL,'$2y$10$msZ0lwG514gVkdmoY5ZrROrL6cnKZHLAc18s4ILrrii3jtsr2SD1m','KCTV','DP Trưởng ca','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(15,'Nguyễn Trọng Tuấn','Nguyễn Trọng Tuấn','tuan.nt2@pvgas.com.vn',NULL,'$2y$10$yEK1Wc.8w1LryXOxKvQbUumZ6fGGdWOXbzhJA.nsMDSHN806tzdou','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(16,'Giang Thanh Bình','Giang Thanh Bình','binh.gt@pvgas.com.vn',NULL,'$2y$10$9vUFE1wxDrZrTptaNQ1WjurhzozWeotPcCXiaCBm1YbsKBlk8m/WG','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(17,'Trần Đình Tuấn','Trần Đình Tuấn','tuan.td@pvgas.com.vn',NULL,'$2y$10$1G8to6YVZ0iQ.TXPxsWFa.kwFUU8Gxyc3RGSqUUNBJmPxq21uLR7C','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(18,'Nguyễn Thanh Long','Nguyễn Thanh Long','long.nt2@pvgas.com.vn',NULL,'$2y$10$GcC5nt/bSDP/evxxdTMrKOPrZVUClJaKKdiRj1MVXWOu8ssUlMDFW','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(19,'Lâm Tiến Đức','Lâm Tiến Đức','duc.lt@pvgas.com.vn',NULL,'$2y$10$4TofWhaTlMTY2K9fWuSS1.8J0WLe7sIgYpw9Thr09xPxuKiJeBbA.','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(20,'Trương Đình Huy','Trương Đình Huy','huy.td@pvgas.com.vn',NULL,'$2y$10$104iU9lvfhqcSyqEX2dqg.PJeTUg6oEc7p8UieiZUxTgtbHVbPTAm','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:13','2026-03-27 07:19:13'),(21,'Nguyễn Tấn Đạt','Nguyễn Tấn Đạt','dat.nt@pvgas.com.vn',NULL,'$2y$10$eYFQ8HmJr9TMakINBCfQTOqrKuzT0ZhV1EM/vM9xQYbBp9NNEwGVS','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(22,'Hoàng Văn Hùng','Hoàng Văn Hùng','hung.hv@pvgas.com.vn',NULL,'$2y$10$Pfv0Is6SAY/DC/w34.CaieZ.wgNLcrDvloRGqOmVp9o.rUkFo2eCi','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(23,'Nguyễn Đức Hiếu','Nguyễn Đức Hiếu','hieu.nd2@pvgas.com.vn',NULL,'$2y$10$wBudBSvDe0VT2fQ5HPx5jOwvhZNlosAI3Z2fE97Q4BuOvqFaMO1ju','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(24,'Hoàng Anh Phương','Hoàng Anh Phương','phuong.hd@pvgas.com.vn',NULL,'$2y$10$/FTEzTbZBxu4pwmsLKa0XOl1M4Jl4ZAM090Cjt0Agb.mP3tWCpvyy','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(25,'Bùi Trung Hiếu','Bùi Trung Hiếu','hieu.bt@pvgas.com.vn',NULL,'$2y$10$BgKKUzOeksBsInEpnYAWp.SQbZ/kReewUv9/mqU4okudaI20zDjNq','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(26,'Phạm Văn Chức','Phạm Văn Chức','chuc.pv@pvgas.com.vn',NULL,'$2y$10$g6hCGgNbiZyJSKpQCfReyOGxTRwDnw5yI15U2CxK98ekMiuJNyZpi','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(27,'Bùi Long Giang','Bùi Long Giang','giang.bl@pvgas.com.vn',NULL,'$2y$10$O6rxaxl0LtLzfjFaCQKGU.QrK7KzVDOrAwgd8AdQsiXbQ5OL/5Ms2','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(28,'Nguyễn Văn Trịnh','Nguyễn Văn Trịnh','trinh.nv@pvgas.com.vn',NULL,'$2y$10$Zw7.m5QZ0sBoeVckLZkjGuiorxBuZJK3M/corIkHEtHo/u3uBQz8.','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(29,'Trần Văn Thuật','Trần Văn Thuật','thuat.nv@pvgas.com.vn',NULL,'$2y$10$zZSaFawcYjWQzUJHfHRpDuI2E1JB7KPnaKEEF4eX3v4tyE1qn4eDK','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(30,'Phạm Đình Ngân','Phạm Đình Ngân','ngan.pd@pvgas.com.vn',NULL,'$2y$10$B8ImF.qwrGRjp2JF0EVXSOLDBzN4jSRYA694B6UvJ0pmuZyuNlJcu','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(31,'Bùi Văn Cơ','Bùi Văn Cơ','co.bv@pvgas.com.vn',NULL,'$2y$10$hJ2rkfxGR3SYvqaPBecL6.KQ504Zyhe2W24MTr4zzJkatDULU9n06','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(32,'Hồ Hữu Tiến','Hồ Hữu Tiến','tien.hh@pvgas.com.vn',NULL,'$2y$10$tyAiSSk4uqHk465ergY53eotQnPz4ohm20B.HScLCdiplL8EWgG2G','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(33,'Nguyễn Đình Trường','Nguyễn Đình Trường','truong.nd@pvgas.com.vn',NULL,'$2y$10$ZzfkFmysga1nQgmwqdhNfepuWNxyQAYKOPgUIZP7ddTrsm8aMkQkG','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(34,'Nguyễn Công Hoàng','Nguyễn Công Hoàng','hoang.nc@pvgas.com.vn',NULL,'$2y$10$z9IZbLkRl.njmmzUOIAZpe14R4TgogqVUprqEuSIwkuMwhIl5NXHW','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(35,'Nguyễn Tấn Thưởng','Nguyễn Tấn Thưởng','thuong.nt@pvgas.com.vn',NULL,'$2y$10$isvY.HjgbrR7nOIzKNjnb.goFE88kVulRWjXgazANmHQNA.aqTb3.','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(36,'Nguyễn Ngọc Tú','Nguyễn Ngọc Tú','tu.nn@pvgas.com.vn',NULL,'$2y$10$0OP3HXDT61oXWexyydrbO.EkdWVmRNjmurIgBdxciiEnTQgNaSRhG','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:14','2026-03-27 07:19:14'),(37,'Lê Ngọc Hưng','Lê Ngọc Hưng','hung.ln@pvgas.com.vn',NULL,'$2y$10$5.LFDYF.7A1TDEQKD6yDcu32KkkFt/OseW3mH2qqNRjmA0IV3NQ16','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:15','2026-03-27 07:19:15'),(38,'Nguyễn Quốc Việt-PTT','Nguyễn Quốc Việt-PTT','viet.nq@pvgas.com.vn',NULL,'$2y$10$Xc5PBsxQwhL4T184WltFNecfJprnbbse.pSdqL7IaY/4u.3XVMuaO','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:15','2026-03-27 07:19:15'),(39,'Trần Hữu Sáu-PTT-PTT','Trần Hữu Sáu-PTT-PTT','sau.th@pvgas.com.vn',NULL,'$2y$10$.yTc7tXh0sSnwKhLbGSCpuMmXSlalfVbbVfueG8zJXoQSqgP7tl.u','KCTV','Công nhân','employee',0,0,'VH02',NULL,1,NULL,'2026-03-27 07:19:15','2026-03-27 07:19:15');
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

-- Dump completed on 2026-04-05 14:11:00
