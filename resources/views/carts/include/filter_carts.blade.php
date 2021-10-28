<link rel="stylesheet" type="text/css" href="js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" />
<div class="block block-rounded block-bordered">
    <div class="block-header block-header-default">
        <h3 class="block-title">Filtro</h3>
    </div>
    <div class="block-content">
       <form method="get">
           <div class="row">
              <div class="col-12">
                  <div class="row">
                     <div class="col-5">
                        <div class="form-group">
                            <label for="">Produtoras</label>
                            <select class="form-control" name="client_id" data-placeholder="Selecione uma produtora">
                                <option></option>
                                @forelse($clients as $client)
                                    <option value="{{$client->id}}" {{(app('request')->input('client_id')==$client->id?"selected":"")}}>{{$client->contact}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                     <div class="col-5">
                        <div class="form-group">
                            <label for="">Data de criação do carrinho</label>
                            <input class="form-control datepicker" name="created_date" value="{{ app('request')->input('created_date') }}" data-date-format="dd/mm/yyyy" readonly>
                        </div>
                    </div>
                     <div class="col-2 mt-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary " id="btn-external-filter">Aplicar</button>
                            <a href="{{route(Route::currentRouteName())}}" class="btn btn-default">Limpar</a>
                        </div>
                    </div>
                  </div>
              </div>
           </div>
       </form>
    </div>
</div>

