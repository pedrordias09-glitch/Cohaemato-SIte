<?php
/**
 * Dados de exemplo usados enquanto a base de dados não estiver ligada.
 * Têm a mesma estrutura das tabelas em database/schema.sql.
 * Notícias e galeria são EXEMPLOS, a substituir por conteúdo real.
 */

function fallback_equipas(): array
{
    // Escalões e competições confirmados no zerozero (épocas 2024/25 e 2025/26).
    // Treinadores: por preencher (não confirmados).
    return [
        ['id' => 1, 'slug' => 'seniores', 'sigla' => 'SEN', 'nome' => 'Seniores', 'escalao' => 'Seniores', 'competicao' => 'AF Porto – Divisão de Elite', 'treinador' => null, 'descricao' => 'A equipa principal do clube, nos campeonatos distritais de futsal da AF Porto, com jogos no Pavilhão da Cohaemato.', 'ordem' => 1],
        ['id' => 7, 'slug' => 'sub-23', 'sigla' => 'B', 'nome' => 'Equipa B / Sub-23', 'escalao' => 'Seniores B', 'competicao' => 'AF Porto', 'treinador' => null, 'descricao' => 'A equipa de transição entre a formação e a equipa principal, onde os jovens ganham ritmo competitivo.', 'ordem' => 2],
        ['id' => 2, 'slug' => 'sub-19', 'sigla' => 'S19', 'nome' => 'Sub-19', 'escalao' => 'Juniores A', 'competicao' => 'AF Porto – Futsal Juniores A', 'treinador' => null, 'descricao' => 'O último passo da formação antes da equipa principal.', 'ordem' => 3],
        ['id' => 3, 'slug' => 'sub-17', 'sigla' => 'S17', 'nome' => 'Sub-17', 'escalao' => 'Juniores B', 'competicao' => 'AF Porto – Juniores B, Divisão de Elite', 'treinador' => null, 'descricao' => 'Atletas em plena evolução competitiva, a preparar a passagem aos juniores.', 'ordem' => 4],
        ['id' => 4, 'slug' => 'sub-15', 'sigla' => 'S15', 'nome' => 'Sub-15', 'escalao' => 'Juniores C', 'competicao' => 'AF Porto – Futsal Juniores C', 'treinador' => null, 'descricao' => 'Jovens atletas a consolidar a técnica e a leitura de jogo.', 'ordem' => 5],
        ['id' => 5, 'slug' => 'sub-13', 'sigla' => 'S13', 'nome' => 'Sub-13', 'escalao' => 'Juniores D', 'competicao' => 'AF Porto – Futsal Juniores D', 'treinador' => null, 'descricao' => 'Fase de desenvolvimento em que o jogo coletivo ganha forma. Segundo o zerozero, o clube tem 5 títulos regionais neste escalão (4 da 1.ª Divisão e 1 Taça).', 'ordem' => 6],
        ['id' => 6, 'slug' => 'sub-11', 'sigla' => 'S11', 'nome' => 'Sub-11', 'escalao' => 'Juniores E', 'competicao' => 'AF Porto – Futsal Juniores E', 'treinador' => null, 'descricao' => 'Onde tudo começa: aprender a jogar, a divertir-se e a pertencer ao clube.', 'ordem' => 7],
    ];
}

/**
 * Plantéis por equipa (chave = slug da equipa).
 * Cada jogador: nome, numero, posicao (Guarda-redes | Fixo | Ala | Pivô), nacionalidade, nascimento (AAAA-MM-DD), foto (ficheiro em assets/img/jogadores/).
 * Exemplo:
 *   'seniores' => [
 *       ['nome' => 'Nome Apelido', 'numero' => 7, 'posicao' => 'Ala', 'nacionalidade' => 'Portugal', 'nascimento' => '2000-01-31', 'foto' => null],
 *   ],
 */
