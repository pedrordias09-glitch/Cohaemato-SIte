<?php
require_once __DIR__ . '/includes/functions.php';

$msg = null; $tipo = '';
$v = ['nome_atleta'=>'','data_nascimento'=>'','escalao_interesse'=>'','nome_encarregado'=>'','email'=>'','telefone'=>'','mensagem'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) $v[$k] = trim((string)($_POST[$k] ?? ''));
    if (!csrf_ok($_POST['csrf'] ?? null)) { [$msg,$tipo] = ['A sessão expirou. Atualize a página e tente novamente.','erro']; }
    elseif (!empty($_POST['website'])) { [$msg,$tipo] = ['Pedido recebido. Obrigado pelo interesse!','ok']; $v = array_map(fn()=>'', $v); }
    elseif ($v['nome_atleta']==='' || $v['nome_encarregado']==='' || $v['escalao_interesse']==='' || $v['telefone']==='' || !filter_var($v['email'], FILTER_VALIDATE_EMAIL)) { [$msg,$tipo] = ['Preencha os campos obrigatórios e indique um email válido.','erro']; }
    elseif (mb_strlen($v['nome_atleta'])>120 || mb_strlen($v['nome_encarregado'])>120 || mb_strlen($v['telefone'])>30 || mb_strlen($v['mensagem'])>2000 || ($v['data_nascimento']!=='' && !preg_match('/^\d{4}-\d{2}-\d{2}$/',$v['data_nascimento']))) { [$msg,$tipo] = ['Verifique os dados introduzidos.','erro']; }
    elseif (!db_ativa()) { [$msg,$tipo] = ['O formulário está preparado, mas o registo só fica guardado depois de ativar a base de dados. Contacte o clube por email entretanto.','info']; }
    elseif (guardar_inscricao($v)) { [$msg,$tipo] = ['Pedido enviado. A equipa do clube entrará em contacto.','ok']; $v = array_map(fn()=>'', $v); }
    else { [$msg,$tipo] = ['Não foi possível guardar o pedido. Tente novamente mais tarde ou contacte o clube por email.','erro']; }
}
render_header('Inscrições', 'Demonstra o teu interesse em treinar futsal no GDC Cohaemato.');
render_hero_pagina('Vem jogar connosco', 'Deixa os teus dados e a equipa do clube entrará em contacto para explicar os próximos passos.');
?>
<section class="secao"><div class="wrap inscricao-layout">
    <div class="inscricao-intro"><span class="eyebrow">Formação Cohaemato</span><h2>O próximo capítulo começa aqui.</h2><p>Preenche o formulário para pedir informações sobre treinos, escalões e disponibilidade. O envio deste formulário não garante inscrição nem lugar numa equipa.</p><div class="inscricao-nota"><i class="fa-solid fa-shield-halved"></i><span>Usaremos os dados apenas para responder ao teu pedido. Não incluas informação pessoal desnecessária.</span></div><p><strong>Preferes falar diretamente?</strong><br><a href="mailto:<?= e(CLUBE['email']) ?>"><?= e(CLUBE['email']) ?></a></p></div>
    <div class="painel inscricao-form"><h2>Pedido de informações</h2>
    <?php if ($msg): ?><div class="aviso <?= e($tipo) ?>" role="status"><?= e($msg) ?></div><?php endif; ?>
    <?php if (!db_ativa()): ?><div class="aviso info">Modo demonstração: os pedidos não são guardados enquanto a base de dados estiver desligada.</div><?php endif; ?>
    <form method="post" action="inscricoes.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><div class="hp" aria-hidden="true"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></div>
    <div class="campo"><label for="nome_atleta">Nome do atleta *</label><input id="nome_atleta" name="nome_atleta" required maxlength="120" autocomplete="name" value="<?= e($v['nome_atleta']) ?>"></div>
    <div class="campo"><label for="data_nascimento">Data de nascimento (opcional)</label><input id="data_nascimento" type="date" name="data_nascimento" value="<?= e($v['data_nascimento']) ?>"></div>
    <div class="campo"><label for="escalao_interesse">Escalão pretendido *</label><select id="escalao_interesse" name="escalao_interesse" required><option value="">Selecionar escalão</option><?php foreach (['Petizes','Traquinas','Benjamins','Infantis','Iniciados','Juvenis','Juniores','Seniores','Não sei / preciso de orientação'] as $esc): ?><option value="<?= e($esc) ?>"<?= $v['escalao_interesse']===$esc?' selected':'' ?>><?= e($esc) ?></option><?php endforeach; ?></select></div>
    <div class="campo"><label for="nome_encarregado">Nome do encarregado de educação *</label><input id="nome_encarregado" name="nome_encarregado" required maxlength="120" autocomplete="name" value="<?= e($v['nome_encarregado']) ?>"></div>
    <div class="campo"><label for="email">Email de contacto *</label><input id="email" type="email" name="email" required maxlength="160" autocomplete="email" value="<?= e($v['email']) ?>"></div>
    <div class="campo"><label for="telefone">Telefone *</label><input id="telefone" type="tel" name="telefone" required maxlength="30" autocomplete="tel" value="<?= e($v['telefone']) ?>"></div>
    <div class="campo"><label for="mensagem">Mensagem (opcional)</label><textarea id="mensagem" name="mensagem" maxlength="2000" placeholder="Alguma informação que queiras partilhar…"><?= e($v['mensagem']) ?></textarea></div>
    <p class="peq">* Campos obrigatórios. Ao enviar, autorizas o clube a contactar-te sobre este pedido.</p><button class="btn btn-azul" type="submit"><i class="fa-solid fa-paper-plane"></i> Enviar pedido</button></form></div>
</div></section><?php render_footer();
