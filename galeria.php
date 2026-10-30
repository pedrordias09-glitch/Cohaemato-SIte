<?php
require_once __DIR__ . '/includes/functions.php';
$fotos = get_galeria();
$cats = array_values(array_unique(array_column($fotos, 'categoria')));
$nomes = ['pavilhao' => 'Pavilhão', 'jogos' => 'Jogos', 'formacao' => 'Formação', 'equipas' => 'Equipas', 'eventos' => 'Eventos'];
render_header('Galeria', 'Fotografias do GDC Cohaemato: jogos, treinos, equipas e eventos.');
render_hero_pagina('Galeria', 'Momentos que fazem o clube.');
?>
<section class="secao">
    <div class="wrap">
        <?php if (!db_ativa()): ?>
            <div class="aviso info"><i class="fa-solid fa-circle-info"></i> Modo demonstração: espaços reservados. Coloque as fotos em <code>assets/img/galeria/</code> e registe-as na tabela <code>galeria</code>.</div>
        <?php endif; ?>

        <div class="filtros">
            <button class="filtro ativo" data-filtro="todos">Todas</button>
            <?php foreach ($cats as $c): ?>
                <button class="filtro" data-filtro="<?= e($c) ?>"><?= e($nomes[$c] ?? ucfirst($c)) ?></button>
            <?php endforeach; ?>
        </div>

        <div class="galeria">
            <?php foreach ($fotos as $f): ?>
                <button class="foto" type="button" data-cat="<?= e($f['categoria']) ?>" data-titulo="<?= e($f['titulo']) ?>">
                    <?php if (!empty($f['ficheiro'])): ?>
                        <img src="assets/img/galeria/<?= e(basename($f['ficheiro'])) ?>" alt="<?= e($f['titulo']) ?>" loading="lazy">
                    <?php else: ?>
                        <span class="ph"><img src="assets/img/logo.png" alt=""></span>
                    <?php endif; ?>
                    <span class="leg"><?= e($f['titulo']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Fotografia ampliada"><button aria-label="Fechar">&times;</button><figure></figure></div>
<?php render_footer();
