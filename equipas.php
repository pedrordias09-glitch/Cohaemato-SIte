<?php
require_once __DIR__ . '/includes/functions.php';
$equipas = get_equipas();
$jogadores = get_jogadores();
$ordem_pos = ['Guarda-redes', 'Defesa', 'Fixo', 'Fixo / Ala', 'Médio', 'Universal', 'Ala', 'Ala / Pivô', 'Pivô'];
render_header('Equipas', 'Todas as equipas de futsal do GDC Cohaemato: plantéis dos seniores e escalões de formação.');
render_hero_pagina('Equipas', 'Dos mais novos aos seniores, o futsal vive em cada escalão.');
?>
<section class="secao">
    <div class="wrap">
        <div class="tabs" role="tablist" aria-label="Equipas">
            <?php foreach ($equipas as $i => $eq): ?>
                <button class="tab<?= $i === 0 ? ' ativo' : '' ?>" role="tab" id="tab-<?= e($eq['slug']) ?>" data-equipa="<?= e($eq['slug']) ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="painel-<?= e($eq['slug']) ?>"><?= e($eq['nome']) ?></button>
            <?php endforeach; ?>
        </div>

        <?php foreach ($equipas as $i => $eq):
            $lista = $jogadores[$eq['slug']] ?? [];
            $grupos = [];
            foreach ($lista as $j) $grupos[$j['posicao'] ?? 'Ala'][] = $j;
        ?>
        <article class="eq-painel" role="tabpanel" id="painel-<?= e($eq['slug']) ?>" aria-labelledby="tab-<?= e($eq['slug']) ?>"<?= $i === 0 ? '' : ' hidden' ?>>
            <div class="painel-info cartao">
                <div class="faixa"></div>
                <div class="cartao-corpo">
                    <div class="sigla"><?= e($eq['sigla'] ?? '') ?></div>
                    <h2><?= e($eq['nome']) ?></h2>
                    <span class="chip"><?= e($eq['escalao']) ?></span>
                    <p><?= e($eq['descricao']) ?></p>
                    <ul class="lista-info">
                        <li><i class="fa-solid fa-medal"></i> <?= e($eq['competicao']) ?></li>
                        <li><i class="fa-solid fa-user-tie"></i> Treinador: <?= e($eq['treinador'] ?: 'a anunciar') ?></li>
                        <li><i class="fa-solid fa-users"></i> <?= count($lista) ?> jogadores</li>
                        <li><i class="fa-solid fa-house-chimney"></i> <?= e(CLUBE['pavilhao']) ?></li>
                    </ul>
                </div>
            </div>

            <?php if (!$lista): ?>
                <p class="vazio">O plantel desta equipa será publicado em breve.</p>
            <?php else: foreach (array_merge(array_intersect($ordem_pos, array_keys($grupos)), array_diff(array_keys($grupos), $ordem_pos)) as $pos): ?>
                <h3 class="pos-titulo"><?= e($pos) ?></h3>
                <div class="jogadores">
                    <?php foreach ($grupos[$pos] as $j): $id = idade($j['nascimento'] ?? null, isset($j['idade']) ? (int)$j['idade'] : null); ?>
                    <div class="jogador">
                        <div class="foto-j">
                            <?php $foto = foto_jogador($j, $eq['slug']); if ($foto): ?><img src="<?= e($foto) ?>" alt="<?= e($j['nome']) ?>" loading="lazy"><?php else: ?><img class="ph" src="assets/img/logo.png" alt=""><?php endif; ?>
                            <?php if ($j['numero'] !== null && $j['numero'] !== ''): ?><span class="num"><?= (int)$j['numero'] ?></span><?php endif; ?>
                        </div>
                        <strong><?= e($j['nome']) ?></strong>
                        <small><?= e($j['posicao']) ?><?= $id !== null ? ' · ' . $id . ' anos' : '' ?><?= !empty($j['nacionalidade']) ? ' · ' . e($j['nacionalidade']) : '' ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; endif; ?>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php render_footer();
