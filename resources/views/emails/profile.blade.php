﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{$profile->user->name}}</title>
    <style>
        @font-face {
            font-family: Principal;
            src: url('/fonts/josefin-sans-light.ttf');
        }
    </style>
</head>
<body style="width: 100%; border: 1px solid; font-family: Principal, sans-serif">

<div style="width: 45%; height: 700px; position: relative; float:left; text-align: center">
    <div>
        {{-- logo --}}
        <img src="{{ public_path()}}/media/various/logo-pdf.png" style="max-width:30%; padding-left:20px; ">
    </div>

    <div style="width:100%; height: auto; text-align: center;">
        {{-- <img src="{{ public_path()}}/{{$foto_principal}}" style="max-width:100%; max-height:500px; padding-left: 20px; margin-top: 5px;">--}}
        <img src="{{$foto_principal}}" style="max-width:100%; max-height:500px; padding-left: 20px; margin-top: 5px;">
    </div>
    <div style="font-size: 18px; margin-top: 15px"><b>{{$profile->fancy_name}}</b></div>
</div>

<div style="width: 48%; height: 700px; position: relative; float:left;">

    <div style="width:100%; text-align: center; ">
        <table style="width:100%">
            @php $cont = 1; @endphp
            @for($i = 1; $i <= count($fotos_grupos); $i++)
                @if($cont == 1)
                    <tr>
                        @endif
                        <td style="width: 100%; text-align: center">
                            <img src="{{$fotos_grupos[$i]}}" style="max-width:200px; max-height: 300px">
                        </td>
                        @if($cont == 2)
                    </tr>
                    @php
                        $cont = 1;
                    @endphp
                @else
                    @php
                        $cont++;
                    @endphp
                @endif
            @endfor
            <tr>
                <td colspan="2" style="text-align: center;">
                    <div style="font-size: 14px">DN: {{\Carbon\Carbon::parse($profile->date_birth)->format('d/m/Y')}} Altura: {{$profile->height}} Manequim: {{$profile->dummy}}  |  Sapato: {{$profile->feet}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-size: 12px">
                    <div>
                        <span>www.pharoselenco.com.br</span> <span>@pharoselenco</span>
                    </div>
                    <div>
                        <span>Av. Getúlio Vargas 1151, Sala 506 / POA - RS</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>


