<?php
require_once __DIR__ . '/includes/functions.php';
$slug = (string)($_GET['s'] ?? '');
$n = $slug !== '' ? get_noticia($slug) : null;

if (!$n) {
    http_response_code(404);
    render_header('Notícia não encontrada');
    render_hero_pagina('Notícia não encontrada');
    echo '<section class="secao"><div class="wrap"><p>Esta notícia não existe ou foi removida. <a href="noticias.php">Voltar às notícias</a>.</p></div></section>';
    render_footer();
    exit;
}

render_header($n['titulo'], $n['resumo']);
render_hero_pagina($n['titulo'], data_pt($n['data_publicacao']));
?>
<section class="secao">
    <div class="wrap">
        <article class="artigo">
            <?php foreach (preg_split('/\R{2,}/', trim($n['conteudo'])) as $par): ?>
                <p><?= nl2br(e($par)) ?></p>
            <?php endforeach; ?>
            <p><a href="noticias.php">← Voltar às notícias</a></p>
        </article>
    </div>
</section>
<?php render_footer();
