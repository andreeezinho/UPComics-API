SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bd
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema bd
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bd` ;
USE `bd` ;

-- -----------------------------------------------------
-- Table `bd`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `usuario` VARCHAR(100) NOT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `cpf` VARCHAR(15) NULL,
  `telefone` VARCHAR(15) NULL,
  `senha` VARCHAR(255) NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `icone` VARCHAR(255) NULL DEFAULT 'default.png',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB;

-- inserção do usuário padrão
INSERT INTO `bd`.`usuarios` 
  SET 
    `id` = 1,
    `uuid` = 'a9c6a907-4889-477c-a9a7-a1f0a208c2fb',
    `usuario` = 'adm',
    `nome` = 'Administrador',
    `email` = 'admin@admin.com',
    `cpf` = '111.222.333.45',
    `telefone` = '(99) 99999-9999',
    `senha` = '$2y$10$NA2as4khHjPOlIe/ocscHOmtFQstBd.tiUqYKHw08EnHxDfC//BNK',
    `ativo` = 1,
    `is_admin` = 1
;

-- -----------------------------------------------------
-- Table `bd`.`livros`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`livros` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `descricao` VARCHAR(1000) NULL,
  `autor` VARCHAR(255) NOT NULL,
  `tipo` ENUM('livro', 'quadrinhos') NULL,
  `capa` VARCHAR(255) NOT NULL DEFAULT 'default.png',
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd`.`livros_lidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`livros_lidos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `usuarios_id` INT NOT NULL,
  `livros_id` INT NOT NULL,
  `ultima_pag` INT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `usuarios_id`, `livros_id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
  INDEX `fk_livros_lidos_usuarios_idx` (`usuarios_id` ASC) VISIBLE,
  INDEX `fk_livros_lidos_livros1_idx` (`livros_id` ASC) VISIBLE,
  CONSTRAINT `fk_livros_lidos_usuarios`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `bd`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_livros_lidos_livros1`
    FOREIGN KEY (`livros_id`)
    REFERENCES `bd`.`livros` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd`.`avaliacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`avaliacoes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `avaliacao` DECIMAL(4,1) NOT NULL,
  `livros_id` INT NOT NULL,
  `usuarios_id` INT NOT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `livros_id`, `usuarios_id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
  INDEX `fk_avaliacoes_livros1_idx` (`livros_id` ASC) VISIBLE,
  INDEX `fk_avaliacoes_usuarios1_idx` (`usuarios_id` ASC) VISIBLE,
  CONSTRAINT `fk_avaliacoes_livros1`
    FOREIGN KEY (`livros_id`)
    REFERENCES `bd`.`livros` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_avaliacoes_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `bd`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd`.`notificacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`notificacoes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `titulo` VARCHAR(100) NOT NULL,
  `mensagem` TEXT NOT NULL,
  `lido` TINYINT(1) NOT NULL DEFAULT 1,
  `usuarios_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `usuarios_id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
  INDEX `fk_notificacoes_usuarios1_idx` (`usuarios_id` ASC) VISIBLE,
  CONSTRAINT `fk_notificacoes_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `bd`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd`.`recuperar_senha`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`recuperar_senha` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `usuarios_id` INT NOT NULL,
  `codigo` INT NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `usuarios_id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
  INDEX `fk_recuperar_senha_usuarios1_idx` (`usuarios_id` ASC) VISIBLE,
  CONSTRAINT `fk_recuperar_senha_usuarios1`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `bd`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd`.`volumes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`volumes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `livros_id` INT NOT NULL,
  `volume` INT NULL,
  `paginas` INT NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `livros_id`),
  UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC) VISIBLE,
  INDEX `fk_volumes_livros1_idx` (`livros_id` ASC) VISIBLE,
  CONSTRAINT `fk_volumes_livros1`
    FOREIGN KEY (`livros_id`)
    REFERENCES `bd`.`livros` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
