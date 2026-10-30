<?php
require_once __DIR__ . '/includes/functions.php';
render_header('O Clube', 'História e identidade do Grupo Desportivo e Cultural Cohaemato, em Leça da Palmeira.');
render_hero_pagina('O Clube', 'Mais de três décadas de futsal em Leça da Palmeira.');
?>
<section class="secao">
    <div class="wrap grelha g2" style="align-items:start">
        <div class="rev">
            <div class="sec-topo"><h2>Quem somos</h2></div>
            <p>O <strong><?= e(CLUBE['nome']) ?></strong> foi fundado a <?= e(CLUBE['fundacao']) ?>, em <?= e(CLUBE['local']) ?>. É um clube dedicado ao futsal, com equipas desde os escalões mais jovens até aos seniores, filiado na <?= e(CLUBE['associacao']) ?>.</p>
            <p style="margin-top:14px">O <?= e(CLUBE['pavilhao']) ?> é a casa do clube e o ponto de encontro de atletas, famílias e adeptos.</p>
            
        </div>
        <div class="painel rev">
            <h2>Em resumo</h2>
            <div class="info-item"><i class="fa-solid fa-calendar-days"></i><div><b>Fundação</b><?= e(CLUBE['fundacao']) ?></div></div>
            <div class="info-item"><i class="fa-solid fa-location-dot"></i><div><b>Sede e pavilhão</b><?= e(CLUBE['pavilhao']) ?><br><?= e(CLUBE['morada']) ?>, <?= e(CLUBE['codigo']) ?></div></div>
            <div class="info-item"><i class="fa-solid fa-flag"></i><div><b>Modalidade</b>Futsal</div></div>
            <div class="info-item"><i class="fa-solid fa-user-tie"></i><div><b>Presidente</b><?= e(CLUBE['presidente']) ?></div></div>
            <div class="info-item"><i class="fa-solid fa-landmark"></i><div><b>Associação</b><?= e(CLUBE['associacao']) ?></div></div>
        </div>
    </div>
</section>

<section class="secao alt">
    <div class="wrap">
        <div class="sec-topo"><h2>Linha do tempo</h2></div>
        <div class="tempo rev">
            <div><b>1993</b><p>Fundação do Grupo Desportivo e Cultural Cohaemato, a 11 de setembro, em Leça da Palmeira.</p></div>
            <div><b>Formação</b><p>O clube mantém escalões de formação de futsal do Sub-11 ao Sub-19, nas competições da AF Porto.</p></div>
            <div><b>Palmarés</b><p>O zerozero regista 5 títulos regionais nos Juniores D (Sub-13): 4 da 1.ª Divisão e 1 Taça da AF Porto.</p></div>
            <div><b>Hoje</b><p>Equipa sénior na Divisão de Elite da AF Porto, com jogos no Pavilhão da Cohaemato.</p></div>
            <div><p style="color:var(--muted)"><em>Dados recolhidos no zerozero. A direção pode acrescentar aqui outros marcos da história do clube.</em></p></div>
        </div>
    </div>
</section>
<?php render_footer();
