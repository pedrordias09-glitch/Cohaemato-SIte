<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/fallback.php';

// Compatibilidade com alojamentos PHP sem a extensão mbstring.
if (!function_exists('mb_substr')) {
    function mb_substr(string $string, int $start, ?int $length = null, ?string $encoding = null): string
    {
        if (function_exists('iconv_substr')) {
            $result = $length === null ? iconv_substr($string, $start, null, 'UTF-8') : iconv_substr($string, $start, $length, 'UTF-8');
            return $result === false ? '' : $result;
        }
        return $length === null ? substr($string, $start) : substr($string, $start, $length);
    }
}
if (!function_exists('mb_strlen')) {
    function mb_strlen(string $string, ?string $encoding = null): int
    {
        if (function_exists('iconv_strlen')) { $length = iconv_strlen($string, 'UTF-8'); return $length === false ? strlen($string) : $length; }
        return strlen($string);
    }
}

/* ---------- Helpers ---------- */

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function data_pt(string $date, bool $curta = false): string
{
    $meses = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
    $t = strtotime($date);
    if (!$t) return '';
    $m = $meses[(int)date('n', $t) - 1];
    return $curta ? date('d', $t) . ' ' . mb_substr($m, 0, 3) : date('j', $t) . ' de ' . $m . ' de ' . date('Y', $t);
}

function db_ativa(): bool
{
    return db() !== null;
}

/** Executa uma query; devolve null em caso de erro para ativar o fallback. */
function consulta(string $sql, array $params = []): ?array
{
    $pdo = db();
    if (!$pdo) return null;
    try {
        $st = $pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    } catch (PDOException $e) {
        error_log('Cohaemato SQL: ' . $e->getMessage());
        return null;
    }
}

/* ---------- Acesso a dados (BD ou exemplo) ---------- */

function get_equipas(): array
{
    return consulta('SELECT * FROM equipas WHERE ativa = 1 ORDER BY ordem, nome') ?? fallback_equipas();
}

/** Jogadores agrupados por slug da equipa. */
function get_jogadores(): array
{
    $r = consulta('SELECT j.*, e.slug AS equipa_slug FROM jogadores j JOIN equipas e ON e.id = j.equipa_id WHERE j.ativo = 1 ORDER BY j.numero IS NULL, j.numero, j.nome');
    if (!$r) return fallback_jogadores();   // BD desligada ou ainda sem jogadores
    $out = [];
    foreach ($r as $j) $out[$j['equipa_slug']][] = $j;
    return $out;
}

/** Nome -> nome de ficheiro: "Cláudio Oliveira" -> "claudio-oliveira". */
function slug_nome(string $nome): string
{
    $nome = strtr(mb_strtolower($nome, 'UTF-8'), ['á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c','ñ'=>'n']);
    return trim(preg_replace('/[^a-z0-9]+/', '-', $nome), '-');
}

/**
 * Procura a foto de um jogador em assets/img/jogadores/. Ordem:
 *  1. o ficheiro indicado no campo "foto" do jogador;
 *  2. assets/img/jogadores/<slug-da-equipa>/<nome-do-jogador>.(jpg|jpeg|png|webp)
 *  3. assets/img/jogadores/<nome-do-jogador>.(jpg|jpeg|png|webp)
 * Devolve o caminho relativo ao site, ou null (aparece o emblema do clube).
 */
function foto_jogador(array $j, string $equipa_slug): ?string
{
    $base = __DIR__ . '/../assets/img/jogadores/';
    $url = 'assets/img/jogadores/';
    if (!empty($j['foto']) && is_file($base . $j['foto'])) return $url . $j['foto'];
    $nome = slug_nome($j['nome']);
    foreach ([$equipa_slug . '/', ''] as $pasta) {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            if (is_file($base . $pasta . $nome . '.' . $ext)) return $url . $pasta . $nome . '.' . $ext;
        }
    }
    return null;
}

function idade(?string $nasc, ?int $fixa = null): ?int
{
    if (!$nasc) return $fixa;
    try { return (new DateTime($nasc))->diff(new DateTime('today'))->y; } catch (Exception $e) { return null; }
}