function fallback_jogadores(): array
{
    // Plantéis copiados do zerozero a 23/10/2026. 'idade' é a idade nessa data; 'nascimento' (AAAA-MM-DD) tem prioridade se for preenchido.
    $p = static fn(string $n, string $pos, int $idade, ?int $num = null) => ['nome' => $n, 'numero' => $num, 'posicao' => $pos, 'nacionalidade' => 'Portugal', 'idade' => $idade, 'nascimento' => null, 'foto' => null];
    return [
        'seniores' => [
            $p('Rui Gonçalves', 'Guarda-redes', 32, null),
            $p('Miguel Fonseca', 'Guarda-redes', 23, null),
            $p('Cláudio Oliveira', 'Guarda-redes', 29, null),
            $p('João Fonseca', 'Fixo', 25, null),
            $p('Chóias', 'Fixo / Ala', 43, null),
            $p('Bruno Oliveira', 'Universal', 34, null),
            $p('Figo', 'Universal', 34, null),
            $p('Tiago Sousa', 'Universal', 21, null),
            $p('Nuno Reguengo', 'Universal', 19, null),
            $p('Marquinho', 'Ala', 27, null),
            $p('Rica', 'Ala', 31, null),
            $p('João Matos', 'Ala', 23, null),
            $p('Ricardo Oliveira', 'Ala / Pivô', 29, null),
            $p('Pedro Serra', 'Ala / Pivô', 26, null),
            $p('Afonso Nunes', 'Ala / Pivô', 19, null),
            $p('Rodrigo Wang', 'Pivô', 19, null),
        ],
        'sub-23' => [
            $p('Tiago Neuer', 'Guarda-redes', 20, null),
            $p('Bruno Miranda', 'Guarda-redes', 20, null),
            $p('Tiago Pimentel', 'Guarda-redes', 20, null),
            $p('Fabio Dias', 'Guarda-redes', 20, null),
            $p('Leandro Figueiredo', 'Fixo / Ala', 21, null),
            $p('Ivan Pinto', 'Universal', 19, null),
            $p('Nuno Pereira', 'Universal', 19, null),
            $p('Andre Santos', 'Universal', 19, null),
            $p('Rui Soares', 'Universal', 21, null),
            $p('Nuno Reguengo', 'Universal', 19, null),
            $p('Tiago Santos', 'Universal', 19, null),
            $p('Francisco Ferreira', 'Universal', 19, null),
            $p('Gabriel Santos', 'Ala', 19, null),
            $p('Tiago Gomes', 'Ala', 21, null),
            $p('Rogério Marinho', 'Ala', 21, null),
            $p('Pedro Ribeiro', 'Ala', 19, null),
            $p('Tiago Cardoso', 'Ala / Pivô', 19, null),
            $p('João Sousa', 'Ala / Pivô', 19, null),
            $p('Afonso Nunes', 'Ala / Pivô', 19, null),
            $p('Tito', 'Pivô', 19, null),
            $p('Rodrigo Wang', 'Pivô', 19, null),
        ],
        'sub-19' => [
            $p('Daniel Ribeiro', 'Guarda-redes', 17, null),
            $p('Angelo Moreira', 'Guarda-redes', 18, 1),
            $p('Diogo Carvalho', 'Guarda-redes', 17, 12),
            $p('Ricardo Matos', 'Defesa', 18, null),
            $p('Martim Peixoto', 'Fixo', 18, null),
            $p('Gil Sousa', 'Universal', 16, null),
            $p('Rodrigo Inácio', 'Universal', 15, null),
            $p('Aadit Bhikhabhai', 'Universal', 17, null),
            $p('Diogo Rodrigues', 'Universal', 17, null),
            $p('Martim Gomes', 'Universal', 17, 2),
            $p('Joao Lancos', 'Universal', 17, 6),
            $p('Carlinhos', 'Universal', 18, 10),
            $p('Pedro Santos', 'Universal', 17, 15),
            $p('Simão Pinto', 'Ala', 16, null),
            $p('Goncalo Miranda', 'Ala', 18, 18),
            $p('Leonardo Pinheiro', 'Ala / Pivô', 18, 11),
            $p('Goncalo Crista', 'Pivô', 18, 4),
            $p('Bubu', 'Pivô', 17, 13),
        ],
        'sub-17' => [
            $p('Santiago Remelgado', 'Guarda-redes', 14, null),
            $p('Manuel Beleza', 'Fixo', 15, null),
            $p('Francisco Conceição', 'Fixo / Ala', 15, null),
            $p('Rodrigo Pinto', 'Médio', 15, null),
            $p('Simão Moreira', 'Universal', 16, null),
            $p('Tiago Loureiro', 'Universal', 15, null),
            $p('Tomás Geraldes', 'Universal', 15, null),
            $p('Jose Pereira', 'Universal', 15, null),
            $p('Gustavo Ponte', 'Universal', 15, null),
            $p('Rodrigo Inácio', 'Universal', 15, null),
            $p('Gil Sousa', 'Universal', 16, null),
            $p('Bruno Costa', 'Ala', 16, null),
            $p('Diego Bretes', 'Ala', 15, null),
            $p('Pedro Ferreira', 'Ala / Pivô', 15, null),
            $p('Diogo Chaves', 'Pivô', 15, null),
        ],
        'sub-15' => [
            $p('Martim Pontes', 'Guarda-redes', 14, null),
            $p('Simão Jacinto', 'Fixo', 14, null),
            $p('Igor Coelho', 'Universal', 14, null),
            $p('Duarte Silva', 'Universal', 14, null),
            $p('Hugo Pereira', 'Universal', 13, null),
            $p('Renato Felix', 'Ala', 14, null),
        ],
        'sub-13' => [
        ],
        'sub-11' => [
        ],
    ];
}

