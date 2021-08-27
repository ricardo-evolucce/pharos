<?php
use Carbon\Carbon;
?>
{!! Form::open(['method' => 'post', 'name' => 'edit-agenciado-data', 'id' => 'edit-agenciado-data', 'onSubmit' => 'return false'])!!}
    {!! Form::hidden('user_id', $user->id )!!}

   <div class="block block-rounded block-bordered">
       <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
           <li class="nav-item">
               <a class="nav-link active" href="#profile">Perfil</a>
           </li>
       </ul>
       <div class="block-content tab-content">
           <div class="tab-pane active" id="profile" role="tabpanel">

               <div class="row push">

                   <!-- DADOS PESSOAIS -->
                   <div class="col-md-6">
                       <h2 class="content-heading pt-0">Dados pessoais</h2>

                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="name">Nome completo *</label>
                               <input type="text" class="form-control" name="name" placeholder="" value="{{ old('name', $profile->user->name ?? Auth::user()->name) }}">
                           </div>
                           <div class="col-6">
                               <label for="fancy_name">Nome artístico *</label>
                               <input type="text" class="form-control" name="fancy_name" placeholder="" value="{{ old('fancy_name', $profile->fancy_name) }}">
                           </div>
                       </div>

                       <div class="form-group">
                           <label for="email">E-mail *</label>
                           <input type="email" class="form-control" name="email" placeholder="" value="{{ old('email', $profile->user->email ?? Auth::user()->email) }}">
                       </div>

                       <div class="form-group form-row">
                           <div class="col-4">
                               <label for="rg">Nº identidade *</label>
                               <input type="text" class="form-control" name="rg" placeholder="" value="{{ old('rg', $profile->rg) }}">
                           </div>
                           <div class="col-4">
                               <label for="organ">Órgão expedidor *</label>
                               <input type="text" class="form-control" name="organ" placeholder="" value="{{ old('organ', $profile->organ) }}">
                           </div>
                           <div class="col-4">
                               <label for="cpf">CPF *</label>
                               <input type="text" class="form-control" name="cpf" placeholder="" value="{{ old('cpf', $profile->cpf) }}">
                           </div>
                           <div class="col-4">
                               <label for="cpf">DRT</label>
                               <input type="text" class="form-control" name="drt" placeholder="" value="{{ old('drt', $profile->drt) }}">
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="date_birth">Data de nascimento * </label>
                               <input type="date" class="form-control" name="date_birth" placeholder="" value="{{ old('date_birth', \Carbon\Carbon::parse($profile->date_birth)->format('Y-m-d')) }}">
                           </div>
                           <div class="col-6">
                               <label for="gender">Sexo *</label>
                               <select class="form-control" id="gender" name="gender">
                                   <option value="">-- Selecione --</option>
                                   @foreach($gender_list as $key => $value)
                                       @if ($key === $profile->gender)
                                       <option value="{{$key}}" selected>{{$value}}</option>
                                       @else
                                       <option value="{{$key}}">{{$value}}</option>
                                       @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="address">Endereço *</label>
                               <input type="text" class="form-control" name="address" placeholder="" value="{{ old('address', $profile->address) }}">
                           </div>

                           <div class="col-3">
                               <label for="zip_code">CEP *</label>
                               <input type="text" class="form-control" name="zip_code" placeholder="" value="{{ old('zip_code', $profile->zip_code) }}">
                           </div>

                           <div class="col-3">
                               <label for="phone_number">Telefone *</label>
                               <input type="text" class="form-control" name="phone_number" placeholder="" value="{{ old('phone_number', $profile->phone_number) }}">
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-4">
                               <label for="marital_status">Estado civil *</label>
                                   <select class="form-control" id="marital_status" name="marital_status">
                                   <option value="">-- Selecione --</option>

                                   @foreach($marital_status_list as $key => $value)
                                       @if ($key === $profile->marital_status)
                                       <option value="{{$key}}" selected>{{$value}}</option>
                                       @else
                                       <option value="{{$key}}">{{$value}}</option>
                                       @endif
                                   @endforeach
                               </select>
                           </div>
                           <div class="col-4">
                               <label for="education">Escolaridade *</label>
                               <select class="form-control" id="education" name="education">
                                   <option value="">-- Selecione --</option>
                                   @foreach($education_list as $key => $value)
                                       @if ($key === $profile->education)
                                       <option value="{{$key}}" selected>{{$value}}</option>
                                       @else
                                       <option value="{{$key}}">{{$value}}</option>
                                       @endif
                                   @endforeach 
                               </select>
                           </div>
                           <div class="col-4">
                               <label for="city_birth">Natural de *</label>
                               <input type="text" class="form-control" name="city_birth" placeholder="" value="{{ old('city_birth', $profile->city_birth) }}">
                           </div>
                       </div>

            

                       <div class="form-group form-row">
                           <div class="col-12">
                               <label for="abilities">Outras aptidões</label>
                               <input type="text" class="form-control" name="abilities" placeholder="" value="{{ old('abilities', $profile->abilities) }}">
                           </div>
                       </div>

                                    <div class="form-group form-row">
                           <div class="col-12">
                               <label for="curriculum">Curriculo</label>
                               <textarea class="form-control" name="curriculum" placeholder="" >{{ old('curriculum', $profile->curriculum) }}</textarea>
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="languages">Outros Idiomas</label>
                               <input type="text" class="form-control" name="languages" placeholder="" value="{{ old('languages', $profile->languages) }}">
                           </div>

                           <div class="col-6">
                               <label for="favorite_team">Time preferido</label>
                               <input type="text" class="form-control" name="favorite_team" placeholder="" value="{{ old('favorite_team', $profile->favorite_team) }}">
                           </div>
                       </div>
                   </div>
                   <!-- /DADOS PESSOAIS -->

                   <!-- INFORMAÇÕES FÍSICAS -->
                   <div class="col-md-6">
                       <h2 class="content-heading pt-0">Informações físicas</h2>

                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="height">Altura (Ex.: 1.72) *</label>
                               <input type="text" class="form-control" name="height" placeholder="" value="{{ old('height', $profile->height) }}">
                           </div>
                           <div class="col-6">
                               <label for="weight">Peso (Kg) *</label>
                               <input type="text" class="form-control" name="weight" placeholder="" value="{{ old('weight', $profile->weight) }}">
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-4">
                               <label for="shirt">Camisa *</label>
                               <input type="text" class="form-control" name="shirt" placeholder="" value="{{ old('shirt', $profile->shirt) }}">
                           </div>
                           <div class="col-4">
                               <label for="pants">Calça *</label>
                               <input type="text" class="form-control" name="pants" placeholder="" value="{{ old('pants', $profile->pants) }}">
                           </div>
                           <div class="col-4">
                               <label for="feet">Sapato *</label>
                               <input type="text" class="form-control" name="feet" placeholder="" value="{{ old('feet', $profile->feet) }}">
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-3">
                               <label for="dummy">Manequim *</label>
                               <input type="text" class="form-control" name="dummy" placeholder="" value="{{ old('dummy', $profile->dummy) }}">
                           </div>
                           <div class="col-3">
                               <label for="bust">Busto (cm)</label>
                               <input type="text" class="form-control" name="bust" placeholder="" value="{{ old('bust', $profile->bust) }}">
                           </div>
                           <div class="col-3">
                               <label for="waist">Cintura (cm)</label>
                               <input type="text" class="form-control" name="waist" placeholder="" value="{{ old('waist', $profile->waist) }}">
                           </div>
                           <div class="col-3">
                               <label for="hip">Quadril (cm)</label>
                               <input type="text" class="form-control" name="hip" placeholder="" value="{{ old('hip', $profile->hip) }}">
                           </div>

                       </div>

                       <div class="form-group form-row">
                           <div class="col-4">
                               <label for="skin_color">Cor da pele *</label>
                               <input type="text" class="form-control" name="skin_color" placeholder="" value="{{ old('skin_color', $profile->skin_color) }}">
                           </div>
                           <div class="col-4">
                               <label for="eye_color">Cor dos olhos *</label>
                               <input type="text" class="form-control" name="eye_color" placeholder="" value="{{ old('eye_color', $profile->eye_color) }}">
                           </div>
                           <div class="col-4">
                               <label for="hair_color">Cor do cabelo *</label>
                               <select class="form-control" id="hair_color" name="hair_color">
                                   <option value="" selected="selected"> -- Selecione --</option>
                                   @foreach($hair_color_list as $key => $value)
                                       @if ($key === $profile->hair_color)
                                       <option value="{{$key}}" selected>{{$value}}</option>
                                       @else
                                       <option value="{{$key}}">{{$value}}</option>
                                       @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>

                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="hair_type">Tipo de cabelo *</label>
                               <input type="text" class="form-control" name="hair_type" placeholder="" value="{{ old('hair_type', $profile->hair_type) }}">
                           </div>
                           <div class="col-6">
                               <label for="hair_size">Tamanho do cabelo *</label>
                               <input type="text" class="form-control" name="hair_size" placeholder="" value="{{ old('hair_size', $profile->hair_size) }}">
                           </div>
                       </div>
                       <div class="form-group form-row">
                           <div class="col-3">
                               <label for="tattoo" class="d-block">Possui tatuagens? *</label>

                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="tattoo_active" name="tattoo" {{$profile->tattoo ? 'checked' : ''}} value="1">
                                   <label class="custom-control-label" for="tattoo_active">Sim</label>
                               </div>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="tattoo_inactive" name="tattoo" {{!$profile->tattoo ? 'checked' : ''}} value="0">
                                   <label class="custom-control-label" for="tattoo_inactive">Não</label>
                               </div>
                           </div>
                           <div class="col-9">
                               <label for="name">Quais locais do corpo?</label>
                               <input type="text" class="form-control" name="tattoo_location" placeholder="" value="{{ old('tattoo_location', $profile->tattoo_location) }}">
                           </div>
                       </div>

                   </div>
                   <!-- /INFORMAÇÕES FÍSICAS -->
               </div>

               <div class="row push">

                   <!-- INFORMAÇÕES ADICIONAIS -->
                   <div class="col-md-6">
                       <h2 class="content-heading pt-0">Informações adicionais</h2>
                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="practice_sports">Pratica algum esporte? *</label>
                               <input type="text" class="form-control" name="practice_sports" placeholder="" value="{{ old('practice_sports', $profile->practice_sports) }}">
                           </div>
                           <div class="col-6">
                               <label for="play_instrument">Toca algum instrumento musical? *</label>
                               <input type="text" class="form-control" name="play_instrument" placeholder="" value="{{ old('play_instrument', $profile->play_instrument) }}">
                           </div>
                       </div>
                       <div class="form-group form-row">
                           <div class="col-4">
                               <label for="film_outside" class="d-block">Grava em outras locais? *</label>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="film_outside_active" name="film_outside" {{$profile->film_outside ? 'checked' : ''}} value="1">
                                   <label class="custom-control-label" for="film_outside_active">Sim</label>
                               </div>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="film_outside_inactive" name="film_outside" {{!$profile->film_outside ? 'checked' : ''}} value="0">
                                   <label class="custom-control-label" for="film_outside_inactive">Não</label>
                               </div>
                           </div>
                           <div class="col-4">
                               <label for="make_figuration" class="d-block">Aceita fazer figuração? *</label>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="make_figuration_active" name="make_figuration" {{$profile->make_figuration ? 'checked' : ''}} value="1">
                                   <label class="custom-control-label" for="make_figuration_active">Sim</label>
                               </div>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="make_figuration_inactive" name="make_figuration" {{!$profile->make_figuration ? 'checked' : ''}} value="0">
                                   <label class="custom-control-label" for="make_figuration_inactive">Não</label>
                               </div>
                           </div>
                           <div class="col-4">
                               <label for="make_event" class="d-block">Aceita fazer eventos? *</label>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="make_event_active" name="make_event" {{$profile->make_event ? 'checked' : ''}} value="1">
                                   <label class="custom-control-label" for="make_event_active">Sim</label>
                               </div>
                               <div class="custom-control custom-radio custom-control-inline custom-control-primary">
                                   <input type="radio" class="custom-control-input" id="make_event_inactive" name="make_event" {{!$profile->make_event ? 'checked' : ''}} value="0">
                                   <label class="custom-control-label" for="make_event_inactive">Não</label>
                               </div>
                           </div>
                       </div>
                   </div>
                   <!-- /INFORMAÇÕES ADICIONAIS -->

                   <!-- DADOS BANCÁRIOS -->
                   <div class="col-md-6">
                       <h2 class="content-heading pt-0">Dados bancários</h2>
                       <div class="form-group form-row">
                           <div class="col-6">
                               <label for="bank_nro">Banco *</label>
                               <input type="text" class="form-control" name="bank_nro" placeholder="" value="{{ old('bank_nro', $profile->bank_nro) }}">
                           </div>
                           <div class="col-6">
                               <label for="back_agency">Agência *</label>
                               <input type="text" class="form-control" name="back_agency" placeholder="" value="{{ old('back_agency', $profile->back_agency) }}">
                           </div>
                       </div>
                       <div class="form-group form-row">
                           <div class="col-4">
                               <label for="bank_account">Conta corrente *</label>
                               <input type="text" class="form-control" name="bank_account" placeholder="" value="{{ old('bank_account', $profile->bank_account) }}">
                           </div>
                           <div class="col-4">
                               <label for="bank_holder_name">Nome do titular *</label>
                               <input type="text" class="form-control" name="bank_holder_name" placeholder="" value="{{ old('bank_holder_name', $profile->bank_holder_name) }}">
                           </div>
                           <div class="col-4">
                               <label for="bank_holder_cpf">CPF do titular *</label>
                               <input type="text" class="form-control" name="bank_holder_cpf" placeholder="" value="{{ old('bank_holder_cpf', $profile->bank_holder_cpf) }}">
                           </div>
                       </div>
                   </div>
                   <!-- /DADOS BANCÁRIOS -->

               </div>

               <div class="row push">
                   <!-- MENORIDADE -->
                   <div class="col-md-12">
                       <h2 class="content-heading pt-0">Menoridade</h2>
                       <div class="form-group form-row">
                           <div class="col-md-3">
                               <label for="tutor_name">Nome do responsável</label>
                               <input type="text" class="form-control" name="tutor_name" placeholder="" value="{{ old('tutor_name', $profile->tutor_name) }}">
                           </div>
                           <div class="col-md-3">
                               <label for="tutor_rg">Nº identidade responsável</label>
                               <input type="text" class="form-control" name="tutor_rg" placeholder="" value="{{ old('tutor_rg', $profile->tutor_rg) }}">
                           </div>
                           <div class="col-md-3">
                               <label for="tutor_organ">Órgão Expedidor</label>
                               <input type="text" class="form-control" name="tutor_organ" placeholder="" value="{{ old('tutor_organ', $profile->tutor_organ) }}">
                           </div>
                           <div class="col-md-3">
                               <label for="tutor_cpf">CPF do responsável</label>
                               <input type="text" class="form-control" name="tutor_cpf" placeholder="" value="{{ old('tutor_cpf', $profile->tutor_cpf) }}">
                           </div>
                       </div>
                   </div>
                   <!-- /MENORIDADE -->
               </div>

           </div>
       </div>
    </div>
    <button type="submit" class="btn btn-access" id="save_edit_agenciado_data" style="float: right;">{{__('profile.save_profile') }}</button>
{!! Form::close()!!}
<br>
<br>

<script type="text/javascript" async>

    $('Form[name="edit-agenciado-data"]').trigger("reset")
    $('Form[name="edit-agenciado-data"]').submit(function(e){
        e.preventDefault()
        console.log('tentativa enviar detalhes')
        $('#save_edit_agenciado_data').attr('disabled', 'disabled')
        var formData = new FormData(document.getElementById('edit-agenciado-data'))
        $.ajax({
            method: "POST",
            url: "{!! url('/api/site/edit-agenciado-data') !!}",
            data: formData,
            processData: false,
            contentType: false,
            // enctype: 'multipart/form-data'
        })
        .done( function( result ) {
            console.log(result)
            $('.btn-primary').removeAttr('disabled');
            if(result.error){
                console.log(result)
                showMessagesError(result)
            } else {
                $.notify({
                    message: result.success
                },{type: 'success' });
                // $('#form_register_agenciado').unbind('submit').submit()
            }
            $('#save_edit_agenciado_data').removeAttr('disabled')
        })
        .fail( function( msg ) {
            console.log(msg)
            $('.btn-primary').removeAttr('disabled');
            $.notify({
                message: msg
            },{type: 'danger' });
            $('#save_edit_agenciado_data').removeAttr('disabled')
        });
    })

</script>