function get_noticias(int $limite = 20): array
{
    $r = consulta('SELECT * FROM noticias WHERE publicada = 1 ORDER BY data_publicacao DESC, id DESC LIMIT ' . (int)$limite);
    return $r ?? array_slice(fallback_noticias(), 0, $limite);
}

function get_noticia(string $slug): ?array
{
    $r = consulta('SELECT * FROM noticias WHERE slug = ? AND publicada = 1 LIMIT 1', [$slug]);
    if ($r === null) {
        foreach (fallback_noticias() as $n) {
            if ($n['slug'] === $slug) return $n;
        }
        return null;
    }
    return $r[0] ?? null;
}

function get_jogos(int $limite = 500): array
{
    $r = consulta('SELECT * FROM jogos ORDER BY data_jogo DESC, hora DESC, id DESC LIMIT ' . (int)$limite);
    return $r ?? array_slice(fallback_jogos(), 0, $limite);
}

function jogo_jogado(array $j): bool
{
    return $j['golos_cohaemato'] !== null && $j['golos_adversario'] !== null;
}

/** Resultados já realizados, do mais recente para o mais antigo. */
function get_resultados(?string $escalao = null, int $limite = 500): array
{
    $r = array_filter(get_jogos(500), fn($j) => jogo_jogado($j) && (!$escalao || $j['escalao'] === $escalao));
    usort($r, fn($a, $b) => strcmp($b['data_jogo'] . ($b['hora'] ?? ''), $a['data_jogo'] . ($a['hora'] ?? '')));
    return array_slice($r, 0, $limite);
}

/** Jogos por realizar, do mais próximo para o mais distante. */
function get_proximos(?string $escalao = null, int $limite = 500): array
{
    $r = array_filter(get_jogos(500), fn($j) => !jogo_jogado($j) && (!$escalao || $j['escalao'] === $escalao));
    usort($r, fn($a, $b) => strcmp($a['data_jogo'] . ($a['hora'] ?? ''), $b['data_jogo'] . ($b['hora'] ?? '')));
    return array_slice($r, 0, $limite);
}

function dia_semana_pt(string $date): string
{
    $d = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sáb'];
    $t = strtotime($date);
    return $t ? $d[(int)date('w', $t)] : '';
}

function resultado_tag(array $j): ?array
{
    if (!jogo_jogado($j)) return null;
    $c = (int)$j['golos_cohaemato']; $a = (int)$j['golos_adversario'];
    return $c > $a ? ['V', 'res-v'] : ($c < $a ? ['D', 'res-d'] : ['E', 'res-e']);
}

function get_galeria(): array
{
    return consulta('SELECT * FROM galeria WHERE visivel = 1 ORDER BY id DESC') ?? fallback_galeria();
}

function get_estatisticas(): array
{
    $equipas = get_equipas();
    $formacao = count(array_filter($equipas, fn($e) => $e['escalao'] !== 'Seniores'));
    $jogos = get_jogos(500);
    return ['equipas' => count($equipas), 'formacao' => $formacao, 'anos' => (int)date('Y') - CLUBE['ano'], 'jogos' => count($jogos)];
}

function guardar_mensagem(array $d): bool
{
    $pdo = db();
    if (!$pdo) return false;
    try {
        $st = $pdo->prepare('INSERT INTO mensagens (nome, email, assunto, mensagem, ip, criada_em) VALUES (?, ?, ?, ?, ?, NOW())');
        return $st->execute([$d['nome'], $d['email'], $d['assunto'], $d['mensagem'], $_SERVER['REMOTE_ADDR'] ?? null]);
    } catch (PDOException $e) {
        error_log('Cohaemato SQL: ' . $e->getMessage());
        return false;
    }
}

