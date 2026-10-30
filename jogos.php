<?php
require_once __DIR__ . '/includes/functions.php';
$equipas = get_equipas();
$todos = get_jogos(500);
$hoje = date('Y-m-d');

/** Uma linha de jogo (resultado ou calendário). */
function linha_jogo(array $j, bool $mostrar_equipa): void
{
    $tag = resultado_tag($j);
    $casa = (int)$j['em_casa'] === 1;
    $c = $j['golos_cohaemato']; $a = $j['golos_adversario'];
    $esq = $casa ? 'Cohaemato' : $j['adversario'];
    $dir = $casa ? $j['adversario'] : 'Cohaemato';
    $pe = $casa ? $c : $a; $pd = $casa ? $a : $c;
    ?>
    <li class="lj<?= $tag ? ' lj-' . strtolower($tag[0]) : '' ?>">
        <div class="lj-data"><b><?= e(data_pt($j['data_jogo'], true)) ?></b><span><?= e(dia_semana_pt($j['data_jogo'])) ?></span></div>
        <div class="lj-corpo">
            <div class="lj-jogo">
                <span class="lj-eq<?= $casa ? ' nos' : '' ?>"><?= e($esq) ?></span>
                <span class="lj-placar<?= $tag ? '' : ' vazio' ?>"><?= $tag ? e("$pe – $pd") : e($j['hora'] ? substr($j['hora'], 0, 5) : 'a definir') ?></span>
                <span class="lj-eq dir<?= $casa ? '' : ' nos' ?>"><?= e($dir) ?></span>
            </div>
            <div class="lj-meta">
                <?php if ($mostrar_equipa): ?><span class="chip"><?= e($j['escalao']) ?></span><?php endif; ?>
                <span><i class="fa-solid <?= $casa ? 'fa-house-chimney' : 'fa-bus' ?>"></i> <?= $casa ? 'Em casa' : 'Fora' ?></span>
                <span class="lj-comp"><?= e($j['competicao']) ?></span>
            </div>
        </div>
        <?php if ($tag): ?><span class="res-tag <?= $tag[1] ?>" title="<?= ['V' => 'Vitória', 'E' => 'Empate', 'D' => 'Derrota'][$tag[0]] ?>"><?= $tag[0] ?></span><?php endif; ?>
    </li>
    <?php
}

/** Painel de uma equipa (ou de todas, quando $escalao é null). */
function painel_jogos(string $id, ?string $escalao, bool $ativo): void
{
    $res = get_resultados($escalao);
    $prox = get_proximos($escalao);
    $todas = $escalao === null;
    if ($todas) { $prox = array_slice($prox, 0, 12); $res = array_slice($res, 0, 12); }
    $v = $e = $d = $gm = $gs = 0;
    foreach (get_resultados($escalao) as $j) {
        $c = (int)$j['golos_cohaemato']; $a = (int)$j['golos_adversario'];
        $gm += $c; $gs += $a;
        if ($c > $a) $v++; elseif ($c < $a) $d++; else $e++;
    }
    $np = $prox[0] ?? null;
    ?>
    <div class="jg-painel" role="tabpanel" id="jg-<?= e($id) ?>"<?= $ativo ? '' : ' hidden' ?>>
        <div class="jg-topo">
            <?php if ($np): ?>
            <div class="proximo">
                <span class="proximo-et"><i class="fa-solid fa-calendar-day"></i> Próximo jogo</span>
                <div class="proximo-data"><b><?= e(data_pt($np['data_jogo'], true)) ?></b> <?= e(dia_semana_pt($np['data_jogo'])) ?><?= $np['hora'] ? ' · ' . e(substr($np['hora'], 0, 5)) : '' ?></div>
                <div class="proximo-vs">
                    <span class="<?= $np['em_casa'] ? 'nos' : '' ?>"><?= $np['em_casa'] ? 'Cohaemato' : e($np['adversario']) ?></span>
                    <i>vs</i>
                    <span class="<?= $np['em_casa'] ? '' : 'nos' ?>"><?= $np['em_casa'] ? e($np['adversario']) : 'Cohaemato' ?></span>
                </div>
                <small><?= $todas ? e($np['escalao']) . ' · ' : '' ?><?= $np['em_casa'] ? 'Em casa, ' . e(CLUBE['pavilhao']) : 'Fora de casa' ?> · <?= e($np['competicao']) ?></small>
            </div>
            <?php endif; ?>
            <?php if (!$todas): ?>
            <dl class="resumo">
                <div><dt>Jogos</dt><dd><?= $v + $e + $d ?></dd></div>
                <div class="v"><dt>Vitórias</dt><dd><?= $v ?></dd></div>
                <div class="e"><dt>Empates</dt><dd><?= $e ?></dd></div>
                <div class="d"><dt>Derrotas</dt><dd><?= $d ?></dd></div>
                <div><dt>Golos</dt><dd><?= $gm ?>–<?= $gs ?></dd></div>
            </dl>
            <?php endif; ?>
        </div>

        <div class="jg-cols">
            <section>
                <h2 class="jg-h">Resultados <small><?= $todas ? 'mais recentes' : count($res) . ' jogos' ?></small></h2>
                <?php if ($res): ?><ul class="lista-jogos"><?php foreach ($res as $j) linha_jogo($j, $todas); ?></ul>
                <?php else: ?><p class="vazio">Ainda não há resultados.</p><?php endif; ?>
            </section>
            <section>
                <h2 class="jg-h">Calendário <small><?= $todas ? 'próximos jogos' : count($prox) . ' jogos' ?></small></h2>
                <?php if ($prox): ?><ul class="lista-jogos"><?php foreach ($prox as $j) linha_jogo($j, $todas); ?></ul>
                <?php else: ?><p class="vazio">Não há jogos agendados.</p><?php endif; ?>
            </section>
        </div>
    </div>
    <?php
}

$com_jogos = array_values(array_filter($equipas, fn($eq) => in_array($eq['nome'], array_column($todos, 'escalao'), true)));
render_header('Jogos', 'Resultados e calendário de jogos de todas as equipas do GDC Cohaemato.');
render_hero_pagina('Jogos e resultados', 'Resultados e calendário da época 2026/27.');
?>
<section class="secao">
    <div class="wrap">
        <?php if (!db_ativa()): ?>
            <div class="aviso info"><i class="fa-solid fa-circle-info"></i> Modo demonstração: dados copiados do zerozero a 23/10/2026. Ligue a base de dados para gerir os jogos.</div>
        <?php endif; ?>

        <div class="tabs" role="tablist" aria-label="Equipas">
            <button class="tab ativo" role="tab" data-jogos="todas" aria-selected="true" aria-controls="jg-todas">Todas</button>
            <?php foreach ($com_jogos as $eq): ?>
                <button class="tab" role="tab" data-jogos="<?= e($eq['slug']) ?>" aria-selected="false" aria-controls="jg-<?= e($eq['slug']) ?>"><?= e($eq['nome']) ?></button>
            <?php endforeach; ?>
        </div>

        <?php painel_jogos('todas', null, true); ?>
        <?php foreach ($com_jogos as $eq) painel_jogos($eq['slug'], $eq['nome'], false); ?>
    </div>
</section>
<?php render_footer();
