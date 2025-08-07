<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Código de Recuperação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .code-box {
            font-size: 24px;
            background-color: #eef2ff;
            color: #1e3a8a;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h2>Olá!</h2>
    <p>Você solicitou a recuperação da sua senha. Aqui está o seu código:</p>

    <div class="code-box">
        <strong>{{ $codigo }}</strong>
    </div>

    <p>Se você não solicitou isso, apenas ignore este e-mail.</p>

    <p>Equipe PROATIVA</p>
</body>
</html>
