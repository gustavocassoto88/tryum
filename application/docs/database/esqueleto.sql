-- ------------------------------------------------------
-- Server version	5.5.13

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
-- Table structure for table `erros`
--

DROP TABLE IF EXISTS `erros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `erros` (
  `iderro` int(11) NOT NULL AUTO_INCREMENT,
  `data_execucao` datetime NOT NULL,
  `mensagem` text NOT NULL,
  `parametros` text NOT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `trace` text,
  PRIMARY KEY (`iderro`),
  KEY `fk_erros_usuarios1` (`idusuario`),
  CONSTRAINT `fk_erros_usuarios1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `erros`
--

LOCK TABLES `erros` WRITE;
/*!40000 ALTER TABLE `erros` DISABLE KEYS */;
INSERT INTO `erros` VALUES (1,'2012-04-05 09:00:30','Invalid controller specified (teste)','{\"module\":\"user\",\"controller\":\"teste\",\"action\":\"list\"}',3,'#0 /var/www/esqueleto/library/Zend/Controller/Front.php(954): Zend_Controller_Dispatcher_Standard->dispatch(Object(Zend_Controller_Request_Http), Object(Zend_Controller_Response_Http))\n#1 /var/www/esqueleto/library/Zend/Application/Bootstrap/Bootstrap.php(97): Zend_Controller_Front->dispatch()\n#2 /var/www/esqueleto/library/Zend/Application.php(366): Zend_Application_Bootstrap_Bootstrap->run()\n#3 /var/www/esqueleto/index.php(37): Zend_Application->run()\n#4 {main}'),(2,'2012-04-05 09:00:32','Invalid controller specified (test)','{\"module\":\"user\",\"controller\":\"test\",\"action\":\"list\"}',3,'#0 /var/www/esqueleto/library/Zend/Controller/Front.php(954): Zend_Controller_Dispatcher_Standard->dispatch(Object(Zend_Controller_Request_Http), Object(Zend_Controller_Response_Http))\n#1 /var/www/esqueleto/library/Zend/Application/Bootstrap/Bootstrap.php(97): Zend_Controller_Front->dispatch()\n#2 /var/www/esqueleto/library/Zend/Application.php(366): Zend_Application_Bootstrap_Bootstrap->run()\n#3 /var/www/esqueleto/index.php(37): Zend_Application->run()\n#4 {main}'),(3,'2012-04-05 09:00:36','Invalid controller specified (teste)','{\"module\":\"user\",\"controller\":\"teste\",\"action\":\"list\"}',3,'#0 /var/www/esqueleto/library/Zend/Controller/Front.php(954): Zend_Controller_Dispatcher_Standard->dispatch(Object(Zend_Controller_Request_Http), Object(Zend_Controller_Response_Http))\n#1 /var/www/esqueleto/library/Zend/Application/Bootstrap/Bootstrap.php(97): Zend_Controller_Front->dispatch()\n#2 /var/www/esqueleto/library/Zend/Application.php(366): Zend_Application_Bootstrap_Bootstrap->run()\n#3 /var/www/esqueleto/index.php(37): Zend_Application->run()\n#4 {main}'),(4,'2012-04-05 09:00:37','Invalid controller specified (teste)','{\"module\":\"user\",\"controller\":\"teste\",\"action\":\"list\"}',3,'#0 /var/www/esqueleto/library/Zend/Controller/Front.php(954): Zend_Controller_Dispatcher_Standard->dispatch(Object(Zend_Controller_Request_Http), Object(Zend_Controller_Response_Http))\n#1 /var/www/esqueleto/library/Zend/Application/Bootstrap/Bootstrap.php(97): Zend_Controller_Front->dispatch()\n#2 /var/www/esqueleto/library/Zend/Application.php(366): Zend_Application_Bootstrap_Bootstrap->run()\n#3 /var/www/esqueleto/index.php(37): Zend_Application->run()\n#4 {main}');
/*!40000 ALTER TABLE `erros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `idlog` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `tabela` varchar(50) NOT NULL,
  `json_data` longblob NOT NULL,
  `acao_executada` varchar(20) NOT NULL,
  `data_execucao` datetime NOT NULL,
  PRIMARY KEY (`idlog`),
  KEY `fk_logs_usuarios1` (`idusuario`),
  CONSTRAINT `fk_logs_usuarios1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,3,'testes','{\"idteste\":1,\"data\":\"2012-03-22\",\"path\":\"b41285022e83bf115d911e858addad02.png\",\"texto\":\"asdasdasd\\r\\n\\r\\n[b]asdasd[\\/b]\\r\\n[i]asdasd[\\/i]\\r\\n[u]asdasd[\\/u]\\r\\n[h2]asdasd[\\/h2]\\r\\n[h3]asdasd[\\/h3]\\r\\n[h4]asdasd[\\/h4]\"}','UPDATE','2012-04-05 10:19:28');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_categorias`
--

DROP TABLE IF EXISTS `menu_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_categorias` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT COMMENT '\n',
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_categorias`
--

LOCK TABLES `menu_categorias` WRITE;
/*!40000 ALTER TABLE `menu_categorias` DISABLE KEYS */;
INSERT INTO `menu_categorias` VALUES (1,'useristração');
/*!40000 ALTER TABLE `menu_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_itens`
--

DROP TABLE IF EXISTS `menu_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_itens` (
  `iditem` int(11) NOT NULL AUTO_INCREMENT,
  `idperfil` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `controlador` varchar(50) NOT NULL,
  `acao` varchar(50) NOT NULL,
  PRIMARY KEY (`iditem`),
  KEY `fk_menu_itens_menu_categorias1` (`idcategoria`),
  KEY `fk_menu_itens_perfis1` (`idperfil`),
  CONSTRAINT `fk_menu_itens_menu_categorias1` FOREIGN KEY (`idcategoria`) REFERENCES `menu_categorias` (`idcategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_itens_perfis1` FOREIGN KEY (`idperfil`) REFERENCES `perfis` (`idperfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_itens`
--

LOCK TABLES `menu_itens` WRITE;
/*!40000 ALTER TABLE `menu_itens` DISABLE KEYS */;
INSERT INTO `menu_itens` VALUES (15,99,1,'Categorias de Menus','user','menuscategorias','list'),(16,99,1,'Itens de Menu','user','menusitens','list'),(17,2,1,'Usuários','user','usuarios','list'),(18,2,1,'Trocar Senha','user','usuarios','trocarsenha');
/*!40000 ALTER TABLE `menu_itens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfis`
--

DROP TABLE IF EXISTS `perfis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfis` (
  `idperfil` int(11) NOT NULL,
  `descricao` varchar(55) NOT NULL,
  PRIMARY KEY (`idperfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfis`
--

LOCK TABLES `perfis` WRITE;
/*!40000 ALTER TABLE `perfis` DISABLE KEYS */;
INSERT INTO `perfis` VALUES (2,'Usuário'),(99,'useristrador');
/*!40000 ALTER TABLE `perfis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testes`
--

DROP TABLE IF EXISTS `testes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testes` (
  `idteste` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `path` varchar(45) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`idteste`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testes`
--

LOCK TABLES `testes` WRITE;
/*!40000 ALTER TABLE `testes` DISABLE KEYS */;
INSERT INTO `testes` VALUES (1,'2012-03-22','b41285022e83bf115d911e858addad02.png','asdasdasd\r\n\r\n[b]asdasd[/b]\r\n[i]asdasd[/i]\r\n[u]asdasd[/u]\r\n[h2]asdasd[/h2]\r\n[h3]asdasd[/h3]\r\n[h4]asdasd[/h4]');
/*!40000 ALTER TABLE `testes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `idperfil` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(45) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `chave` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  KEY `fk_usuarios_perfis1` (`idperfil`),
  CONSTRAINT `fk_usuarios_perfis1` FOREIGN KEY (`idperfil`) REFERENCES `perfis` (`idperfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (3,99,'useristrador Tryum','user@tryum.com.br','user','89794b621a313bb59eed0d9f0f4e8205','');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-04-27 17:07:10
