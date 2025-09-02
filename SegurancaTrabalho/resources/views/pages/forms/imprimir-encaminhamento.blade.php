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
        <div class="info-line"><strong>EMPRESA:</strong> {{ $encaminhamento->empresa->razao_social ?? 'CRENG - SERVIÇOS E COMERCIO LTDA' }}</div>
        <div class="info-line"><strong>CNPJ:</strong> {{ $encaminhamento->empresa->cnpj ?? '49.267.000/0001-28' }}</div>
        <div class="info-line"><strong>COLABORADOR:</strong> {{ $encaminhamento->funcionario->nome ?? 'DARLISON GEOVANE PIMENTEL FREITAS' }}</div>
        <div class="info-line"><strong>RG:</strong> {{ $encaminhamento->funcionario->rg ?? '6063417-PA' }}</div>
        <div class="info-line"><strong>CPF:</strong> {{ $encaminhamento->funcionario->cpf ?? '902861202-59' }}</div>
        <div class="info-line"><strong>DATA DE NASCIMENTO:</strong> {{ $encaminhamento->funcionario->data_nascimento ?? '15/03/1990' }}</div>
        <div class="info-line"><strong>FUNÇÃO:</strong> {{ $encaminhamento->funcionario->cargo->nome ?? $encaminhamento->cargo->nome ?? 'MECÂNICO' }}</div>
        <div class="info-line"><strong>TIPO DE EXAME:</strong> {{ $encaminhamento->tipo_exame ?? 'ADMISSIONAL' }}</div>
    </div>
    
    <div class="section-title">RISCOS OCUPACIONAIS</div>
    
    <div class="risks-grid">
        <div>
            <div class="risk-item"><strong>FÍSICO:</strong> Ruído</div>
            <div class="risk-item"><strong>FÍSICO:</strong> Raios UV</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Graxas</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Diesel</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Gasolina</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Desengraxante</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Thinner</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Desengraxante</div>
            <div class="risk-item"><strong>QUÍMICO:</strong> Limpa contato</div>
            <div class="risk-item"><strong>BIOLÓGICO:</strong> Ausência de fator de risco</div>
        </div>
        <div>
            <div class="risk-item"><strong>ERGONÔMICO:</strong> Postura inadequada</div>
            <div class="risk-item"><strong>ERGONÔMICO:</strong> Carregamento de peças ou volumes</div>
            <div class="risk-item"><strong>ACIDENTE:</strong> Queda de nível diferente</div>
            <div class="risk-item"><strong>ACIDENTE:</strong> Queda de objetos sobre os membros</div>
            <div class="risk-item"><strong>ACIDENTE:</strong> Peças quentes</div>
        </div>
    </div>
    
    <div class="section-title">EXAMES</div>
    
    <div class="exams-section">
        <div class="exam-item"><strong>0295 -</strong> EXAME CLÍNICO OCUPACIONAL</div>
        <div class="exam-item"><strong>0693 -</strong> HEMOGRAMA COMP + CONT PLAQ</div>
        <div class="exam-item"><strong>1057 -</strong> ESPIROMETRIA</div>
        <div class="exam-item"><strong>1098 -</strong> URINA ROTINA</div>
        <div class="exam-item"><strong>1074 -</strong> RX DE COLUNA DORSAL - AP/PERFIL</div>
    </div>
    
    <div class="footer">
        <div><strong>PROATIVA</strong> – Soluções em Saúde e Segurança do Trabalho</div>
        <div>Tv. Assis de Vasconcelos, nº 746 Aldeia - Entre Mendonça Furtado e Presidente Vargas</div>
        <div>Santarém - PA</div>
    </div>
</body>
</html>