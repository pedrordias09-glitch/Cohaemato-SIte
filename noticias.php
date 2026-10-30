<?php
require_once __DIR__ . '/includes/functions.php';
$noticias = get_noticias(30);
render_header('Notícias', 'Últimas notícias do GDC Cohaemato.');
render_hero_pagina('Notícias', 'Tudo o que se passa no clube.');
$destaque = $noticias[0] ?? null;
$resto = array_slice($noticias, 1);
?>
<section class="secao">
    <div class="wrap">
        <?php if (!db_ativa()): ?>
            <div class="aviso info"><i class="fa-solid fa-circle-info"></i> Modo demonstração: notícias de exemplo. Ligue a base de dados para publicar as suas.</div>
        <?php endif; ?>

        <?php if ($destaque): ?>
            <article class="destaque rev" style="margin-bottom:30px">
                <div class="img"><img src="assets/img/logo.png" alt=""></div>
                <div class="txt">
                    <span class="meta" style="color:var(--gold-2)"><i class="fa-regular fa-calendar"></i> <?= e(data_pt($destaque['data_publicacao'])) ?></span>
                    <h3><?= e($destaque['titulo']) ?></h3>
                    <p><?= e($destaque['resumo']) ?></p>
                    <a class="btn btn-ouro" href="noticia.php?s=<?= urlencode($destaque['slug']) ?>">Ler notícia <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </article>
        <?php endif; ?>

        <div class="grelha g3">
            <?php foreach ($resto as $n): ?>
                <article class="cartao rev">
                    <div class="faixa"></div>
                    <div class="cartao-corpo">
                        <span class="meta"><i class="fa-regular fa-calendar"></i> <?= e(data_pt($n['data_publicacao'])) ?></span>
                        <h3><?= e($n['titulo']) ?></h3>
                        <p><?= e($n['resumo']) ?></p>
                        <a href="noticia.php?s=<?= urlencode($n['slug']) ?>"><strong>Ler mais →</strong></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php render_footer();
