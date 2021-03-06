-- MySQL Script generated by MySQL Workbench
-- Sun May 30 10:50:47 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema dtbutil
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema dtbutil
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dtbutil` DEFAULT CHARACTER SET latin1 ;
USE `dtbutil` ;

-- -----------------------------------------------------
-- Table `dtbutil`.`mensagem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`mensagem` (
  `idMensagem` INT(11) NOT NULL AUTO_INCREMENT,
  `mensagem` VARCHAR(2000) NOT NULL,
  `datamensagem` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USUARIO_idUSUARIO` INT(11) NOT NULL,
  `visualizado` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idMensagem`))
ENGINE = InnoDB
AUTO_INCREMENT = 87
DEFAULT CHARACTER SET = utf8
COMMENT = 'não visualizado 0 | visualizado 1';


-- -----------------------------------------------------
-- Table `dtbutil`.`notification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`notification` (
  `idnotification` INT(11) NOT NULL AUTO_INCREMENT,
  `message` VARCHAR(45) NULL DEFAULT NULL,
  `DATANOT` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_idusuario` BIGINT(20) NOT NULL,
  `ip` VARCHAR(50) NULL DEFAULT NULL,
  `cont` INT(11) NULL DEFAULT '0',
  PRIMARY KEY (`idnotification`),
  INDEX `usuario_idusuario` (`usuario_idusuario` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 989
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dtbutil`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`usuario` (
  `idUsuario` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `LOGIN` VARCHAR(45) NOT NULL,
  `NOME` VARCHAR(100) NOT NULL,
  `EMAIL` VARCHAR(60) NOT NULL,
  `DATACADASTRO` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TELEFONE_UM` VARCHAR(40) NULL DEFAULT NULL,
  `BIOGRAFIA` VARCHAR(100) NULL DEFAULT NULL,
  `CIDADE` VARCHAR(45) NULL DEFAULT NULL,
  `PAIS` VARCHAR(45) NULL DEFAULT NULL,
  `INSTAGRAM` VARCHAR(100) NULL DEFAULT NULL,
  `SITE` VARCHAR(100) NULL DEFAULT NULL,
  `STATUS_idSTATUS` INT(11) NOT NULL,
  `TIPOUSUARIO_idTIPOUSUARIO` INT(11) NOT NULL,
  PRIMARY KEY (`idUsuario`, `LOGIN`),
  INDEX `fk_USUARIO_TIPOUSUARIO1_idx` (`TIPOUSUARIO_idTIPOUSUARIO` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 88
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dtbutil`.`senha`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`senha` (
  `idSENHA` INT(11) NOT NULL AUTO_INCREMENT,
  `SENHA` VARCHAR(45) NULL DEFAULT NULL,
  `TIPO` CHAR(1) NULL DEFAULT NULL COMMENT '1 - SENHA ACESSO\\n2 - SENHA FINANCEIRA',
  `TOKEN` VARCHAR(45) NULL DEFAULT NULL,
  `SENHAALTERAR` VARCHAR(45) NULL DEFAULT NULL,
  `DATACADASTRO` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_idUsuario` BIGINT(20) NOT NULL,
  PRIMARY KEY (`idSENHA`, `usuario_idUsuario`),
  INDEX `fk_senha_usuario1_idx` (`usuario_idUsuario` ASC) VISIBLE,
  CONSTRAINT `fk_senha_usuario1`
    FOREIGN KEY (`usuario_idUsuario`)
    REFERENCES `dtbutil`.`usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 140
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dtbutil`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`status` (
  `idSTATUS` INT(11) NOT NULL,
  `DESCRICAO` VARCHAR(45) NOT NULL,
  `DATACADASTRO` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idSTATUS`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dtbutil`.`suporte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`suporte` (
  `idmensagem` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(30) NOT NULL,
  `email` VARCHAR(40) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `mensagem` VARCHAR(500) NOT NULL,
  `dataabertura` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atendido` INT(11) NOT NULL,
  `resolvido` INT(11) NOT NULL,
  `tipo` INT(11) NOT NULL,
  `USUARIO_idUSUARIO` BIGINT(20) NOT NULL,
  PRIMARY KEY (`idmensagem`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dtbutil`.`systemkey`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`systemkey` (
  `idkey` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NULL DEFAULT NULL,
  `chave` VARCHAR(300) NULL DEFAULT NULL,
  `datacadastro` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `status_idstatus` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`idkey`),
  INDEX `status_idstatus_idx` (`status_idstatus` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dtbutil`.`tipousuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`tipousuario` (
  `idTIPOUSUARIO` INT(11) NOT NULL AUTO_INCREMENT,
  `TIPOUSUARIODESCRICAO` VARCHAR(45) NOT NULL,
  `OBSERVACAO` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`idTIPOUSUARIO`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dtbutil`.`nivelcriticidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`nivelcriticidade` (
  `idnivelcriticidade` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  `datacadastro` VARCHAR(45) NULL,
  `status_idSTATUS` INT(11) NOT NULL,
  PRIMARY KEY (`idnivelcriticidade`, `status_idSTATUS`),
  INDEX `fk_nivelcriticidade_status1_idx` (`status_idSTATUS` ASC) VISIBLE,
  CONSTRAINT `fk_nivelcriticidade_status1`
    FOREIGN KEY (`status_idSTATUS`)
    REFERENCES `dtbutil`.`status` (`idSTATUS`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dtbutil`.`chamado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dtbutil`.`chamado` (
  `idchamado` BIGINT NOT NULL,
  `usuarioafetadonome` VARCHAR(45) NOT NULL,
  `usuarioafetadocpf` VARCHAR(45) NOT NULL,
  `usuarioafetadoemail` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(1000) NOT NULL,
  `datainicioproblema` DATETIME NOT NULL,
  `dataconclusao` DATETIME NULL,
  `datacancelamento` DATETIME NULL,
  `analistaatendendo` INT NULL,
  `descricaoconclusao` VARCHAR(1000) NULL,
  `nivelcriticidade_idnivelcriticidade` INT NOT NULL,
  `status_idSTATUS` INT(11) NOT NULL,
  `usuario_idUsuario` BIGINT(20) NOT NULL,
  PRIMARY KEY (`idchamado`, `nivelcriticidade_idnivelcriticidade`, `status_idSTATUS`, `usuario_idUsuario`),
  INDEX `fk_chamado_nivelcriticidade1_idx` (`nivelcriticidade_idnivelcriticidade` ASC) VISIBLE,
  INDEX `fk_chamado_status1_idx` (`status_idSTATUS` ASC) VISIBLE,
  INDEX `fk_chamado_usuario1_idx` (`usuario_idUsuario` ASC) VISIBLE,
  CONSTRAINT `fk_chamado_nivelcriticidade1`
    FOREIGN KEY (`nivelcriticidade_idnivelcriticidade`)
    REFERENCES `dtbutil`.`nivelcriticidade` (`idnivelcriticidade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chamado_status1`
    FOREIGN KEY (`status_idSTATUS`)
    REFERENCES `dtbutil`.`status` (`idSTATUS`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chamado_usuario1`
    FOREIGN KEY (`usuario_idUsuario`)
    REFERENCES `dtbutil`.`usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `dtbutil` ;

-- -----------------------------------------------------
-- procedure PROC_INSERT_BEGINNIG_USER
-- -----------------------------------------------------

DELIMITER $$
USE `dtbutil`$$
CREATE PROCEDURE `PROC_INSERT_BEGINNIG_USER`(	
	NOME varchar(100),	
    LOGIN varchar(45),
	EMAIL varchar(60), 	
    TELEFONE_UM char(40) ,
    CIDADE VARCHAR(45), 
    PAIS char(45),
    SENHA VARCHAR(45)
)
BEGIN
	DECLARE newID BIGINT;   
    
    INSERT INTO 
		usuario 
	(	
		NOME ,	
		LOGIN ,
		EMAIL , 	
		TELEFONE_UM  ,
		CIDADE , 
		PAIS ,
        STATUS_idSTATUS,
        TIPOUSUARIO_idTIPOUSUARIO,
        DATACADASTRO
	) 
    VALUES (
		NOME ,	
		LOGIN ,
		EMAIL , 	
		TELEFONE_UM  ,
		CIDADE , 
		PAIS ,
        STATUS_idSTATUS,
        TIPOUSUARIO_idTIPOUSUARIO,
        NOW()
    );

	SET newID = (SELECT LAST_INSERT_ID());
   -- ********INSERIR NA TABELA DE SENHA*********---1 - SENHA ACESSO
	INSERT INTO senha
		(SENHA,
		TIPO,
		USUARIO_idUSUARIO,
		DATACADASTRO
        )
		VALUES
		(SENHA,
		"1",
		newID,
		NOW());
		
  -- ********INSERIR NA TABELA DE REDE********---SALDO CTC
	INSERT INTO `saldousuario`
		(
		`valor`,
        tiposaldo,
		`USUARIO_idUSUARIO`)
		VALUES
		(
        0,
		1,		
		newID);
        
	  -- ********INSERIR NA TABELA DE REDE********---SALDO INTERNO
	INSERT INTO `saldousuario`
		(
		`valor`,
        tiposaldo,
		`USUARIO_idUSUARIO`)
		VALUES
		(
        0,
		2,		
		newID);
	
	
	insert into mensagem (mensagem, usuario_idusuario)
	values(
		'<b>ConnectStarCoin:</b></br>
	<b>Bem-vindo(a).</b></br>',
		newID
	);
		

	    
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
status