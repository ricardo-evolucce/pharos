<?php
use Carbon\Carbon;
?>
{!! Form::open(['method' => 'post', 'name' => 'edit-password-data', 'id' => 'edit-password-data', 'onSubmit' => 'return false'])!!}
    {!! Form::hidden('user_id', $user->id )!!}

        {!! Form::hidden('name', 'aa' )!!}
    {!! Form::hidden('email', 'ricardomuller90@gmail.com' )!!}


   <div class="block block-rounded block-bordered">
       <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
           <li class="nav-item">
               <a class="nav-link active" href="#profile">Senha</a>
           </li>
       </ul>
       <div class="block-content tab-content">
           <div class="tab-pane active" id="profile" role="tabpanel">

               <div class="row push">

                   <!-- DADOS PESSOAIS -->
                   <div class="col-md-6">
                       <h2 class="content-heading pt-0">Nova senha</h2>

                       <div class="form-group form-row">
                           <div class="col-12">
                               <label for="name">Senha *</label>
                               <input class="form-control" name="password" placeholder="" type="password">
                           </div>
                        
                       </div>

                   </div>
                   <!-- /DADOS PESSOAIS -->

                  
                   <!-- /INFORMAÇÕES FÍSICAS -->
               </div>


        

           </div>
       </div>
    </div>
    <button type="submit" class="btn btn-access" id="save_edit_password_data" style="float: right;">{{__('Salvar') }}</button>
{!! Form::close()!!}
<br>
<br>

<script type="text/javascript" async>

    $('Form[name="edit-password-data"]').trigger("reset")
    $('Form[name="edit-password-data"]').submit(function(e){
        e.preventDefault()
        console.log('tentativa enviar detalhes')
        $('#save_edit_password_data').attr('disabled', 'disabled')
        var formData = new FormData(document.getElementById('edit-password-data'))
        $.ajax({
            method: "POST",
            url: "{!! url('/api/site/edit-password-data') !!}",
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
            $('#save_edit_password_data').removeAttr('disabled')
        })
        .fail( function( msg ) {
            console.log(msg)
            $('.btn-primary').removeAttr('disabled');
            $.notify({
                message: msg
            },{type: 'danger' });
            $('#save_edit_password_data').removeAttr('disabled')
        });
    })

</script>
