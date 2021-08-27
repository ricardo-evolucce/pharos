<?php ?>
<main id="main-container" class="footer">
    <div class="container padding-15">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <img class="footer__image" src="<?php echo url( env('APP_PREFIX') . '/images/logo-white.png' ); ?>" style="" alt="">
            </div>

            <div class="col-md-3  col-sm-12 mobile-hide">
                <ul class="footer__map">
                    <li>
                        <a href='{!! route("elencos") !!}'>ELENCO</a> 
                    </li>
                    <li>
                        <a href='{!! route("trabalhos") !!}'>TRABALHOS</a> 
                    </li>
                    <li>
                        <a href='{!! route("agencia") !!}'>AGÊNCIA</a> 
                    </li>
                    <li>
                        <a href='{!! route("contato") !!}'>CONTATO</a> 
                    </li>
                    <li>
                        <a href='{!! route("contato") !!}'>FAÇA O SEU CADASTRO</a>  
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12 mobile-hide">
                <ul class="footer__endereco">
                    <li>
                        <i class="fa fa-li fa-pencil-alt "></i>
                        <label>
                            Avenida Getúlio Vargas, 1151<br>
                            Sala 506, Menino Deus<br>
                            Porto Alegre/RS - Brasil
                        </label>
                    </li>
                    <li>
                        <i class="fa fa-li fa-phone "></i>
                        <label>
                            (51) 3209-8378<br>
                            (51) 98435-1833
                        </label>
                    </li>
                    <li>
                        <i class="fa fa-li fa-envelope "></i>
                        <label>
                            {!! env('EMAILCONTATOPHAROS', 'claudia@pharoselenco.com.br') !!}
                        </label>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="footer__social">
                    <a href="{!! env('INSTAGRAM', 'https://instagram.com')!!}">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                    <a href="{!! env('FACEBOOK', 'https://facebook.com')!!}">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
<main id="main-container" class="allrightsreserved">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="centerGrid">
                Pharos Elenco,  2015 - {!! date('Y') !!} &#169; - Todos os Direitos reservados
                <!-- - edrobeda -->
                </div>
            </div>
        </div>
    </div>
</main>
