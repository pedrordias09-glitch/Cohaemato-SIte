# Site GDC Cohaemato (PHP)

## Executar localmente
```
cd cohaemato
php -S localhost:8000
```
Abra http://localhost:8000. Sem base de dados, o site mostra dados de exemplo.

## Ligar a base de dados
1. Importe `database/schema.sql` e depois `database/seed.sql` (phpMyAdmin ou `mysql < ficheiro.sql`).
2. Em `includes/config.php`: `DB_ENABLED` → `true` e preencha `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
   (ou defina as variáveis de ambiente com os mesmos nomes).
3. Pronto: equipas, notícias, jogos e galeria passam a vir da BD; o formulário de contacto grava em `mensagens`.

## Estrutura
- `includes/` configuração, PDO (`db.php`), acesso a dados (`functions.php`), dados de exemplo (`fallback.php`)
- `assets/` CSS, JS, logótipo (`logo.png` é o emblema oficial do clube)
- `database/` esquema e dados iniciais

## A preencher
Telefone e redes sociais em `includes/config.php`; treinadores, notícias, fotos (`assets/img/galeria/`) e história do clube.


## Novas funcionalidades: inscrições e administração
- `inscricoes.php`: formulário de pedido de informações para a formação; os dados são guardados na tabela `inscricoes`.
- `admin/index.php`: área privada para criar/apagar notícias, adicionar/apagar jogos, acompanhar pedidos de inscrição e consultar mensagens recentes.
- Importa novamente `database/schema.sql` (ou executa o bloco `CREATE TABLE inscricoes` se já tiveres uma base de dados em produção). O `CREATE TABLE IF NOT EXISTS` preserva as tabelas existentes.
- Ativa a base de dados em `includes/config.php` e define credenciais por variáveis de ambiente `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`. Não uses uma conta MySQL com permissões de administrador em produção.
- Define `ADMIN_USER` e `ADMIN_PASSWORD_HASH` no ambiente do servidor. Para gerar um hash seguro com PHP: `php -r "echo password_hash('ESCOLHE-UMA-PALAVRA-PASSE-FORTE', PASSWORD_DEFAULT), PHP_EOL;"` e coloca o resultado em `ADMIN_PASSWORD_HASH`. Não guardes a palavra-passe em texto simples no código.
- A área de administração requer a base de dados ativa. Publica o site com HTTPS, restringe o acesso ao painel, mantém backups e define permissões mínimas na base de dados.
- Os pedidos de inscrição podem conter dados de menores: recolhe apenas o necessário, informa os encarregados de educação sobre a utilização dos dados e define um prazo de conservação/apagamento adequado.
