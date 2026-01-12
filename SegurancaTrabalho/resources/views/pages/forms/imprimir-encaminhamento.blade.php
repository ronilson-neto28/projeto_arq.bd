<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encaminhamento para Exame Médico Ocupacional</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2e8b57;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-line {
            margin-bottom: 3px;
        }
        
        .section-title {
            font-weight: bold;
            text-align: center;
            margin: 20px 0 10px 0;
            text-decoration: underline;
        }
        
        .risks-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .risk-item {
            margin-bottom: 2px;
        }
        
        .exams-section {
            margin-top: 20px;
        }
        
        .exam-item {
            margin-bottom: 2px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Imprimir</button>
    
    <div class="header">
        <div class="logo">Proativa</div>
        <div class="subtitle">SOLUÇÕES EM SAÚDE E SEGURANÇA DO TRABALHO</div>
    </div>
    
    <div class="title">Encaminhamento para Exame Médico Ocupacional</div>
    
    <div class="info-section">
        @php
            $empresa = $encaminhamento->funcionario->empresa ?? null;
            $empresaNome = $empresa->razao_social ?? ($empresa->nome_fantasia ?? '');
            $cnpjRaw = $empresa->cnpj ?? '';
            $c = preg_replace('/\D+/','', (string)$cnpjRaw);
            $cnpjFmt = (strlen($c)===14) ? (substr($c,0,2).'.'.substr($c,2,3).'.'.substr($c,5,3).'/'.substr($c,8,4).'-'.substr($c,12,2)) : $cnpjRaw;
            $cpfRaw = $encaminhamento->funcionario->cpf ?? '';
            $p = preg_replace('/\D+/','', (string)$cpfRaw);
            $cpfFmt = (strlen($p)===11) ? (substr($p,0,3).'.'.substr($p,3,3).'.'.substr($p,6,3).'-'.substr($p,9,2)) : $cpfRaw;
            $dnRaw = $encaminhamento->funcionario->data_nascimento ?? null;
            try { $dnFmt = $dnRaw ? (\Carbon\Carbon::parse($dnRaw)->format('d/m/Y')) : ''; } catch (\Throwable $e) { $dnFmt = is_string($dnRaw) ? $dnRaw : ''; }
            $funcao = $encaminhamento->funcionario->cargo->nome ?? ($encaminhamento->cargo->nome ?? '');
        @endphp
        <div class="info-line"><strong>EMPRESA:</strong> {{ $empresaNome }}</div>
        <div class="info-line"><strong>CNPJ:</strong> {{ $cnpjFmt }}</div>
        <div class="info-line"><strong>COLABORADOR:</strong> {{ $encaminhamento->funcionario->nome ?? '' }}</div>
        <div class="info-line"><strong>RG:</strong> {{ $encaminhamento->funcionario->rg ?? '' }}</div>
        <div class="info-line"><strong>CPF:</strong> {{ $cpfFmt }}</div>
        <div class="info-line"><strong>DATA DE NASCIMENTO:</strong> {{ $dnFmt }}</div>
        <div class="info-line"><strong>FUNÇÃO:</strong> {{ $funcao }}</div>
        <div class="info-line"><strong>TIPO DE EXAME:</strong> {{ $encaminhamento->tipo_exame ?? '' }}</div>
    </div>
    
    <div class="section-title">RISCOS OCUPACIONAIS</div>
    
    @php
        $grp = (array)($encaminhamento->riscos_ocupacionais ?? []);
        $mapLabel = [
            'fisico' => 'FÍSICO',
            'quimico' => 'QUÍMICO',
            'biologico' => 'BIOLÓGICO',
            'ergonomico' => 'ERGONÔMICO',
            'acidentes' => 'ACIDENTE',
            'outros' => 'OUTROS',
        ];
        $combined = collect([]);
        foreach ($grp as $k => $lista) {
            $label = $mapLabel[strtolower($k)] ?? strtoupper($k);
            if (is_string($lista)) {
                $combined->push(['tipo' => $label, 'nome' => $lista]);
            } else {
                foreach ((array)$lista as $nome) {
                    $combined->push(['tipo' => $label, 'nome' => $nome]);
                }
            }
        }
        if ($combined->isEmpty()) {
            $riscosDb = collect($riscos_selecionados ?? [])->map(function($r){
                return ['tipo' => strtoupper($r->tipoRisco->nome ?? ''), 'nome' => $r->nome];
            });
            $combined = $riscosDb->values();
        }
        $col1 = $combined->slice(0, ceil($combined->count()/2));
        $col2 = $combined->slice(ceil($combined->count()/2));
    @endphp
    <div class="risks-grid">
        <div>
            @foreach($col1 as $r)
                <div class="risk-item"><strong>{{ $r['tipo'] }}:</strong> {{ $r['nome'] }}</div>
            @endforeach
        </div>
        <div>
            @foreach($col2 as $r)
                <div class="risk-item"><strong>{{ $r['tipo'] }}:</strong> {{ $r['nome'] }}</div>
            @endforeach
        </div>
    </div>
    
    <div class="section-title">EXAMES</div>
    
    <div class="exams-section">
        @foreach(($encaminhamento->itensSolicitados ?? []) as $it)
            @php
                $codigo = $it->exame_id ?? null;
                $codStr = is_object($codigo) ? (string)$codigo : (string)$codigo;
            @endphp
            <div class="exam-item"><strong>{{ $codStr ? $codStr.' -' : '' }}</strong> {{ $it->nome_exame_snapshot ?? 'Exame' }}</div>
        @endforeach
    </div>
    
    <div class="footer">
        <div><strong>PROATIVA</strong> – Soluções em Saúde e Segurança do Trabalho</div>
        <div>Prédio Aldeia comércio - Trav. barjonas de miranda, Av. São Sebastião, sala 206 - Aldeia, Santarém - PA, 68040-520</div>
        <div>telefone - 93 99103-1855</div>
        <div>Santarém - PA</div>
    </div>
</body>
</html>