function guardar_inscricao(array $d): bool
{
    $pdo = db();
    if (!$pdo) return false;
    try {
        $st = $pdo->prepare('INSERT INTO inscricoes (nome_atleta, data_nascimento, escalao_interesse, nome_encarregado, email, telefone, mensagem, criada_em) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        return $st->execute([$d['nome_atleta'], $d['data_nascimento'] ?: null, $d['escalao_interesse'], $d['nome_encarregado'], $d['email'], $d['telefone'], $d['mensagem'] ?: null]);
    } catch (PDOException $e) {
        error_log('Cohaemato inscrição: ' . $e->getMessage());
        return false;
    }
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_ok(?string $t): bool
{
    return !empty($_SESSION['csrf']) && is_string($t) && hash_equals($_SESSION['csrf'], $t);
}

/* ---------- Layout ---------- */

function pagina_atual(): string
{
    return basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
}

function render_header(string $titulo, string $descricao = ''): void
{
    $atual = pagina_atual();
    if ($atual === 'noticia.php') $atual = 'noticias.php';
    $titulo_completo = $titulo === 'Início' ? CLUBE['curto'] . ' – Futsal em Leça da Palmeira' : $titulo . ' · ' . CLUBE['curto'];
    $descricao = $descricao ?: 'Site oficial do ' . CLUBE['nome'] . ', clube de futsal de Leça da Palmeira, Matosinhos. Equipas, jogos, notícias e contactos.';
    ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo_completo) ?></title>
    <meta name="description" content="<?= e($descricao) ?>">
    <meta name="theme-color" content="#15773e">
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<a class="skip" href="#conteudo">Saltar para o conteúdo</a>
<header class="topo">
    <div class="wrap topo-in">
        <a class="marca" href="index.php" aria-label="<?= e(CLUBE['curto']) ?> – página inicial">
            <img src="assets/img/logo.png" alt="" width="46" height="46">
            <span><strong>Cohaemato</strong><small>Futsal · Leça da Palmeira</small></span>
        </a>
        <button class="menu-btn" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu"><i class="fa-solid fa-bars"></i></button>
        <nav id="menu" class="menu" aria-label="Principal">
            <?php foreach (MENU as $ficheiro => [$rotulo, $icone]): ?>
                <a href="<?= e($ficheiro) ?>"<?= $atual === $ficheiro ? ' class="ativo" aria-current="page"' : '' ?>><i class="fa-solid <?= e($icone) ?>"></i> <?= e($rotulo) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
<main id="conteudo">
<?php
}

function render_hero_pagina(string $titulo, string $sub = ''): void
{
    ?>
<section class="hero-pagina">
    <img class="marca-agua" src="assets/img/logo.png" alt="">
    <div class="wrap">
        <p class="eyebrow">GDC Cohaemato</p>
        <h1><?= e($titulo) ?></h1>
        <?php if ($sub): ?><p class="lead"><?= e($sub) ?></p><?php endif; ?>
    </div>
</section>
<?php
}

function render_footer(): void
{
    ?>
</main>
<footer class="rodape">
    <div class="wrap rodape-grid">
        <div>
            <a class="marca" href="index.php"><img src="assets/img/logo.png" alt="" width="52" height="52"><span><strong>Cohaemato</strong><small><?= e(CLUBE['nome']) ?></small></span></a>
            <p class="peq">Clube de futsal fundado a <?= e(CLUBE['fundacao']) ?>, em <?= e(CLUBE['local']) ?>.</p>
        </div>
        <div>
            <h3>Navegar</h3>
            <ul>
                <?php foreach (MENU as $f => [$r]): ?><li><a href="<?= e($f) ?>"><?= e($r) ?></a></li><?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h3>Contactos</h3>
            <ul class="contactos">
                <li><i class="fa-solid fa-location-dot"></i> <?= e(CLUBE['pavilhao']) ?><br><?= e(CLUBE['morada']) ?><br><?= e(CLUBE['codigo']) ?></li>
                <li><i class="fa-solid fa-envelope"></i> <a href="mailto:<?= e(CLUBE['email']) ?>"><?= e(CLUBE['email']) ?></a></li>
                <li><i class="fa-solid fa-globe"></i> <a href="<?= e(CLUBE['site_oficial']) ?>" rel="noopener" target="_blank">gdccohaemato.com</a></li>
            </ul>
            <div class="redes">
                <a href="<?= e(CLUBE['facebook']) ?>" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="<?= e(CLUBE['instagram']) ?>" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="<?= e(CLUBE['youtube']) ?>" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
    </div>
    <div class="wrap rodape-fim">© <?= date('Y') ?> <?= e(CLUBE['nome']) ?>. Todos os direitos reservados.</div>
</footer>
<script src="assets/js/main.js" defer></script>
</body>
</html>
<?php
}