function fallback_noticias(): array
{
    return [
        ['id' => 1, 'slug' => 'bem-vindo-ao-novo-site', 'titulo' => 'Bem-vindo ao novo site do Cohaemato', 'resumo' => 'Notícia de exemplo: aqui vão aparecer as novidades do clube.', 'conteudo' => "Esta é uma notícia de exemplo.\n\nQuando a base de dados estiver ligada, as notícias passam a ser lidas da tabela `noticias` e esta mensagem desaparece.", 'data_publicacao' => '2026-10-23', 'destaque' => 1],
        ['id' => 2, 'slug' => 'inicio-da-epoca', 'titulo' => 'Arranque da nova época desportiva', 'resumo' => 'Exemplo: apresentação das equipas e calendário dos primeiros jogos.', 'conteudo' => "Texto de exemplo para a notícia de início de época.\n\nSubstitua por conteúdo real do clube.", 'data_publicacao' => '2026-10-10', 'destaque' => 0],
        ['id' => 3, 'slug' => 'inscricoes-abertas', 'titulo' => 'Inscrições abertas para a formação', 'resumo' => 'Exemplo: como inscrever os mais novos nos escalões de formação.', 'conteudo' => "Texto de exemplo sobre inscrições.\n\nEscreva aqui as condições, datas e contactos.", 'data_publicacao' => '2026-09-20', 'destaque' => 0],
    ];
}

