<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $profile->fancy_name }} foi favoritado por {{ $user->name }}</title>
</head>
<body>
    <div>
        <p><a href="{{ $profile->link }}">{{ $profile->fancy_name }}</a> foi favoritado por {{ $user->name }}</p>

        <p>Qualquer dúvida, estamos a disposição.</p>
        Cordialmente, <strong>Pharos Elenco</strong>
    </div>
</body>
</html>
