<?php
require_once dirname(__DIR__) . '/includes/functions.php';

// Configure ADMIN_USER and ADMIN_PASSWORD_HASH as environment variables before deployment.
$adminUser = getenv('ADMIN_USER') ?: '';
$adminHash = getenv('ADMIN_PASSWORD_HASH') ?: '';
$pdo = db();
$erro = ''; $sucesso = '';

if (isset($_GET['sair'])) {
    unset($_SESSION['admin_autenticado']);
    session_regenerate_id(true);
    header('Location: index.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'login') {
    if (!csrf_ok($_POST['csrf'] ?? null)) $erro = 'Sessão expirada. Atualize a página.';
    elseif ($adminUser === '' || $adminHash === '') $erro = 'A administração ainda não está configurada. Consulte o README para definir ADMIN_USER e ADMIN_PASSWORD_HASH.';
    elseif (hash_equals($adminUser, (string)($_POST['utilizador'] ?? '')) && password_verify((string)($_POST['password'] ?? ''), $adminHash)) {
        session_regenerate_id(true);
        $_SESSION['admin_autenticado'] = true;
        header('Location: index.php'); exit;
    } else { usleep(350000); $erro = 'Credenciais inválidas.'; }
}

$autenticado = !empty($_SESSION['admin_autenticado']);

/* ---------- Estilos e scripts partilhados ---------- */
function admin_head(string $titulo): void { ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($titulo) ?> · GDC Cohaemato</title>
<script>try{var t=localStorage.getItem('adm-tema');if(t)document.documentElement.setAttribute('data-tema',t);}catch(e){}</script>
<style>
:root{--azul:#16a34a;--azul-2:#166534;--azul-claro:#dcfce7;--bg:#f0fdf4;--card:#fff;--txt:#14291d;--mut:#5f7a69;--borda:#d1e7d9;--ok:#15803d;--ok-bg:#bbf7d0;--err:#b91c1c;--err-bg:#fee2e2;--warn:#b45309;--warn-bg:#fef3c7;--info-bg:#e6f6ec;--raio:14px;--sombra:0 1px 2px rgba(20,41,29,.06),0 4px 16px rgba(20,41,29,.05)}
:root[data-tema=escuro]{--bg:#07150d;--card:#0e2217;--txt:#e3f2e8;--mut:#8fb09b;--borda:#1c3a29;--azul:#22a55a;--azul-2:#0b3a20;--azul-claro:#143923;--ok:#4ade80;--ok-bg:#12351f;--err:#fca5a5;--err-bg:#3b1414;--warn:#fcd34d;--warn-bg:#3a2a0c;--info-bg:#0f2f1d;--sombra:0 1px 2px rgba(0,0,0,.4)}
*{box-sizing:border-box}html{scroll-behavior:smooth}
body{margin:0;font:15px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;background:var(--bg);color:var(--txt)}
a{color:var(--azul)}h1,h2,h3{margin:0;line-height:1.25}h1{font-size:1.6rem}h2{font-size:1.15rem}h3{font-size:1rem}
.peq{font-size:.85rem;color:var(--mut)}.eyebrow{font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:var(--azul);font-weight:700;margin:0 0 4px}
.marca{display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit}.marca img{border-radius:10px;background:#fff}.marca strong{display:block;font-size:1.05rem}.marca small{color:var(--mut);font-size:.78rem}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 16px;border-radius:10px;border:1px solid transparent;font:inherit;font-weight:600;cursor:pointer;text-decoration:none;transition:.15s}
.btn-azul{background:var(--azul);color:#fff}.btn-azul:hover{filter:brightness(1.1)}.btn-azul[disabled]{opacity:.5;cursor:not-allowed}
.btn-linha{background:transparent;border-color:var(--borda);color:var(--txt)}.btn-linha:hover{background:var(--azul-claro)}
.btn-small{padding:6px 11px;font-size:.82rem;border-radius:8px;border:1px solid var(--borda);background:var(--card);color:var(--txt);font-weight:600;cursor:pointer;font-family:inherit}.btn-small:hover{background:var(--azul-claro)}
.btn-small.perigo{color:var(--err);border-color:transparent;background:var(--err-bg)}.btn-small.perigo:hover{filter:brightness(.96)}
.campo{margin-bottom:14px}.campo label{display:block;font-weight:600;font-size:.85rem;margin-bottom:5px}
input,select,textarea{width:100%;padding:10px 12px;border:1px solid var(--borda);border-radius:10px;background:var(--card);color:var(--txt);font:inherit}
input:focus,select:focus,textarea:focus{outline:2px solid var(--azul);outline-offset:1px;border-color:transparent}
input[type=checkbox]{width:auto}textarea{resize:vertical;min-height:90px}
.aviso{padding:12px 14px;border-radius:10px;margin:0 0 14px;font-size:.92rem}.aviso.erro{background:var(--err-bg);color:var(--err)}.aviso.ok{background:var(--ok-bg);color:var(--ok)}.aviso.info{background:var(--info-bg)}
.painel{background:var(--card);border:1px solid var(--borda);border-radius:var(--raio);box-shadow:var(--sombra);padding:20px}
.badge{display:inline-block;padding:2px 10px;border-radius:999px;font-size:.75rem;font-weight:700;background:var(--azul-claro);color:var(--azul)}
.badge.ok{background:var(--ok-bg);color:var(--ok)}.badge.warn{background:var(--warn-bg);color:var(--warn)}.badge.cinza{background:var(--borda);color:var(--mut)}
/* login */
.login-wrap{min-height:100vh;display:grid;place-items:center;padding:20px;background:linear-gradient(135deg,var(--azul-2),var(--azul))}
.login-card{width:100%;max-width:400px}.login-card .marca{color:#fff;justify-content:center;margin-bottom:18px}.login-card .marca small{color:#cbd5e1}
.login-card .painel{padding:28px}.pw-box{position:relative}.pw-box button{position:absolute;right:6px;top:50%;transform:translateY(-50%);border:0;background:none;color:var(--mut);cursor:pointer;font-weight:600;padding:6px 8px}
.login-card .btn{width:100%;margin-top:6px}.login-card .voltar{text-align:center;margin:16px 0 0}
/* layout */
.app{display:grid;grid-template-columns:250px 1fr;min-height:100vh}
.side{background:var(--card);border-right:1px solid var(--borda);padding:20px 14px;position:sticky;top:0;height:100vh;display:flex;flex-direction:column;gap:18px}
.side nav{display:flex;flex-direction:column;gap:4px}
.nav-btn{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;border:0;background:none;color:var(--txt);font:inherit;font-weight:600;cursor:pointer;text-align:left;width:100%}
.nav-btn:hover{background:var(--azul-claro)}.nav-btn.ativo{background:var(--azul);color:#fff}
.nav-btn .ic{width:22px;text-align:center}.nav-btn .badge{margin-left:auto}.nav-btn.ativo .badge{background:#fff;color:var(--azul)}
.side-foot{margin-top:auto;display:flex;flex-direction:column;gap:8px}
.main{padding:28px;max-width:1200px;width:100%}
.topo{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:20px}
.sec{display:none}.sec.ativa{display:block;animation:fade .2s}@keyframes fade{from{opacity:0;transform:translateY(4px)}to{opacity:1}}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px;margin-bottom:20px}
.stat{cursor:pointer;transition:.15s;text-align:left;font:inherit;color:inherit}.stat:hover{transform:translateY(-2px);border-color:var(--azul)}
.stat b{display:block;font-size:2rem;line-height:1}.stat span{color:var(--mut);font-size:.88rem}
.grid2{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px;align-items:start}
.cab-lista{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px}
.pesquisa{max-width:260px}.chips{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px}
.chip{padding:5px 13px;border-radius:999px;border:1px solid var(--borda);background:var(--card);color:var(--txt);font:inherit;font-size:.82rem;font-weight:600;cursor:pointer}
.chip.ativo{background:var(--azul);color:#fff;border-color:var(--azul)}
.table-scroll{overflow-x:auto}table{width:100%;border-collapse:collapse}th{text-align:left;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:var(--mut);padding:8px 10px;border-bottom:1px solid var(--borda)}
td{padding:11px 10px;border-bottom:1px solid var(--borda);vertical-align:middle}tr:last-child td{border-bottom:0}tbody tr:hover{background:var(--azul-claro)}
.inline{display:flex;gap:6px;align-items:center}.inline input[type=number]{width:62px;padding:6px 8px;text-align:center}.inline select{padding:6px 8px;width:auto}
.check{display:flex;align-items:center;gap:8px;margin-bottom:10px;font-weight:500;cursor:pointer}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.vazio{text-align:center;color:var(--mut);padding:28px 10px}.contador{font-size:.75rem;color:var(--mut);font-weight:500}
.slug-prev{font-size:.78rem;color:var(--mut);margin-top:4px;word-break:break-all}.cnt{float:right;font-weight:400;color:var(--mut)}
.lista-simples .item{display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid var(--borda)}.lista-simples .item:last-child{border:0}
.res{font-weight:800;font-variant-numeric:tabular-nums}
.proximo{display:flex;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid var(--borda)}.proximo:last-child{border:0}
.data-box{min-width:54px;text-align:center;background:var(--azul-claro);color:var(--azul);border-radius:10px;padding:6px;font-weight:800;font-size:.8rem}
.toasts{position:fixed;right:16px;bottom:16px;display:flex;flex-direction:column;gap:8px;z-index:50}
.toast{padding:12px 16px;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.2);font-weight:600;max-width:340px;animation:fade .25s}.toast.ok{background:var(--ok);color:#fff}.toast.erro{background:var(--err);color:#fff}
dialog{border:0;border-radius:var(--raio);padding:24px;max-width:380px;background:var(--card);color:var(--txt);box-shadow:0 20px 60px rgba(0,0,0,.35)}dialog::backdrop{background:rgba(15,23,42,.55)}
dialog .acoes{display:flex;gap:8px;justify-content:flex-end;margin-top:18px}
[hidden]{display:none!important}
.badge.erro{background:var(--err-bg);color:var(--err)}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:0 14px}
.jogos-lista{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px}
.jogo{border:1px solid var(--borda);border-left:5px solid var(--azul);border-radius:var(--raio);padding:14px;background:var(--card);display:flex;flex-direction:column;gap:14px}
.jogo.v{border-left-color:var(--ok)}.jogo.e{border-left-color:var(--warn)}.jogo.d{border-left-color:var(--err)}
.jogo-top{display:flex;justify-content:space-between;gap:8px;align-items:flex-start}.jogo-data{font-weight:700}
.jogo-badges{display:flex;gap:4px;flex-wrap:wrap;justify-content:flex-end}
.placar{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:6px;margin-bottom:12px}
.equipa-p{display:flex;flex-direction:column;align-items:center;gap:8px;text-align:center;min-width:0}
.equipa-p .nome{font-size:.82rem;font-weight:700;overflow-wrap:anywhere}
.step{display:flex;flex-direction:column;align-items:center;gap:4px}
.step button{width:58px;height:34px;border-radius:10px;border:1px solid var(--borda);background:var(--card);color:var(--txt);font-size:1.25rem;font-weight:700;cursor:pointer;line-height:1;font-family:inherit}
.step button:hover{background:var(--azul-claro);border-color:var(--azul)}.step button:active{transform:scale(.94)}
.step input{width:58px;height:58px;text-align:center;font-size:1.8rem;font-weight:800;padding:0;-moz-appearance:textfield;appearance:textfield}
.step input::-webkit-outer-spin-button,.step input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.sep{font-size:1.6rem;font-weight:800;color:var(--mut);padding-top:34px}
.placar-form .salvar{width:100%}
.jogo-del{display:flex;justify-content:flex-end}
.mobile-bar{display:none}
@media(max-width:860px){
 .app{grid-template-columns:1fr}.side{display:none}
 .mobile-bar{display:block;position:sticky;top:0;z-index:20;background:var(--card);border-bottom:1px solid var(--borda)}
 .mobile-top{display:flex;justify-content:space-between;align-items:center;padding:10px 14px}
 .mobile-nav{display:flex;overflow-x:auto;gap:4px;padding:0 10px 8px}.mobile-nav .nav-btn{width:auto;white-space:nowrap;padding:8px 12px}
 .main{padding:16px}.row2{grid-template-columns:1fr}
}
</style>
</head>
<?php }

function old(string $k, string $def = ''): string { return e((string)($_POST[$k] ?? $def)); }

/* ---------- Ecrã de login ---------- */
if (!$autenticado) {
    admin_head('Administração'); ?>
<body>
<div class="login-wrap"><div class="login-card">
  <a class="marca" href="../index.php"><img src="../assets/img/logo.png" width="52" height="52" alt=""><span><strong>Cohaemato</strong><small>Área de administração</small></span></a>
  <section class="painel">
    <p class="eyebrow">Área reservada</p><h1>Entrar</h1>
    <p class="peq" style="margin:6px 0 18px">Acesso exclusivo à equipa autorizada do clube.</p>
    <?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>
    <?php if (!$pdo): ?><div class="aviso info">A base de dados está desligada. A autenticação e a gestão de conteúdos só ficam disponíveis depois de configurar a base de dados.</div><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="login">
      <div class="campo"><label for="utilizador">Utilizador</label><input id="utilizador" name="utilizador" required autocomplete="username" autofocus></div>
      <div class="campo"><label for="password">Palavra-passe</label>
        <div class="pw-box"><input id="password" type="password" name="password" required autocomplete="current-password"><button type="button" id="verpw">Mostrar</button></div></div>
      <button class="btn btn-azul" type="submit" <?= !$pdo ? 'disabled' : '' ?>>Entrar na administração</button>
    </form>
    <p class="peq voltar"><a href="../index.php">← Voltar ao site</a></p>
  </section>
</div></div>
<script>
document.getElementById('verpw').addEventListener('click',function(){var i=document.getElementById('password'),m=i.type==='password';i.type=m?'text':'password';this.textContent=m?'Esconder':'Mostrar';});
</script>
</body></html>
<?php
    exit;
}

/* ---------- Mensagem guardada após redirecionamento ---------- */
if (!empty($_SESSION['admin_flash'])) { $sucesso = (string)$_SESSION['admin_flash']; unset($_SESSION['admin_flash']); }
$secaoRetorno = 'geral';

if (!$pdo) { $erro = 'Base de dados indisponível. Confirma a configuração em includes/config.php.'; }
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') !== 'login') {
    if (!csrf_ok($_POST['csrf'] ?? null)) $erro = 'Sessão expirada. Atualize a página e tente novamente.';
    else {
        $acao = (string)($_POST['acao'] ?? '');
        try {
            if ($acao === 'noticia') {
                $secaoRetorno = 'noticias';
                $titulo = trim((string)($_POST['titulo'] ?? '')); $resumo = trim((string)($_POST['resumo'] ?? '')); $conteudo = trim((string)($_POST['conteudo'] ?? ''));
                $slug = trim((string)($_POST['slug'] ?? ''));
                if ($slug === '') { $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $titulo) ?: $titulo; $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $slug), '-')); }
                if ($titulo === '' || $resumo === '' || $conteudo === '' || $slug === '') throw new RuntimeException('Preencha o título, resumo e conteúdo da notícia.');
                $st = $pdo->prepare('INSERT INTO noticias (slug,titulo,resumo,conteudo,data_publicacao,destaque,publicada) VALUES (?,?,?,?,?,?,?)');
                $st->execute([$slug,mb_substr($titulo,0,200),mb_substr($resumo,0,400),$conteudo,($_POST['data_publicacao'] ?? '') ?: date('Y-m-d'),isset($_POST['destaque'])?1:0,isset($_POST['publicada'])?1:0]);
                $sucesso = 'Notícia criada.';
            } elseif ($acao === 'jogo') {
                $secaoRetorno = 'jogos';
                $st = $pdo->prepare('INSERT INTO jogos (escalao,data_jogo,hora,adversario,em_casa,golos_cohaemato,golos_adversario,competicao,local) VALUES (?,?,?,?,?,?,?,?,?)');
                $g1 = trim((string)($_POST['golos_cohaemato'] ?? '')); $g2 = trim((string)($_POST['golos_adversario'] ?? ''));
                if ($g1 !== '' && (!ctype_digit($g1) || (int)$g1 > 99)) throw new RuntimeException('O resultado deve ser um número entre 0 e 99.');
                if ($g2 !== '' && (!ctype_digit($g2) || (int)$g2 > 99)) throw new RuntimeException('O resultado deve ser um número entre 0 e 99.');
                if (($g1 === '') !== ($g2 === '')) throw new RuntimeException('Preencha os dois golos ou deixe ambos vazios para um jogo futuro.');
                $st->execute([trim((string)$_POST['escalao']),$_POST['data_jogo'],($_POST['hora'] ?? '') ?: null,trim((string)$_POST['adversario']),isset($_POST['em_casa'])?1:0,$g1===''?null:(int)$g1,$g2===''?null:(int)$g2,trim((string)$_POST['competicao']),trim((string)($_POST['local'] ?? '')) ?: null]);
                $sucesso = 'Jogo adicionado ao calendário.';
            } elseif ($acao === 'atualizar_resultado') {
                $secaoRetorno = 'jogos';
                $g1 = trim((string)($_POST['golos_cohaemato'] ?? '')); $g2 = trim((string)($_POST['golos_adversario'] ?? ''));
                if ($g1 === '' || $g2 === '' || !ctype_digit($g1) || !ctype_digit($g2) || (int)$g1 > 99 || (int)$g2 > 99) throw new RuntimeException('Indique os dois resultados entre 0 e 99.');
                $st = $pdo->prepare('UPDATE jogos SET golos_cohaemato=?, golos_adversario=? WHERE id=?');
                $st->execute([(int)$g1,(int)$g2,(int)($_POST['id'] ?? 0)]); $sucesso = 'Resultado atualizado.';
            } elseif ($acao === 'apagar_noticia' || $acao === 'apagar_jogo') {
                $secaoRetorno = $acao === 'apagar_noticia' ? 'noticias' : 'jogos';
                $tabela = $acao === 'apagar_noticia' ? 'noticias' : 'jogos';
                $st = $pdo->prepare("DELETE FROM {$tabela} WHERE id = ?"); $st->execute([(int)($_POST['id'] ?? 0)]); $sucesso = 'Registo eliminado.';
            } elseif ($acao === 'estado_inscricao') {
                $secaoRetorno = 'inscricoes';
                $estado = (string)($_POST['estado'] ?? 'nova');
                if (!in_array($estado, ['nova','contactada','concluida'], true)) throw new RuntimeException('Estado inválido.');
                $st = $pdo->prepare('UPDATE inscricoes SET estado=? WHERE id=?'); $st->execute([$estado,(int)($_POST['id'] ?? 0)]); $sucesso = 'Estado da inscrição atualizado.';
            }
        } catch (RuntimeException $ex) { $erro = $ex->getMessage(); }
          catch (PDOException $ex) { error_log('Admin Cohaemato: '.$ex->getMessage()); $erro = $ex->getCode() === '23000' ? 'Já existe uma notícia com esse endereço (slug). Escolha outro.' : 'Não foi possível guardar a alteração. Verifique os campos e a base de dados.'; }

        // Sucesso: redireciona (evita reenvio ao atualizar a página) e volta à mesma secção.
        if ($sucesso !== '' && $erro === '') {
            $_SESSION['admin_flash'] = $sucesso;
            header('Location: index.php#' . $secaoRetorno); exit;
        }
    }
}

function admin_rows(PDO $pdo, string $sql): array { try { return $pdo->query($sql)->fetchAll(); } catch (PDOException $e) { error_log('Admin Cohaemato listagem: '.$e->getMessage()); return []; } }
$noticias = $pdo ? admin_rows($pdo, 'SELECT id,titulo,slug,data_publicacao,publicada,destaque FROM noticias ORDER BY id DESC LIMIT 12') : [];
$jogos = $pdo ? admin_rows($pdo, 'SELECT id,escalao,data_jogo,hora,adversario,em_casa,competicao,golos_cohaemato,golos_adversario FROM jogos ORDER BY data_jogo DESC,id DESC') : [];
$inscricoes = $pdo ? admin_rows($pdo, 'SELECT id,nome_atleta,escalao_interesse,nome_encarregado,email,telefone,estado,criada_em FROM inscricoes ORDER BY criada_em DESC LIMIT 30') : [];
$mensagens = $pdo ? admin_rows($pdo, 'SELECT id,nome,email,assunto,criada_em,lida FROM mensagens ORDER BY criada_em DESC LIMIT 15') : [];
$equipas = $pdo ? admin_rows($pdo, 'SELECT nome,escalao,ativa FROM equipas ORDER BY ordem,nome') : [];

$hoje = date('Y-m-d');
$porJogar = array_values(array_filter($jogos, fn($j) => $j['golos_cohaemato'] === null));
$realizados = array_values(array_filter($jogos, fn($j) => $j['golos_cohaemato'] !== null));
usort($porJogar, fn($a, $b) => strcmp($a['data_jogo'].($a['hora'] ?? ''), $b['data_jogo'].($b['hora'] ?? '')));
$proximos = array_slice(array_values(array_filter($porJogar, fn($j) => $j['data_jogo'] >= $hoje)), 0, 4);
$novas = count(array_filter($inscricoes, fn($i) => $i['estado'] === 'nova'));
$naoLidas = count(array_filter($mensagens, fn($m) => empty($m['lida'])));
$estadoLabel = ['nova' => 'Nova', 'contactada' => 'Contactada', 'concluida' => 'Concluída'];
$estadoClasse = ['nova' => 'warn', 'contactada' => '', 'concluida' => 'ok'];

function placar_lado(string $nome, string $campo, ?int $valor): void { ?>
    <div class="equipa-p">
      <span class="nome"><?= e($nome) ?></span>
      <div class="step">
        <button type="button" data-d="1" aria-label="Mais um golo: <?= e($nome) ?>">+</button>
        <input type="number" inputmode="numeric" min="0" max="99" required name="<?= e($campo) ?>" value="<?= $valor === null ? '' : $valor ?>" placeholder="–" aria-label="Golos: <?= e($nome) ?>">
        <button type="button" data-d="-1" aria-label="Menos um golo: <?= e($nome) ?>">−</button>
      </div>
    </div>
<?php }

function cartao_jogo(array $j, string $hoje): void {
    $feito = $j['golos_cohaemato'] !== null;
    $gc = $feito ? (int)$j['golos_cohaemato'] : null;
    $ga = $feito ? (int)$j['golos_adversario'] : null;
    $classe = 'p'; $res = '';
    if ($feito) { $classe = $gc > $ga ? 'v' : ($gc < $ga ? 'd' : 'e'); $res = $gc > $ga ? 'Vitória' : ($gc < $ga ? 'Derrota' : 'Empate'); }
    $atrasado = !$feito && $j['data_jogo'] < $hoje;
    $casa = !empty($j['em_casa']);
    $orig = $feito ? $gc . ',' . $ga : '';
    ?>
<article class="jogo <?= $classe ?>" data-g="<?= $feito ? 'feito' : 'futuro' ?>" data-q="<?= e(mb_strtolower($j['adversario'].' '.$j['escalao'].' '.($j['competicao'] ?? ''))) ?>">
  <div class="jogo-top">
    <div><div class="jogo-data"><?= e(data_pt($j['data_jogo'],true)) ?><?= $j['hora'] ? ' · ' . e(substr((string)$j['hora'],0,5)) : '' ?></div>
      <div class="peq"><?= e($j['escalao']) ?><?= !empty($j['competicao']) ? ' · ' . e((string)$j['competicao']) : '' ?></div></div>
    <div class="jogo-badges"><span class="badge cinza"><?= $casa ? 'Casa' : 'Fora' ?></span>
      <?php if ($feito): ?><span class="badge <?= $classe === 'v' ? 'ok' : ($classe === 'e' ? 'warn' : 'erro') ?>"><?= $res ?></span>
      <?php elseif ($atrasado): ?><span class="badge warn">Falta resultado</span>
      <?php else: ?><span class="badge">Por realizar</span><?php endif; ?></div>
  </div>
  <form method="post" class="placar-form" data-orig="<?= e($orig) ?>">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="atualizar_resultado"><input type="hidden" name="id" value="<?= (int)$j['id'] ?>">
    <div class="placar">
      <?php if ($casa) { placar_lado('Cohaemato', 'golos_cohaemato', $gc); } else { placar_lado((string)$j['adversario'], 'golos_adversario', $ga); } ?>
      <span class="sep">:</span>
      <?php if ($casa) { placar_lado((string)$j['adversario'], 'golos_adversario', $ga); } else { placar_lado('Cohaemato', 'golos_cohaemato', $gc); } ?>
    </div>
    <button class="btn btn-azul salvar" type="submit" disabled><?= $feito ? 'Guardar alterações' : 'Registar resultado' ?></button>
  </form>
  <form method="post" class="jogo-del" data-confirm="Eliminar o jogo contra <?= e($j['adversario']) ?>?">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="apagar_jogo"><input type="hidden" name="id" value="<?= (int)$j['id'] ?>">
    <button class="btn-small perigo" type="submit">Eliminar jogo</button></form>
</article>
<?php }

admin_head('Painel de gestão'); ?>
<body>
<?php
$nav = [
  'geral' => ['🏠','Visão geral',0],
  'noticias' => ['📰','Notícias',0],
  'jogos' => ['⚽','Jogos',count($porJogar)],
  'inscricoes' => ['📝','Inscrições',$novas],
  'mensagens' => ['✉️','Mensagens',$naoLidas],
  'equipas' => ['👥','Equipas',0],
];
?>
<div class="mobile-bar">
  <div class="mobile-top"><a class="marca" href="../index.php"><img src="../assets/img/logo.png" width="34" height="34" alt=""><span><strong>Cohaemato</strong></span></a>
    <div style="display:flex;gap:6px"><button class="btn-small tema-btn" type="button">🌓</button><a class="btn-small" href="?sair=1">Sair</a></div></div>
  <div class="mobile-nav"><?php foreach ($nav as $k => $n): ?><button type="button" class="nav-btn" data-sec="<?= $k ?>"><span class="ic"><?= $n[0] ?></span><?= e($n[1]) ?><?php if ($n[2]): ?><span class="badge"><?= (int)$n[2] ?></span><?php endif; ?></button><?php endforeach; ?></div>
</div>

<div class="app">
<aside class="side">
  <a class="marca" href="../index.php"><img src="../assets/img/logo.png" width="42" height="42" alt=""><span><strong>Cohaemato</strong><small>Gestão do clube</small></span></a>
  <nav><?php foreach ($nav as $k => $n): ?><button type="button" class="nav-btn" data-sec="<?= $k ?>"><span class="ic"><?= $n[0] ?></span><?= e($n[1]) ?><?php if ($n[2]): ?><span class="badge"><?= (int)$n[2] ?></span><?php endif; ?></button><?php endforeach; ?></nav>
  <div class="side-foot">
    <a class="btn btn-linha" href="../index.php" target="_blank" rel="noopener">Ver site ↗</a>
    <button class="btn btn-linha tema-btn" type="button">🌓 Tema claro/escuro</button>
    <a class="btn btn-linha" href="?sair=1">Terminar sessão</a>
  </div>
</aside>

<main class="main">
<?php if ($erro): ?><div class="aviso erro" role="alert"><?= e($erro) ?></div><?php endif; ?>
<?php if (!$pdo): ?><div class="aviso info">Sem ligação à base de dados, as listas aparecem vazias e não é possível guardar alterações.</div><?php endif; ?>

<!-- ============ VISÃO GERAL ============ -->
<section class="sec" id="sec-geral">
  <div class="topo"><div><p class="eyebrow">Área reservada</p><h1>Painel de gestão</h1><p class="peq">Olá! Aqui tens o resumo do clube e atalhos para as tarefas mais comuns.</p></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap"><button class="btn btn-azul" data-ir="noticias" data-foco="titulo">+ Nova notícia</button><button class="btn btn-azul" data-ir="jogos" data-foco="adversario">+ Novo jogo</button></div></div>
  <div class="stats">
    <button class="painel stat" data-ir="equipas"><b><?= count($equipas) ?></b><span>equipas</span></button>
    <button class="painel stat" data-ir="jogos"><b><?= count($porJogar) ?></b><span>jogos por realizar</span></button>
    <button class="painel stat" data-ir="noticias"><b><?= count($noticias) ?></b><span>notícias recentes</span></button>
    <button class="painel stat" data-ir="inscricoes"><b><?= $novas ?></b><span>inscrições novas <small>(de <?= count($inscricoes) ?>)</small></span></button>
    <button class="painel stat" data-ir="mensagens"><b><?= $naoLidas ?></b><span>mensagens por ler</span></button>
  </div>
  <div class="grid2">
    <div class="painel"><h2 style="margin-bottom:10px">Próximos jogos</h2>
      <?php if (!$proximos): ?><p class="vazio">Sem jogos agendados.</p><?php else: foreach ($proximos as $j): ?>
      <div class="proximo"><div class="data-box"><?= e(data_pt($j['data_jogo'],true)) ?></div><div><strong><?= e($j['escalao']) ?> vs <?= e($j['adversario']) ?></strong><br><span class="peq"><?= $j['hora'] ? e(substr((string)$j['hora'],0,5)).' · ' : '' ?><?= !empty($j['em_casa']) ? 'Em casa' : 'Fora' ?></span></div></div>
      <?php endforeach; endif; ?></div>
    <div class="painel"><h2 style="margin-bottom:10px">Últimos pedidos de inscrição</h2>
      <?php if (!$inscricoes): ?><p class="vazio">Sem pedidos.</p><?php else: foreach (array_slice($inscricoes,0,4) as $i): ?>
      <div class="proximo"><div style="flex:1"><strong><?= e($i['nome_atleta']) ?></strong><br><span class="peq"><?= e($i['escalao_interesse']) ?></span></div><span class="badge <?= $estadoClasse[$i['estado']] ?? 'cinza' ?>"><?= e($estadoLabel[$i['estado']] ?? $i['estado']) ?></span></div>
      <?php endforeach; endif; ?></div>
  </div>
</section>

<!-- ============ NOTÍCIAS ============ -->
<section class="sec" id="sec-noticias">
  <div class="topo"><div><p class="eyebrow">Conteúdos</p><h1>Notícias</h1></div></div>
  <div class="grid2">
    <div class="painel"><h2 style="margin-bottom:14px">Publicar notícia</h2>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="noticia">
        <div class="campo"><label for="titulo">Título *</label><input id="titulo" name="titulo" maxlength="200" required value="<?= old('titulo') ?>"></div>
        <div class="campo"><label for="slug">Endereço curto (opcional)</label><input id="slug" name="slug" maxlength="160" placeholder="gerado-a-partir-do-titulo" value="<?= old('slug') ?>"><div class="slug-prev" id="slugprev"></div></div>
        <div class="campo"><label for="resumo">Resumo * <span class="cnt" id="cnt-resumo">0/400</span></label><textarea id="resumo" name="resumo" maxlength="400" required><?= old('resumo') ?></textarea></div>
        <div class="campo"><label for="conteudo">Conteúdo *</label><textarea id="conteudo" name="conteudo" rows="7" required><?= old('conteudo') ?></textarea></div>
        <div class="campo"><label for="data_publicacao">Data de publicação</label><input id="data_publicacao" type="date" name="data_publicacao" value="<?= old('data_publicacao', date('Y-m-d')) ?>"></div>
        <label class="check"><input type="checkbox" name="publicada" <?= ($_SERVER['REQUEST_METHOD']!=='POST' || isset($_POST['publicada'])) ? 'checked' : '' ?>> Publicar imediatamente</label>
        <label class="check"><input type="checkbox" name="destaque" <?= isset($_POST['destaque']) ? 'checked' : '' ?>> Destacar notícia</label>
        <button class="btn btn-azul" type="submit">Guardar notícia</button>
      </form></div>
    <div class="painel"><div class="cab-lista"><h2>Notícias recentes</h2><input class="pesquisa filtro" data-alvo="tb-noticias" type="search" placeholder="Pesquisar…" aria-label="Pesquisar notícias"></div>
      <?php if (!$noticias): ?><p class="vazio">Ainda não existem notícias na base de dados.</p><?php else: ?>
      <div class="table-scroll"><table><thead><tr><th>Notícia</th><th>Data</th><th>Estado</th><th></th></tr></thead><tbody id="tb-noticias">
      <?php foreach ($noticias as $n): ?><tr data-q="<?= e(mb_strtolower($n['titulo'].' '.$n['slug'])) ?>">
        <td><strong><?= e($n['titulo']) ?></strong><?php if (!empty($n['destaque'])): ?> <span class="badge">★ Destaque</span><?php endif; ?></td>
        <td><?= e(data_pt($n['data_publicacao'],true)) ?></td>
        <td><span class="badge <?= $n['publicada']?'ok':'cinza' ?>"><?= $n['publicada']?'Publicada':'Rascunho' ?></span></td>
        <td><form method="post" data-confirm="Eliminar a notícia “<?= e($n['titulo']) ?>”?"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="apagar_noticia"><input type="hidden" name="id" value="<?= (int)$n['id'] ?>"><button class="btn-small perigo" type="submit">Eliminar</button></form></td></tr>
      <?php endforeach; ?></tbody></table></div><?php endif; ?></div>
  </div>
</section>

<!-- ============ JOGOS ============ -->
<?php $abrirForm = $erro !== '' && (($_POST['acao'] ?? '') === 'jogo'); ?>
<section class="sec" id="sec-jogos">
  <div class="topo"><div><p class="eyebrow">Calendário</p><h1>Jogos</h1><p class="peq">Usa os botões + e − para registar o marcador e depois guarda.</p></div>
    <button class="btn btn-azul" type="button" id="btn-novo-jogo" aria-expanded="<?= $abrirForm ? 'true' : 'false' ?>" aria-controls="painel-novo-jogo">+ Novo jogo</button></div>

  <div class="painel" id="painel-novo-jogo" style="margin-bottom:18px" <?= $abrirForm ? '' : 'hidden' ?>>
    <h2 style="margin-bottom:14px">Adicionar jogo</h2>
    <form method="post" id="form-jogo">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="jogo">
      <div class="form-grid">
        <div class="campo"><label for="escalao">Equipa / escalão *</label><select id="escalao" name="escalao" required>
          <?php foreach ($equipas as $eq): ?><option value="<?= e($eq['nome']) ?>" <?= (($_POST['escalao'] ?? '')===$eq['nome'])?'selected':'' ?>><?= e($eq['nome']) ?></option><?php endforeach; ?>
          <?php if (!$equipas): ?><option value="Seniores">Seniores</option><?php endif; ?></select></div>
        <div class="campo"><label for="adversario">Adversário *</label><input id="adversario" name="adversario" maxlength="120" required value="<?= old('adversario') ?>"></div>
        <div class="campo"><label for="data_jogo">Data *</label><input id="data_jogo" type="date" name="data_jogo" required value="<?= old('data_jogo') ?>"></div>
        <div class="campo"><label for="hora">Hora</label><input id="hora" type="time" name="hora" value="<?= old('hora') ?>"></div>
        <div class="campo"><label for="competicao">Competição *</label><input id="competicao" name="competicao" maxlength="160" required value="<?= old('competicao') ?>"></div>
        <div class="campo"><label for="local">Local (opcional)</label><input id="local" name="local" maxlength="160" value="<?= old('local') ?>"></div>
      </div>
      <label class="check"><input type="checkbox" name="em_casa" <?= ($_SERVER['REQUEST_METHOD']!=='POST' || isset($_POST['em_casa'])) ? 'checked' : '' ?>> Jogo em casa</label>
      <label class="check"><input type="checkbox" id="ja-jogado" <?= (($_POST['golos_cohaemato'] ?? '')!=='' || ($_POST['golos_adversario'] ?? '')!=='') ? 'checked' : '' ?>> O jogo já foi realizado (registar resultado)</label>
      <div class="row2" id="resultado-box" hidden style="max-width:420px"><div class="campo"><label for="golos_cohaemato">Golos Cohaemato</label><input id="golos_cohaemato" type="number" min="0" max="99" name="golos_cohaemato" value="<?= old('golos_cohaemato') ?>"></div>
        <div class="campo"><label for="golos_adversario">Golos adversário</label><input id="golos_adversario" type="number" min="0" max="99" name="golos_adversario" value="<?= old('golos_adversario') ?>"></div></div>
      <p class="peq" id="dica-futuro">Sem resultado, o jogo fica como “por realizar”.</p>
      <div style="display:flex;gap:8px;flex-wrap:wrap"><button class="btn btn-azul" type="submit">Adicionar jogo</button><button class="btn btn-linha" type="button" id="fechar-novo-jogo">Cancelar</button></div>
    </form>
  </div>

  <div class="painel">
    <div class="cab-lista"><div class="chips" data-tabs="jogos" style="margin:0"><button type="button" class="chip ativo" data-f="todos">Todos (<?= count($jogos) ?>)</button><button type="button" class="chip" data-f="futuro">Por realizar (<?= count($porJogar) ?>)</button><button type="button" class="chip" data-f="feito">Realizados (<?= count($realizados) ?>)</button></div>
      <input class="pesquisa filtro" data-alvo="tb-jogos" type="search" placeholder="Adversário, equipa…" aria-label="Pesquisar jogos"></div>
    <?php if (!$jogos): ?><p class="vazio">Ainda não existem jogos na base de dados.</p><?php else: ?>
    <div class="jogos-lista" id="tb-jogos">
      <?php foreach ($porJogar as $j) cartao_jogo($j, $hoje); ?>
      <?php foreach ($realizados as $j) cartao_jogo($j, $hoje); ?>
    </div>
    <p class="vazio" id="vazio-tb-jogos" hidden>Nenhum jogo encontrado.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ============ INSCRIÇÕES ============ -->
<section class="sec" id="sec-inscricoes">
  <div class="topo"><div><p class="eyebrow">Atletas</p><h1>Pedidos de inscrição <span class="contador">últimos 30</span></h1></div></div>
  <div class="painel">
    <div class="cab-lista"><div class="chips" data-tabs="inscricoes" style="margin:0"><button type="button" class="chip ativo" data-f="todos">Todas (<?= count($inscricoes) ?>)</button><button type="button" class="chip" data-f="nova">Novas</button><button type="button" class="chip" data-f="contactada">Contactadas</button><button type="button" class="chip" data-f="concluida">Concluídas</button></div>
      <input class="pesquisa filtro" data-alvo="tb-inscricoes" type="search" placeholder="Nome, email…" aria-label="Pesquisar inscrições"></div>
    <?php if (!$inscricoes): ?><p class="vazio">Ainda não existem pedidos de inscrição.</p><?php else: ?>
    <div class="table-scroll"><table><thead><tr><th>Atleta / escalão</th><th>Encarregado</th><th>Contacto</th><th>Recebida</th><th>Estado</th></tr></thead><tbody id="tb-inscricoes">
    <?php foreach ($inscricoes as $i): ?><tr data-g="<?= e($i['estado']) ?>" data-q="<?= e(mb_strtolower($i['nome_atleta'].' '.$i['nome_encarregado'].' '.$i['email'].' '.$i['telefone'].' '.$i['escalao_interesse'])) ?>">
      <td><strong><?= e($i['nome_atleta']) ?></strong><br><span class="peq"><?= e($i['escalao_interesse']) ?></span></td>
      <td><?= e($i['nome_encarregado']) ?></td>
      <td><a href="mailto:<?= e($i['email']) ?>"><?= e($i['email']) ?></a><br><a class="peq" href="tel:<?= e(preg_replace('/\s+/', '', (string)$i['telefone'])) ?>"><?= e($i['telefone']) ?></a></td>
      <td class="peq"><?= e(data_pt(substr((string)$i['criada_em'],0,10),true)) ?></td>
      <td><form method="post" class="inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="acao" value="estado_inscricao"><input type="hidden" name="id" value="<?= (int)$i['id'] ?>">
        <select name="estado" aria-label="Estado da inscrição" class="auto-submit"><?php foreach ($estadoLabel as $v => $l): ?><option value="<?= $v ?>"<?= $i['estado']===$v?' selected':'' ?>><?= $l ?></option><?php endforeach; ?></select>
        <noscript><button class="btn-small" type="submit">Guardar</button></noscript></form></td></tr>
    <?php endforeach; ?></tbody></table></div><?php endif; ?></div>
</section>

<!-- ============ MENSAGENS ============ -->
<section class="sec" id="sec-mensagens">
  <div class="topo"><div><p class="eyebrow">Contactos</p><h1>Mensagens de contacto</h1></div></div>
  <div class="painel lista-simples">
    <?php if (!$mensagens): ?><p class="vazio">Sem mensagens registadas.</p><?php else: foreach ($mensagens as $m): ?>
    <div class="item"><div><strong><?= e($m['assunto']) ?></strong> <?php if (empty($m['lida'])): ?><span class="badge warn">Nova</span><?php endif; ?><br><span class="peq"><?= e($m['nome']) ?> · <?= e(data_pt(substr((string)$m['criada_em'],0,10),true)) ?></span></div>
      <a class="btn-small" style="text-decoration:none;align-self:center" href="mailto:<?= e($m['email']) ?>?subject=<?= rawurlencode('Re: '.$m['assunto']) ?>">Responder</a></div>
    <?php endforeach; endif; ?></div>
</section>

<!-- ============ EQUIPAS ============ -->
<section class="sec" id="sec-equipas">
  <div class="topo"><div><p class="eyebrow">Clube</p><h1>Equipas</h1></div></div>
  <div class="painel lista-simples">
    <?php if (!$equipas): ?><p class="vazio">Sem equipas registadas.</p><?php else: foreach ($equipas as $eq): ?>
    <div class="item"><div><strong><?= e($eq['nome']) ?></strong><?php if (!empty($eq['escalao']) && $eq['escalao'] !== $eq['nome']): ?><br><span class="peq"><?= e($eq['escalao']) ?></span><?php endif; ?></div><span class="badge <?= $eq['ativa']?'ok':'cinza' ?>"><?= $eq['ativa']?'Ativa':'Inativa' ?></span></div>
    <?php endforeach; endif; ?></div>
</section>

<p class="peq" style="margin-top:28px">🔒 Esta área contém dados pessoais. Não partilhes credenciais e utiliza HTTPS em produção. © <?= date('Y') ?> GDC Cohaemato.</p>
</main>
</div>

<div class="toasts" id="toasts"></div>
<dialog id="dlg"><h3 id="dlg-msg">Confirmar?</h3><p class="peq">Esta ação não pode ser anulada.</p><div class="acoes"><button class="btn btn-linha" id="dlg-nao" type="button">Cancelar</button><button class="btn btn-azul" id="dlg-sim" type="button" style="background:var(--err)">Eliminar</button></div></dialog>

<script>
(function(){
  var secs=['geral','noticias','jogos','inscricoes','mensagens','equipas'];
  function mostrar(id,foco){
    if(secs.indexOf(id)<0)id='geral';
    document.querySelectorAll('.sec').forEach(function(s){s.classList.toggle('ativa',s.id==='sec-'+id);});
    document.querySelectorAll('.nav-btn').forEach(function(b){b.classList.toggle('ativo',b.dataset.sec===id);});
    if(location.hash!=='#'+id)history.replaceState(null,'','#'+id);
    window.scrollTo({top:0});
    if(foco==='adversario')abrirNovoJogo(false);
    if(foco){var f=document.getElementById(foco);if(f)setTimeout(function(){f.focus();},60);}
  }
  // Painel "Novo jogo"
  var pnj=document.getElementById('painel-novo-jogo'),bnj=document.getElementById('btn-novo-jogo');
  function abrirNovoJogo(foca){
    if(!pnj)return;pnj.hidden=false;bnj.setAttribute('aria-expanded','true');
    if(foca!==false){pnj.scrollIntoView({behavior:'smooth',block:'start'});setTimeout(function(){document.getElementById('adversario').focus({preventScroll:true});},250);}
  }
  function fecharNovoJogo(){if(!pnj)return;pnj.hidden=true;bnj.setAttribute('aria-expanded','false');}
  if(bnj){bnj.addEventListener('click',function(){if(pnj.hidden)abrirNovoJogo();else fecharNovoJogo();});
    document.getElementById('fechar-novo-jogo').addEventListener('click',fecharNovoJogo);}

  // Marcadores: botões + / − e guardar só quando há alterações
  document.querySelectorAll('.placar-form').forEach(function(f){
    var c=f.elements['golos_cohaemato'],a=f.elements['golos_adversario'],btn=f.querySelector('.salvar'),orig=f.dataset.orig||'';
    function atualizar(){var ok=c.value!==''&&a.value!=='';btn.disabled=!(ok&&(c.value+','+a.value)!==orig);}
    function passo(inp,d){
      var outro=inp===c?a:c;if(outro.value==='')outro.value='0';
      var n=(inp.value===''?0:parseInt(inp.value,10))+d;inp.value=Math.max(0,Math.min(99,isNaN(n)?0:n));atualizar();
    }
    f.querySelectorAll('.step button').forEach(function(b){b.addEventListener('click',function(){passo(b.parentNode.querySelector('input'),parseInt(b.dataset.d,10));});});
    [c,a].forEach(function(i){i.addEventListener('input',atualizar);i.addEventListener('change',function(){var o=i===c?a:c;if(i.value!==''&&o.value==='')o.value='0';atualizar();});i.addEventListener('focus',function(){i.select();});});
    atualizar();
  });
  document.querySelectorAll('[data-sec]').forEach(function(b){b.addEventListener('click',function(){mostrar(b.dataset.sec);});});
  document.querySelectorAll('[data-ir]').forEach(function(b){b.addEventListener('click',function(){mostrar(b.dataset.ir,b.dataset.foco);});});
  window.addEventListener('hashchange',function(){mostrar(location.hash.slice(1));});
  mostrar(location.hash.slice(1));

  // Form submissions keep the current section after reload
  document.querySelectorAll('form').forEach(function(f){f.addEventListener('submit',function(){var s=document.querySelector('.sec.ativa');if(s)f.action=location.pathname+'#'+s.id.slice(4);});});

  // Tema
  document.querySelectorAll('.tema-btn').forEach(function(b){b.addEventListener('click',function(){
    var r=document.documentElement,n=r.getAttribute('data-tema')==='escuro'?'claro':'escuro';r.setAttribute('data-tema',n);try{localStorage.setItem('adm-tema',n);}catch(e){}
  });});

  // Pesquisa + chips (combinados)
  var estado={};
  function aplicar(tbId){
    var tb=document.getElementById(tbId);if(!tb)return;
    var inp=document.querySelector('.filtro[data-alvo="'+tbId+'"]'),q=inp?inp.value.trim().toLowerCase():'',g=estado[tbId]||'todos';
    var visiveis=0;
    tb.querySelectorAll('[data-q]').forEach(function(tr){
      var okQ=!q||(tr.dataset.q||'').indexOf(q)>-1,okG=g==='todos'||tr.dataset.g===g;tr.hidden=!(okQ&&okG);if(okQ&&okG)visiveis++;
    });
    var vz=document.getElementById('vazio-'+tbId);if(vz)vz.hidden=visiveis>0;
  }
  document.querySelectorAll('.filtro').forEach(function(i){i.addEventListener('input',function(){aplicar(i.dataset.alvo);});});
  document.querySelectorAll('.chips[data-tabs]').forEach(function(box){
    var tb=box.dataset.tabs==='jogos'?'tb-jogos':'tb-inscricoes';
    box.querySelectorAll('.chip').forEach(function(c){c.addEventListener('click',function(){
      box.querySelectorAll('.chip').forEach(function(x){x.classList.remove('ativo');});c.classList.add('ativo');estado[tb]=c.dataset.f;aplicar(tb);
    });});
  });

  // Estado de inscrição grava ao mudar
  document.querySelectorAll('select.auto-submit').forEach(function(s){s.addEventListener('change',function(){s.form.submit();});});

  // Confirmação personalizada
  var dlg=document.getElementById('dlg'),pend=null;
  document.querySelectorAll('form[data-confirm]').forEach(function(f){f.addEventListener('submit',function(ev){
    if(f.dataset.ok){return;}ev.preventDefault();pend=f;document.getElementById('dlg-msg').textContent=f.dataset.confirm;
    if(dlg.showModal)dlg.showModal();else if(confirm(f.dataset.confirm)){f.dataset.ok=1;f.submit();}
  });});
  document.getElementById('dlg-nao').addEventListener('click',function(){dlg.close();pend=null;});
  document.getElementById('dlg-sim').addEventListener('click',function(){dlg.close();if(pend){pend.dataset.ok=1;pend.submit();}});

  // Notícia: contador e pré-visualização do endereço
  var t=document.getElementById('titulo'),sl=document.getElementById('slug'),sp=document.getElementById('slugprev'),rs=document.getElementById('resumo'),cr=document.getElementById('cnt-resumo');
  function slugify(s){return s.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'');}
  function prev(){var v=sl.value.trim()||slugify(t.value);sp.textContent=v?'Endereço: /noticia/'+v:'';}
  if(t){t.addEventListener('input',prev);sl.addEventListener('input',prev);prev();}
  if(rs){var c=function(){cr.textContent=rs.value.length+'/400';};rs.addEventListener('input',c);c();}

  // Jogo: resultado opcional
  var jj=document.getElementById('ja-jogado'),rb=document.getElementById('resultado-box'),dica=document.getElementById('dica-futuro');
  function resultado(){var on=jj.checked;rb.hidden=!on;dica.hidden=on;rb.querySelectorAll('input').forEach(function(i){i.disabled=!on;i.required=on;});}
  if(jj){jj.addEventListener('change',resultado);resultado();}

  // Mensagens do servidor como toast
  var msgs=<?= json_encode(array_values(array_filter([$sucesso ? ['ok',$sucesso] : null, $erro ? ['erro',$erro] : null])), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  msgs.forEach(function(m){var d=document.createElement('div');d.className='toast '+m[0];d.textContent=m[1];document.getElementById('toasts').appendChild(d);setTimeout(function(){d.remove();},5000);});
  if(msgs.some(function(m){return m[0]==='erro';})){var a=document.querySelector('.aviso.erro');if(a)a.scrollIntoView({block:'center'});}
})();
</script>
</body></html>
