<?php
require_once __DIR__ . '/includes/functions.php';

$msg = null; $tipo = '';
$v = ['nome' => '', 'email' => '', 'assunto' => '', 'mensagem' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) {
        $v[$k] = trim((string)($_POST[$k] ?? ''));
    }
    if (!csrf_ok($_POST['csrf'] ?? null)) {
        [$msg, $tipo] = ['Sessão expirada. Atualize a página e tente novamente.', 'erro'];
    } elseif (!empty($_POST['website'])) {
        // honeypot: finge sucesso para não dar pistas a bots
        [$msg, $tipo] = ['Mensagem enviada. Obrigado!', 'ok'];
        $v = array_map(fn() => '', $v);
    } elseif ($v['nome'] === '' || $v['assunto'] === '' || $v['mensagem'] === '' || !filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        [$msg, $tipo] = ['Preencha todos os campos com um email válido.', 'erro'];
    } elseif (mb_strlen($v['nome']) > 120 || mb_strlen($v['assunto']) > 160 || mb_strlen($v['mensagem']) > 5000) {
        [$msg, $tipo] = ['Um dos campos excede o tamanho permitido.', 'erro'];
    } elseif (!db_ativa()) {
        [$msg, $tipo] = ['Modo demonstração: a base de dados ainda não está ligada, por isso a mensagem não foi guardada.', 'info'];
    } elseif (guardar_mensagem($v)) {
        [$msg, $tipo] = ['Mensagem enviada. Obrigado, respondemos em breve!', 'ok'];
        $v = array_map(fn() => '', $v);
    } else {
        [$msg, $tipo] = ['Não foi possível guardar a mensagem. Tente novamente mais tarde.', 'erro'];
    }
}

render_header('Contacto', 'Contacte o GDC Cohaemato: morada, email e formulário de mensagem.');
render_hero_pagina('Contacto', 'Tens dúvidas, queres inscrever-te ou apoiar o clube? Escreve-nos.');
?>
<section class="secao">
    <div class="wrap">
        <div class="contacto-grid">
            <div class="painel rev">
                <h2>Informações</h2>
                <div class="info-item"><i class="fa-solid fa-location-dot"></i><div><b>Morada</b><?= e(CLUBE['pavilhao']) ?><br><?= e(CLUBE['morada']) ?><br><?= e(CLUBE['codigo']) ?></div></div>
                <div class="info-item"><i class="fa-solid fa-envelope"></i><div><b>Email</b><a href="mailto:<?= e(CLUBE['email']) ?>"><?= e(CLUBE['email']) ?></a></div></div>
                <?php if (CLUBE['telefone']): ?>
                    <div class="info-item"><i class="fa-solid fa-phone"></i><div><b>Telefone</b><?= e(CLUBE['telefone']) ?></div></div>
                <?php endif; ?>
                <div class="info-item"><i class="fa-solid fa-globe"></i><div><b>Site oficial</b><a href="<?= e(CLUBE['site_oficial']) ?>" target="_blank" rel="noopener">gdccohaemato.com</a></div></div>
            </div>

            <div class="painel rev">
                <h2>Envia uma mensagem</h2>
                <?php if ($msg): ?><div class="aviso <?= e($tipo) ?>" role="status"><?= e($msg) ?></div><?php endif; ?>
                <form method="post" action="contacto.php" novalidate>
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                    <div class="campo"><label for="nome">Nome</label><input id="nome" name="nome" required maxlength="120" value="<?= e($v['nome']) ?>" autocomplete="name"></div>
                    <div class="campo"><label for="email">Email</label><input id="email" type="email" name="email" required maxlength="160" value="<?= e($v['email']) ?>" autocomplete="email"></div>
                    <div class="campo"><label for="assunto">Assunto</label><input id="assunto" name="assunto" required maxlength="160" value="<?= e($v['assunto']) ?>"></div>
                    <div class="campo"><label for="mensagem">Mensagem</label><textarea id="mensagem" name="mensagem" required maxlength="5000"><?= e($v['mensagem']) ?></textarea></div>
                    <button class="btn btn-azul" type="submit"><i class="fa-solid fa-paper-plane"></i> Enviar mensagem</button>
                </form>
            </div>
        </div>

        <div class="mapa rev">
            <iframe title="Mapa – Leça da Palmeira" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps?q=<?= urlencode('Rua Luís José Alves, 4450-719 Leça da Palmeira') ?>&output=embed"></iframe>
        </div>
    </div>
</section>
<?php render_footer();
