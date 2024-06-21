<html lang="br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de senha</title>

</head>
<body>
    <div class="email">
        <div class="header_title">
            Recuperar senha
        </div>
        <div class="header_message">
            Olá <strong>{{ $user->person->name }}</strong>,<br />
            Para redefinir sua senha, clique no link <a href="{{ $recoveryLink }}" target="_blank">{{ $recoveryLink }}</a> e siga os passos na nova página.<br />
            Caso desconheça esta solicitação, ignore o email.
        </div>
        <div class="footer">
            Patinhas Carentes &copy;
        </div>
    </div>
</body>
</html>