function fallback_jogos(): array
{
    // Calendário 2026/27 copiado do zerozero a 23/10/2026 (resultados já com os golos do Cohaemato primeiro).
    $j = static fn(string $e, string $d, ?string $h, string $a, int $c, ?int $gc, ?int $ga, string $comp) => ['escalao' => $e, 'data_jogo' => $d, 'hora' => $h, 'adversario' => $a, 'em_casa' => $c, 'golos_cohaemato' => $gc, 'golos_adversario' => $ga, 'competicao' => $comp, 'local' => $c ? 'Pavilhão da Cohaemato' : null];
    $l = [
        $j('Seniores', '2026-10-10', '18:30', 'CA Sangemil', 0, 3, 2, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-10-17', '19:00', 'GCR Vermoim', 1, 3, 5, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-10-24', '19:00', 'Desp. Ordem', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-10-31', '21:00', 'Balantuna', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-11-07', '19:00', 'ARC Gueifães', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-11-14', null, 'Maia Futsal', 0, null, null, 'AF Porto Taça 26/27'),
        $j('Seniores', '2026-11-20', '21:30', 'Guidões FC', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-11-28', '19:00', 'AM Granja', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-12-04', '21:30', 'USC Paredes', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-12-12', '19:00', 'GDR Retorta', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2026-12-19', '17:15', 'CD Aves 1930', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-01-09', '19:00', 'Juventude Gaia', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-01-16', '18:30', 'CD Póvoa', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-01-23', '19:00', 'Ac. Pedras Rubras', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-01-30', '19:00', 'CA Sangemil', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-02-06', '21:00', 'GCR Vermoim', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-02-12', '21:30', 'Desp. Ordem', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-02-20', '19:00', 'Balantuna', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-03-06', '21:30', 'ARC Gueifães', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-03-13', '19:00', 'Guidões FC', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-03-20', '21:30', 'AM Granja', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-04-03', '19:00', 'USC Paredes', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-04-10', '21:00', 'GDR Retorta', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-04-17', '19:00', 'CD Aves 1930', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-04-24', '18:00', 'Juventude Gaia', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-05-01', '19:00', 'CD Póvoa', 1, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Seniores', '2027-05-15', '21:00', 'Ac. Pedras Rubras', 0, null, null, 'AF Porto Liga Trust Futsal 26/27'),
        $j('Equipa B / Sub-23', '2026-10-02', '22:30', 'GDR Retorta B', 0, 2, 1, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-10-17', '19:00', 'Junqueira', 0, 3, 3, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-10-30', '22:15', 'Leões da Guarda', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-11-07', '21:30', 'FC Aliviada', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-11-20', '22:15', 'AD Sebolido', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-11-27', '21:30', 'GDCE Modelos B', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-12-01', '17:00', 'UD Torrados', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-12-04', '22:15', 'A-Smart', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-12-12', '18:00', 'FC Foz', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2026-12-18', '22:15', 'AB92', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-01-09', '22:30', 'São Sebastião', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-01-15', '22:15', 'Mindelo', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-01-22', '22:15', 'GDR Retorta B', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-01-29', '21:30', 'UD Torrados', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-02-05', '22:15', 'Junqueira', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-02-20', '19:30', 'Leões da Guarda', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-03-05', '22:15', 'FC Aliviada', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-03-13', '18:00', 'AD Sebolido', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-03-19', '22:15', 'GDCE Modelos B', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-04-03', '21:30', 'A-Smart', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-04-09', '22:15', 'FC Foz', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-04-17', '17:30', 'AB92', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-04-23', '22:15', 'São Sebastião', 1, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Equipa B / Sub-23', '2027-05-01', '21:00', 'Mindelo', 0, null, null, 'AF Porto I Divisão Futsal Série 3 26/27'),
        $j('Sub-19', '2026-09-27', '18:00', 'AM Granja', 0, 4, 3, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-10-04', '16:00', 'Juventude Gaia', 1, 0, 8, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-10-11', '15:00', 'Maia Futsal', 0, 7, 1, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-10-18', '17:00', 'Arsenal Maia', 1, 3, 2, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-10-25', '18:15', 'Balantuna', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-11-01', '17:00', 'Paços de Ferreira', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-11-08', '16:00', 'Gramidense Infante', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-11-14', '18:00', 'Rio Ave', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-11-22', '17:00', 'CD Póvoa', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-11-28', '18:00', 'FC Porto', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-12-06', '16:00', 'ARC Alpendorada', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-12-08', '16:00', 'AM Granja', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-12-12', '15:00', 'Juventude Gaia', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2026-12-20', '16:00', 'Maia Futsal', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-01-09', '16:30', 'Arsenal Maia', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-01-15', '21:30', 'Balantuna', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-01-24', '17:00', 'Paços de Ferreira', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-01-30', '18:30', 'Gramidense Infante', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-02-07', '17:00', 'Rio Ave', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-02-14', '11:30', 'CD Póvoa', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-02-21', '17:00', 'FC Porto', 1, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-19', '2027-02-27', '16:00', 'ARC Alpendorada', 0, null, null, 'AF Porto Divisão Elite Jun.A 26/27'),
        $j('Sub-17', '2026-10-18', '11:00', 'AD Penafiel', 0, 4, 2, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-11-01', '11:30', 'Ac. Pedras Rubras', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-11-07', '16:30', 'Miramar Império', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-11-14', '16:30', 'Gramidense Infante', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-11-28', '16:30', 'ARC Gueifães', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-12-05', '19:00', 'FC Aliviada', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-12-12', '15:00', 'Boavista FC', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2026-12-19', '15:00', 'Juventude Matosinhos', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-01-10', '11:30', 'Vila Futsal', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-01-16', '15:00', 'Freixieiro', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-01-23', '15:00', 'AD Penafiel', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-02-13', '15:00', 'Ac. Pedras Rubras', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-02-20', '15:00', 'Miramar Império', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-02-27', '15:00', 'Gramidense Infante', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-03-07', '15:00', 'ARC Gueifães', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-03-13', '15:00', 'FC Aliviada', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-03-21', '09:30', 'Boavista FC', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-04-10', '15:00', 'Juventude Matosinhos', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-04-17', '15:00', 'Vila Futsal', 1, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-17', '2027-04-25', '16:00', 'Freixieiro', 0, null, null, 'AF Porto Futsal Jun.A Div.Honra S2 26/27'),
        $j('Sub-15', '2026-10-18', '15:00', 'Arsenal Maia B', 1, 8, 1, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-10-24', '18:00', 'CR Bougado', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-11-01', '15:00', 'FC Aliviada', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-11-15', '15:00', 'Guidões FC', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-11-21', '16:00', 'AM Granja B', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-11-29', '15:00', 'CD Aves 1930', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-12-06', '14:00', 'SC Arcozelo', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-12-13', '15:00', 'Junqueira', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2026-12-19', '15:00', 'Valmesio', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-01-10', '15:00', 'Desp. Ordem', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-01-16', '15:00', 'GDCE Modelos', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-01-24', '15:00', 'GCR Vermoim', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-01-30', '16:00', 'Arsenal Maia B', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-02-07', '15:00', 'CR Bougado', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-02-14', '17:30', 'FC Aliviada', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-02-27', '15:30', 'Guidões FC', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-03-07', '15:00', 'AM Granja B', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-03-14', '11:30', 'CD Aves 1930', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-03-21', '15:00', 'SC Arcozelo', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-04-03', '17:00', 'Junqueira', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-04-11', '15:00', 'Valmesio', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-04-17', '16:00', 'Desp. Ordem', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-04-25', '15:00', 'GDCE Modelos', 1, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
        $j('Sub-15', '2027-05-02', '10:00', 'GCR Vermoim', 0, null, null, 'AF Porto Div.Honra Jun.C Futsal S1 2026/27'),
    ];
    foreach ($l as $i => &$x) $x['id'] = $i + 1;
    return $l;
}

function fallback_galeria(): array
{
    return [
        ['id' => 1, 'titulo' => 'Pavilhão da Cohaemato', 'categoria' => 'pavilhao', 'ficheiro' => null],
        ['id' => 2, 'titulo' => 'Dia de jogo', 'categoria' => 'jogos', 'ficheiro' => null],
        ['id' => 3, 'titulo' => 'Treino da formação', 'categoria' => 'formacao', 'ficheiro' => null],
        ['id' => 4, 'titulo' => 'Equipa sénior', 'categoria' => 'equipas', 'ficheiro' => null],
        ['id' => 5, 'titulo' => 'Convívio do clube', 'categoria' => 'eventos', 'ficheiro' => null],
        ['id' => 6, 'titulo' => 'Celebração', 'categoria' => 'jogos', 'ficheiro' => null],
    ];
}
