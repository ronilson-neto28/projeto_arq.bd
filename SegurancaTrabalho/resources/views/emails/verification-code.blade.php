<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .verification-code {
            background-color: #007bff;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">PRO<span style="color: #28a745;">ATIVA</span></div>
            <h2>Código de Verificação</h2>
        </div>
        
        <p>Olá, <strong>{{ $user->name }}</strong>!</p>
        
        <p>Você solicitou a criação de uma conta no sistema PROATIVA. Para confirmar seu endereço de email, utilize o código de verificação abaixo:</p>
        
        <div class="verification-code">
            {{ $verificationCode }}
        </div>
        
        <div class="warning">
            <strong>⚠️ Importante:</strong>
            <ul>
                <li>Este código é válido por <strong>15 minutos</strong></li>
                <li>Não compartilhe este código com ninguém</li>
                <li>Se você não solicitou esta verificação, ignore este email</li>
            </ul>
        </div>
        
        <p>Após inserir o código, sua conta será ativada e você poderá fazer login no sistema.</p>
        
        <p>Se você tiver alguma dúvida, entre em contato com nossa equipe de suporte.</p>
        
        <div class="footer">
            <p>Este é um email automático, não responda a esta mensagem.</p>
            <p>&copy; {{ date('Y') }} PROATIVA - Sistema de Segurança do Trabalho</p>
        </div>
    </div>
</body>
</html>