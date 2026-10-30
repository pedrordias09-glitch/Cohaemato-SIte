-- Base de dados do site GDC Cohaemato (MySQL 5.7+ / MariaDB 10.3+)
CREATE DATABASE IF NOT EXISTS cohaemato CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cohaemato;

CREATE TABLE IF NOT EXISTS equipas (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(80)  NOT NULL UNIQUE,
    sigla       VARCHAR(6)   NOT NULL DEFAULT '',
    nome        VARCHAR(80)  NOT NULL,
    escalao     VARCHAR(80)  NOT NULL,
    competicao  VARCHAR(160) NOT NULL,
    treinador   VARCHAR(120) NULL,
    descricao   TEXT         NOT NULL,
    ordem       SMALLINT     NOT NULL DEFAULT 0,
    ativa       TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jogadores (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipa_id    INT UNSIGNED NOT NULL,
    nome         VARCHAR(120) NOT NULL,
    numero       TINYINT UNSIGNED NULL,
    posicao      VARCHAR(30)  NOT NULL DEFAULT 'Ala',   -- Guarda-redes, Fixo, Ala, Pivô, Treinador
    nacionalidade VARCHAR(60) NULL,
    nascimento   DATE         NULL,
    idade        TINYINT UNSIGNED NULL,                 -- idade de referência quando não há data de nascimento
    foto         VARCHAR(200) NULL,                     -- ficheiro em assets/img/jogadores/
    ativo        TINYINT(1)   NOT NULL DEFAULT 1,
    FOREIGN KEY (equipa_id) REFERENCES equipas(id) ON DELETE CASCADE,
    INDEX (equipa_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS noticias (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(160) NOT NULL UNIQUE,
    titulo          VARCHAR(200) NOT NULL,
    resumo          VARCHAR(400) NOT NULL,
    conteudo        MEDIUMTEXT   NOT NULL,
    data_publicacao DATE         NOT NULL,
    destaque        TINYINT(1)   NOT NULL DEFAULT 0,
    publicada       TINYINT(1)   NOT NULL DEFAULT 1,
    INDEX (data_publicacao)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jogos (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    escalao          VARCHAR(80)  NOT NULL,
    data_jogo        DATE         NOT NULL,
    hora             TIME         NULL,       -- NULL = hora por definir
    adversario       VARCHAR(120) NOT NULL,
    em_casa          TINYINT(1)   NOT NULL DEFAULT 1,
    golos_cohaemato  TINYINT UNSIGNED NULL,   -- NULL = jogo ainda não realizado
    golos_adversario TINYINT UNSIGNED NULL,
    competicao       VARCHAR(160) NOT NULL,
    local            VARCHAR(160) NULL,
    INDEX (data_jogo)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS galeria (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo    VARCHAR(160) NOT NULL,
    categoria VARCHAR(40)  NOT NULL DEFAULT 'eventos',  -- pavilhao, jogos, formacao, equipas, eventos
    ficheiro  VARCHAR(200) NULL,                        -- nome do ficheiro em assets/img/galeria/
    visivel   TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mensagens (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(120) NOT NULL,
    email     VARCHAR(160) NOT NULL,
    assunto   VARCHAR(160) NOT NULL,
    mensagem  TEXT         NOT NULL,
    ip        VARCHAR(45)  NULL,
    lida      TINYINT(1)   NOT NULL DEFAULT 0,
    criada_em DATETIME     NOT NULL
) ENGINE=InnoDB;


-- Pedidos de inscrição: guardar apenas os dados necessários para contacto.
CREATE TABLE IF NOT EXISTS inscricoes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_atleta VARCHAR(120) NOT NULL,
    data_nascimento DATE NULL,
    escalao_interesse VARCHAR(80) NOT NULL,
    nome_encarregado VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    telefone VARCHAR(30) NOT NULL,
    mensagem VARCHAR(2000) NULL,
    estado ENUM('nova','contactada','concluida') NOT NULL DEFAULT 'nova',
    criada_em DATETIME NOT NULL,
    INDEX (criada_em), INDEX (estado)
) ENGINE=InnoDB;
