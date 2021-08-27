@extends('layouts.site')

@section('css_after')
<style>
    .elenco__title{
        display: flex;
    }
    .elenco__title__icon{
        padding-top: 5px;
        margin-right: 10px;
    }
    .elenco__title__information{

    }
    .elenco__title__information__name{
        font-size: xx-large;
        font-weight: 800;
        color: #2d3647;
        line-height: 97%;
    }
    .elenco__title__information__profission{
        color: #929292;
        text-transform: uppercase;
    }
    .profile__images{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: auto;
        grid-gap: 20px;
    }
    .profile__image{
        height: 135px;
        background-size: cover;
        background-position: 50% 0%;
        border-radius: 10px;
    }
    .profile__image:first-child{
        grid-column: 1 / 4;
        height: 535px;
    }
    .profile__details__icons{
        background-color: #e7e7e7;
        height: 33px;
        padding: 0px;

    }
    .profile__details__icons__tipo{
        padding: 7px 0px 7px 15px;;
        display: inline-block;
        text-transform: uppercase;
        font-size: small;
    }
    .profile__details__icons__arrow{
        display: inline-block;
        float: right;
        border: 10px solid #4d6593;
        border-bottom-color: transparent;
        border-top-color: transparent;
        border-left: transparent;
        position: relative;
        top: 6px;
        left: 5px;
    }
    .profile__details__icons__value{
        display: inline-block;
        float: right;
        background-color: #4d6593;
        height: 33px;
        padding: 5px 10px;
        font-size: medium;
        font-weight: 600;
        color: #fff;
        line-height: 140%;
    }
    .profille__curriculo_title{
        font-size: large;
        font-weight: 600;
        text-transform: uppercase;
    }
    .profille__curriculo_subtitle{
        font-weight: 600;
    }

    .profile__videos{
        border-radius: 10px;
        background-color: #3d3d3d;
    }
    .iframe_yt{
        min-height: 420px;
        width: 100%;
        padding: 15px;
    }
    .thumb_yt{
        height: 120px;
        background-size: cover;
        background-repeat: no-repeat;
        filter: grayscale(1) blur(1px);
        cursor: pointer;
        transition: 0.3s;
    }
    .thumb_yt:hover{
        transition: 0.3s;
        background-size: cover;
        background-repeat: no-repeat;
        filter: grayscale(0) blur(0px);
    }
    .owl-carousel{
        max-height: 120px;
    }
    .owl-prev{
        position: absolute;
        top: 40%;
        left: -21px;
    }
    .owl-prev i {
        color: white;
    }
    .owl-next{
        position: absolute;
        top: 40%;
        right: -21px;
        color: white;
    }
    .owl-next i{
        color: white;
    }
    @media (min-width:1px) and (max-width:767px){
        .status_profile{
            margin-top: 50px;
        }
    }

    /*Works*/

    .works{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 15px;
    }
    .work__item{
        display: grid;
        grid-template-rows: 1fr;
        grid-template-columns: 1fr;
    }
    .work__inside{
        display: grid;
        align-items: end;
    }
    .work__image{
        background-size: cover;
        background-repeat: no-repeat;
    }

    .works__item__main{
        grid-row: 1 / 3;
        grid-column: 1 / 3;
    }
    .work__item__information{
        background-image: linear-gradient(#fff0 -20%, #000);
        padding: 5px 10px;
        color: white;
    }
    .work__item__information_main{
        padding: 25px 25px 25px 25px;
    }
    .work__Information__title{
        text-transform: uppercase;
    }
    .works__item__sec{
        height: 250px;
    }
    .rounded-base{
        border-radius: 0px 0px 10px 10px;
    }
    .xlarge-text{
        font-size: 36px
    }

    .thumb_yt {
    height: 120px;
    background-size: cover;
    background-repeat: no-repeat;
    filter: none !important;
    cursor: pointer;
    transition: 0.3s;
}
</style>
@endsection
@section('title', $profile['fancy_name']  )
@section('type', "perfil" )
@section('url', url($_SERVER['REQUEST_URI'])  )
@section('foto',  $profile->avatar)
@section('content')
    <div class="container padding-50"> <!-- CONTENT-BEGAN -->

        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="elenco__title">
                    <div class="elenco__title__icon">
                        <i class="fa fa-star fa-lg" style="color: #ffa916;"></i>
                    </div>
                    <div class="elenco__title__information">
                        <div class="elenco__title__information__name">
                            {!! $profile['fancy_name'] !!}<br>
                        </div>
                        <div class="elenco__title__information__profission">
                            <?php $ocupation = "";
                            if( $profile['occupation'] ){
                                $ocupation = $profile['occupation'];
                            } else if($profile['alternative_occupation']){
                                $ocupation =  $profile['alternative_occupation'];
                            }
                            echo $ocupation;
                            ?>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-8 col-sm-4 col-xs-12">
                <div class="float-right">
                    @if(Auth::user() && Auth::user()->level != '3')
                    <a  class="btn btn-access-red" id="bt-favoritar" style="color:#FFF">
                        <i class="fa fa-fw fa-heart d-sm-none"></i>
                        <i class="fa fa-fw fa-heart ml-1 d-none d-sm-inline-block"></i>
                        <span class="d-none d-sm-inline-block" style="text-transform: uppercase;color:#FFF">demonstrar interesse</span>
                    </a>
                    @endif
                        <div class="sharethis-inline-share-buttons"></div>
                </div>
            </div>
        </div>

        <div class="row" style="padding-top: 32px;">
            <div class="col-md-4">
                <div class="profile__images">
                    @forelse( $profile['medias']->sortByDesc('order') as $key => $images)
                        <?php $images = $images->toArray();  //dd($profile); ?>
                        <div class="profile__image" style="background-image: url('{{'/uploads/profiles/'.$profile['user_id'].'/'.$images['path'] }}')" data-img=" {{ url('/uploads/profiles/'.$profile['user_id'].'/'.$images['path'])  }}">
                        </div>
                    @empty
                        <div class="profile__image" style="background-image: url('{{ asset('images/sem-foto.png') }}')" data-img="{{ asset('images/sem-foto.png') }}">
                        </div>
                        <div class="profile__image">
                            Sem imagens
                        </div>
                    @endforelse
                </div>
            </div>


            <div class="col-md-8">
                <!-- Separador SM screen --><div class="d-none d-sm-block d-md-none d-lg-none d-xl-none"> <br> </div>

                <div class="row status_profile">
                    @foreach( $details as $key => $val)
                    <div class="col-md-{{ $val['size'] <= 4 ? $val['size'] * 3 : 12 }} col-sm-{{ $val['size'] <= 2 ? $val['size'] * 6 : 12 }} col-xs-12"  style="margin-bottom: 10px;">
                        <div class="profile__details__icons">
                            <div class="profile__details__icons__tipo">
                                <i class="fa fa-birthday-cake"></i>
                                {{ $val['title'] }}
                            </div>
                            <div class="profile__details__icons__value">
                                {{ $val['value'] }}
                            </div>
                            <div class="profile__details__icons__arrow"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row padding-15">
                    <!--<div class="col-md-12">
                        <div class="profille__curriculo_title">
                            Currículo
                        </div>
                    </div>-->
                @unless($profile['curriculum']=='')
                    <div class="col-md-12 padding-15">
                        <div class="profille__curriculo_subtitle"> 
                            Currículo:
                        </div>
                       {!! nl2br(e($profile['curriculum'])) !!}
                    </div>
                @endunless



         


                <!--@unless($profile['publicidade']=='')
                    <div class="col-md-12 padding-15">
                        <div class="profille__curriculo_subtitle">
                            Publicidade:
                        </div>
                        {{ $profile['publicidade'] }}
                    </div>
                @endunless-->
                </div>

                @unless($notices->isEmpty())
                    <div class="my-4">
                        <div class="profille__curriculo_subtitle mb-2">
                            Trabalhos:
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="works">
                                    @foreach ($notices as $key => $val)
                                        <?php if(!$val['media']){
                                            $val['media'] = 'default_notice.png';
                                        }?>
                                        <a href="<?php echo url( env('APP_PREFIX') . '/trabalho/' . $val['slug'] ); ?>" class="work__item works__item__sec">
                                            <div class="work__inside work__image" style="background-image: url('/public/uploads/notices/<?php echo $val['media'] ; ?>'); background-position: 50% 50%;">
                                                <div class="work__item__information">
                                                    <div class="work__Information__title bold">
                                                        <?php
                                                            $txt = $val['subtitle'];
                                                            $len = 25;
                                                            if( strlen( $txt ) <= $len ){
                                                                echo  $txt;
                                                            } else {
                                                                echo substr($txt, 0, $len).'...';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="work__Information__subtitle bold">
                                                <?php
                                                    $txt = $val['title'];
                                                    $len = 25;
                                                    if( strlen( $txt ) <= $len ){
                                                        echo  $txt;
                                                    } else {
                                                        echo substr($txt, 0, $len).'...';
                                                    }
                                                ?>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endunless

                @if( sizeof( $video ) )
                    <div class="profile__videos">
                        <div class="row padding-15">
                            <div class="col-md-12">
                                <div class="iframe_yt" >
                                    <iframe  width="100%" height="420px" id="player"
                                        src="{{ $video[0]->link }}?autoplay=0&fs=0&rel=0&origin=pharosevento.com.br">
                                    </iframe>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding: 0 30px;">
                                <div class="owl-carousel owl-theme owl-loaded owl-drag" style="display: none">
                                    @foreach( $video as $key => $val)
                                        <?php
                                        $id = explode('/', $val->link);
                                        $id = $id[ sizeof($id)-1 ];
                                        if($val->host == 'www.youtube.com'){
                                            $picture = 'https://img.youtube.com/vi/'.$id.'/0.jpg';
                                        } else {
                                            $picture = asset('/images/vimeo-logo.jpg');
                                            // $picture = 'https://i.vimeocdn.com/video/'.$id.'_590x332.jpg';
                                        }
                                        ?>
                                        <div class="thumb_yt yt_switch" ytid="{{ $val->link }}" style=" background-image: url( {{$picture}} ); background-position: 50% 50%;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div> <!-- CONTENT-END -->
@endsection

@section('js_after')
    <script>
        console.log('teste')
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                items: 4,
                // loop:true,
                margin:10,
                nav:true,
                dots: false,
                // autoplay:true,
                // infinite: true,
                navText: ['<i class="fa fa-angle-left fa-2x"></i>','<i class="fa fa-angle-right fa-2x"></i>'],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:4
                    }
                }
            }).show();

            $('.owl-nav').show();

            $('.yt_switch').on('click', (e) => {
                let src = e.target.attributes.ytid.value  + '?autoplay=1&fs=0&rel=0&origin=pharosevento.com.br'
                $('#player').attr('src', src)
            })
        });


    //    opcao de favoritar
        $('#bt-favoritar').click(function(){

            $.post("{{route('favoritar')}}", {usuario_id : '{{$profile['user_id']}}', logado_id: '{{ (Auth::user()) ? Auth::user()->id : ''}}' }, function(data){
              if(data.success){
                  $.notify({
                      // options
                      message: data.success
                  },{
                      // settings
                      type: 'danger'
                  });
              } else {
                  $.notify({
                      // options
                      message: data.error
                  },{
                      // settings
                      type: 'danger'
                  });
              }
            })
        })

        $('.profile__image').click(function(){
            let image = $(this).data('img')
            $('.profile__image').first().css('background-image', 'url("'+ image +'")');
        })
    </script>


    <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=589f326b79d6fb00120307bb&product=inline-share-buttons' async='async'></script>
@endsection
