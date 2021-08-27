<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $profile->fancy_name }} deseja alterar o perfil</title>
</head>
<body>
    <div>
        <p><a href="{{ $profile->link }}">{{ $profile->fancy_name }}</a> deseja alterar o perfil</p>

        <h3>Mudanças</h3>

        <ul>
            @foreach ($request->changes as $field => $value)
                @if ($profile[$field] == $value)
                    @continue
                @endif
                @if ($field == 'date_birth')
                    <li><b>{{ __("profile.{$field}") }}</b>: de {{ $profile[$field]->format('d/m/Y') ?? 'vazio' }} para {{ $value }}</li>
                    @continue
                @endif
                <li><b>{{ __("profile.{$field}") }}</b>: de {{ $profile[$field] ?? 'vazio' }} para {{ $value }}</li>

            @endforeach
        </ul>

        <a href="{{ route('profileChangeRequests.accept', $request->id) }}">Clique aqui para aceitar</a>

        <p>Qualquer dúvida, estamos a disposição.</p>
        Cordialmente, <strong>Pharos Elenco</strong>
    </div>
</body>
</html>
