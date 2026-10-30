<?php
/**
 * Configuração geral do site GDC Cohaemato.
 * Para ligar a base de dados: preencha DB_* abaixo (ou defina variáveis de ambiente)
 * e importe database/schema.sql + database/seed.sql.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Base de dados (MySQL / MariaDB) ---
define('DB_ENABLED', true);              // <- passe a true quando a BD estiver pronta
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'cohaemato');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// --- Dados do clube ---
const CLUBE = [
    'nome'        => 'Grupo Desportivo e Cultural Cohaemato',
    'curto'       => 'GDC Cohaemato',
    'fundacao'    => '11 de setembro de 1993',
    'ano'         => 1993,
    'local'       => 'Leça da Palmeira, Matosinhos',
    'pavilhao'    => 'Pavilhão da Cohaemato',
    'morada'      => 'Rua Luís José Alves, s/n',
    'codigo'      => '4450-719 Leça da Palmeira',
    'email'       => 'gdccohaemato.futsal@gdccohaemato.com',
    'site_oficial'=> 'http://www.gdccohaemato.com/',
    'associacao'  => 'Associação de Futebol do Porto',
    'presidente'  => 'José Eduardo Oliveira',   // fonte: zerozero
    'telefone'    => '',          // preencher
    'facebook'    => '#',         // preencher
    'instagram'   => '#',         // preencher
    'youtube'     => '#',         // preencher
];

// Menu principal: ficheiro => [etiqueta, ícone Font Awesome]
const MENU = [
    'index.php'    => ['Início',   'fa-house-chimney'],
    'clube.php'    => ['O Clube',  'fa-people-roof'],
    'equipas.php'  => ['Equipas',  'fa-shirt'],
    'jogos.php'    => ['Jogos',    'fa-futbol'],
    'noticias.php' => ['Notícias', 'fa-newspaper'],
    'galeria.php'  => ['Galeria',  'fa-camera'],
    'inscricoes.php' => ['Inscrições', 'fa-user-plus'],
    'contacto.php' => ['Contacto', 'fa-envelope'],
];

date_default_timezone_set('Europe/Lisbon');
setlocale(LC_TIME, 'pt_PT.UTF-8', 'pt_PT', 'portuguese');
