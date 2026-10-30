<?php
require_once __DIR__ . '/includes/functions.php';

$st = get_estatisticas();
$noticias = get_noticias(3);
$equipas = array_slice(get_equipas(), 0, 3);
$ultimo = get_resultados(null, 1)[0] ?? null;
$proximo = get_proximos(null, 1)[0] ?? null;

render_header('Início');
?>
<section class="hero">
    <div class="wrap hero-in">
        <div>
            <span class="eyebrow">Futsal desde <?= CLUBE['ano'] ?> · Leça da Palmeira</span>
            <h1>Futsal<br>com <em>raízes</em><br>em Leça</h1>
            <p class="lead">O <?= e(CLUBE['nome']) ?> é um clube de Matosinhos que vive o futsal todos os dias, da formação aos seniores.</p>
            <div class="acoes">
                <a class="btn btn-ouro" href="equipas.php"><i class="fa-solid fa-users"></i> Conhecer as equipas</a>
                <a class="btn btn-linha" href="contacto.php"><i class="fa-solid fa-envelope"></i> Fala connosco</a>
            </div>
        </div>
        <div class="hero-lado">
        <img class="hero-logo" src="assets/img/logo.png" alt="Emblema do G.D.C. Cohaemato">
        <?php if ($ultimo && $ultimo['golos_cohaemato'] !== null): ?>
            <?php
            $c = (int)$ultimo['golos_cohaemato']; $a = (int)$ultimo['golos_adversario'];
            $tag = $c > $a ? ['V', 'res-v'] : ($c < $a ? ['D', 'res-d'] : ['E', 'res-e']);
            ?>
            <aside class="hero-card" aria-label="Último resultado">
                <h3>Último resultado</h3>
                <div class="vs"><span>Cohaemato</span><span><?= e($ultimo['adversario']) ?></span></div>
                <div class="placar"><?= $c ?> – <?= $a ?></div>
                <small><span class="res-tag <?= $tag[1] ?>"><?= $tag[0] ?></span> &nbsp;<?= e($ultimo['escalao']) ?> · <?= e(data_pt($ultimo['data_jogo'])) ?></small>
                <?php if ($proximo): ?><small class="prox-mini">Próximo: <?= e($proximo['escalao']) ?> · <?= e(dia_semana_pt($proximo['data_jogo'])) ?>, <?= e(data_pt($proximo['data_jogo'], true)) ?><?= $proximo['hora'] ? ' · ' . e(substr($proximo['hora'], 0, 5)) : '' ?> · <?= $proximo['em_casa'] ? 'vs' : '@' ?> <?= e($proximo['adversario']) ?></small><?php endif; ?>
            </aside>
        <?php endif; ?>
        </div>
    </div>
</section>

<section class="stats">
    <div class="wrap stats-grid">
        <div class="stat rev"><b><?= $st['anos'] ?></b><span>anos de história</span></div>
        <div class="stat rev"><b><?= $st['equipas'] ?></b><span>equipas de futsal</span></div>
        <div class="stat rev"><b><?= $st['formacao'] ?></b><span>escalões de formação</span></div>
        <div class="stat rev"><b>AF Porto</b><span>associação filiada</span></div>
    </div>
</section>

<section class="secao">
    <div class="wrap">
        <div class="sec-topo"><h2>As nossas equipas</h2><a class="ver-mais" href="equipas.php">Ver todas <i class="fa-solid fa-arrow-right"></i></a></div>
        <div class="grelha g3">
            <?php foreach ($equipas as $eq): ?>
                <article class="cartao rev">
                    <div class="faixa"></div>
                    <div class="cartao-corpo">
                        <div class="sigla"><?= e($eq['sigla'] ?? '') ?></div>
                        <h3><?= e($eq['nome']) ?></h3>
                        <span class="chip"><?= e($eq['competicao']) ?></span>
                        <p><?= e($eq['descricao']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="secao alt">
    <div class="wrap">
        <div class="sec-topo"><h2>Últimas notícias</h2><a class="ver-mais" href="noticias.php">Todas as notícias <i class="fa-solid fa-arrow-right"></i></a></div>
        <div class="grelha g3">
            <?php foreach ($noticias as $n): ?>
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

<section class="secao">
    <div class="wrap">
        <div class="cta rev">
            <div>
                <h2>Queres fazer parte do clube?</h2>
                <p>Descobre os escalões, pede informações sobre os treinos e dá o primeiro passo para fazer parte da família Cohaemato.</p>
            </div>
            <a class="btn btn-ouro" href="inscricoes.php"><i class="fa-solid fa-user-plus"></i> Quero inscrever-me</a>
        </div>
    </div>
</section>
<?php render_footer();
