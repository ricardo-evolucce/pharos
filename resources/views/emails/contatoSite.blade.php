<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>{{ $email->nome }} foi favoritado por</title>
</head>
<body>
    <div>
     
<h3>Mensagem do site: </h3>

<b>Nome: </b> {{ $email->name }}<br>
<b>Telefone: </b>  {{ $email->phone }}<br>
<b>Email: </b> {{ $email->email }} <br><br>
<b>Mensagem: </b> {{ $email->message }}
 



        <p>Qualquer dúvida, estamos a disposição.</p>
        Cordialmente, <strong>Pharos Elenco</strong>
    </div>
</body>
</html>
