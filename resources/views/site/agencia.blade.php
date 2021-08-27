@extends('layouts.site')

@section('css_after')
    <style>
        .agencia-bg{
            position: absolute;
            right: 0px;
            top: -80px;
            height: 309%;
            /*filter: grayscale(1) brightness(1.2) blur(0.5px);*/
            
        }
        .agencia{
            padding: 0px 50px;
        }
        .foto-default{
            width: 100%;
            border: 10px solid white;
            box-shadow: 1px  1px 5px #3333;
            background-size: cover;
            background-position: 50% 50%;
        }
        .foto1{
            height: 820px;
        }
        .foto2{
            height: 300px;
        }
        .foto3{
            height: 300px;
        }
        .foto4{
            height: 300px;
        }
        @media (min-width:1px) and (max-width:767px){
            .row_default{
                position: unset!important;
                top: unset !important;
            }
            .foto4{

            }
            .agencia__title{
                text-align: center;
            }
            .agencia__paragrafo{
                text-align: center;
            }

            .owner__name{
                margin-top: 20px;
                text-align: center;
            }
            .owner__description{
                width: unset !important;
                text-align: center !important;
            }
        }
    </style>
@endsection

@section('content')
   
    <div class="container padding-15"> <!-- CONTENT-BEGAN -->
        <div class="row row_default" style="padding: 65px 0;">
            <div class="col-md-5">
                <img class="agencia-bg" src="<?php echo url( env('APP_PREFIX') . '/images/agencia-bg.jpg' ); ?>" alt="">

            </div>
            <div class="col-md-7">
                <div class="agencia">
                    <div class="agencia__title medium-text bold">
                        A PHAROS ELENCO
                    </div>
                    <div class="agencia__paragrafo">
                        <p>
                            Entramos no mercado de agenciamentos de atores com a certeza de realizar um excelente trabalho. Isto porque estamos preocupados em formar e preparar nossos agenciados para um melhor desenvolvimento na área de teledramaturgia e publicidade. Desta forma buscamos ser o farol que guia os sonhos e desejos profissionais de cada agenciado. Queremos iluminar esse árduo caminho na direção das realizações e melhores conquistas. Acreditamos que tudo é possível, desde que seja sonhado e trabalhado com profissionais que acreditem no seu potencial. Nós acreditamos em sonhos!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row_default" style="padding-bottom: 65px;">
            <div class="col-md-4">
                <!--  -->
            </div>
            <div class="col-md-8">
                <div class="foto-default foto1" style="background-image: url('<?php echo url( env('APP_PREFIX') . '/images/inside-agencia3.jpg'); ?>">
                    
                </div>
            </div>
        </div>
        <!--<div class="row mobile-show" style="display: none">
            <div class="col-md-12">
                <div class="agencia">
                    <div class="agencia__title medium-text bold">
                        Nosso Ambiente
                    </div>
                </div>
            </div>
        </div>-->
        <div class="row row_default" style="padding-bottom: 65px;">
            <div class="col-md-5">
                <div class="foto-default foto2" style="background-image: url('<?php echo url( env('APP_PREFIX') . '/images/inside-agencia1.jpg'); ?>">
                </div>
            </div>
            <div class="col-md-7">
                <div class="foto-default foto3" style="background-image: url('<?php echo url( env('APP_PREFIX') . '/images/inside-agencia2.jpg'); ?>">
                </div>
            </div>
        </div>

       
        <span class="mobile-show"><br></span>
    </div> <!-- CONTENT-END -->
@endsection

@section('js')
@endsection
