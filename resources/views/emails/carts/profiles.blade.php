<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nova Solicitação</title>
</head>
<body>
    
</body>
</html>

<div>
    <div>Pedido <strong>#{{$cart->id}}</strong></div>
    Olá!
    <br>

    <p>Na lista abaixo, ao lado do nome do agenciado, clicando em Perfil você encontra o material completo de cada um (fotos, dados, vídeos e currículo).</p>

    <p>Segue em anexo os composites em PDF e mais uma foto de cada agenciado, no formato JPG:</p>
    <ul>
        @foreach ($cart->profiles as $profile)
        <li>{{$profile->fancy_name}} | <a href="http://www.pharoselenco.com.br/elenco/{{$profile->slug}}">Perfil</a></li>
        @endforeach
    </ul>
    <br>
    <br>
    <p>Qualquer dúvida, estamos a disposição.</p> 
    Cordialmente, <strong>Pharos Elenco</strong> 
</div>