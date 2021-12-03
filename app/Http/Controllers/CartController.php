<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartStoreRequest;
use App\ImgCompressPath;
use App\Mail\CartProfiles;
use App\Profile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use PDF;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Cart::query()->where("finish_cart", true);

        if(isset($request->client_id) && !empty($request->client_id)){
            $query->where('client_id', '=', $request->client_id);
        }

        if(isset($request->created_date) && !empty($request->created_date)){
            $query->whereDate('created_at', '=', Carbon::createFromFormat('d/m/Y', $request->created_date)->format('Y-m-d'));
        }

        $carts = $query->orderBy('created_at', 'desc')->paginate(15);

        $clients = Client::all();
        return view('carts.index', compact('carts','clients'));
    }
    public function pendings(Request $request)
    {
        $query = Cart::query()->where("finish_cart", false);

        if(isset($request->client_id) && !empty($request->client_id)){
            $query->where('client_id', '=', $request->client_id);
        }

        if(isset($request->created_date) && !empty($request->created_date)){
            $query->whereDate('created_at', '=', Carbon::createFromFormat('d/m/Y', $request->created_date)->format('Y-m-d'));
        }

        $carts = $query->orderBy('created_at', 'desc')->paginate(15);

        $clients = Client::all();
        return view('carts.pending', compact('carts','clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $profiles = Profile::all();
        $clients = Client::all();

        return view('carts.create', compact('profiles', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function storeOld(Request $request)
//    {
//
//        request()->validate([
//            'name' => 'required',
//            'client_id' => 'required',
//            'profile_id' => 'required_without_all',
//        ]);
//
//        // DB::beginTransaction();
//
//        $cart = Cart::create($request->all());
//        $cart->profiles()->sync($request->profile_id);
//
//        $message = "O pedido foi salvo na lista de carrinhos!";
//
//        $this->savePDFPhotos($cart, $request->get('fotos'));
//
//        if ($request->action == 'create_send') {
//            if (!$this->send($cart)) {
//                return redirect()->route('carts.index')
//                    ->with('warning', 'Ocorreu um erro ao enviar o pedido!');
//            }
//
//            $message = "O pedido foi criado e enviado para produtora!";
//        }
//
//        return redirect()->route('carts.index')
//            ->with('success', $message);
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCartDraft(Request $request)
    {
        try {
            $dataCreate = array(
                "photos_select" => serialize($request->get('fotos')),
                "name" => $request->get('name'),
                "finish_cart" => false,
            );
            
            $cart = Cart::create($dataCreate);
           
            if($request->get('profile_id')){
                $cart->profiles()->sync($request->profile_id);
            }

            $this->savePDFPhotos($cart, $request->get('fotos'));


            return response()->json([
                'status' => 3,
                'id' => $cart->id
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 1,
                'message'   =>'Ocorreu um erro ao salvar a imagem'
            ]);
        }


    }
    public function store(Request $request)
    {
        if ($request->action == 'create_send') {

            $messages = [
                'name.required' => 'Campo nome é obrigatório',
                'fotos.required' => 'Selecione pelo menos uma foto',
                'profile_id.required' => 'Selecione pelo menos uma perfil',
                'client_ids.required' => 'Selecione pelo menos uma produtora',
            ];

            $this->validate($request, [
                'name' => 'required',
                "fotos" => [
                    'required',
                    'array'
                ],
                "profile_id" => [
                    'required',
                    'array'
                ],
                "client_ids" => [
                    'required',
                    'array'
                ],
            ], $messages);
        }
        $errors = 0;
        $sendEmail = false;

        foreach ($request->client_ids as $client_id){
            $dataCreate = array(
                "client_id" => $client_id,
                "name" => $request->name,
                "photos_select" => serialize($request->get('fotos'))
            );
            $cart = Cart::create($dataCreate);
            $cart->profiles()->sync($request->profile_id);
            $foto_principal = $this->savePDFPhotos($cart, $request->get('fotos'));
            if ($request->action == 'create_send') {
                $sendEmail = true;
                if (!$this->send($cart, $foto_principal)) {
                    $errors++;
                    $sendEmail = false;
                }
            }
        }
        if($errors > 0){
            return response()->json([
               'status' => 1,
               'message'   =>'Ocorreu um erro ao enviar o pedido!'
            ]);
        }else if($errors == 0 && $sendEmail){
            return response()->json([
                'status' => 2,
                'message'   =>'O pedido foi criado e enviado para produtora!'
            ]);
        }else{
            return response()->json([
                'status' => 3,
                'message' =>'O pedido foi salvo na lista de carrinhos!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        $cart = Cart::find($cart->id);
        $clients = Client::all();
        $profiles = Profile::all();
        $selectedProfiles = [];
        $selectedProfilesIds = [];

        foreach($cart->profiles as $profile) {
            $selectedProfiles[] = $profile;
            $selectedProfilesIds[] = $profile->id;
        }

        $profilesSelects = collect($selectedProfiles);
        $profilesSelectsIds = collect($selectedProfilesIds);

        return view('carts.edit', compact("cart","clients", "profiles", "profilesSelects","profilesSelectsIds"));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\JsonResponse
     * 1 - Atualiza os dados
     * 2 - Sincroniza os perfis selecionados
     * 3 - Remove o diretório anterior
     * 4 - Cria um novo diretório de pdf e imagens selecionadas
     */
    public function update(Request $request, Cart $cart)
    {

        if ($request->action == 'edit_send') {

            $messages = [
                'name.required' => 'Campo nome é obrigatório',
                'fotos.required' => 'Selecione pelo menos uma foto',
                'profile_id.required' => 'Selecione pelo menos uma perfil',
                'client_id.required' => 'Selecione pelo menos uma produtora',
            ];

            $this->validate($request, [
                'name' => 'required',
                "fotos" => [
                    'required',
                    'array'
                ],
                "profile_id" => [
                    'required',
                    'array'
                ],
                "client_id" => [
                    'required',
                ],
            ], $messages);
        }
        $dataUpdate = array(
            "client_id" => $request->client_id,
            "name" => $request->name,
            "photos_select" => serialize($request->get('fotos'))
        );

        $cart->update($dataUpdate);

        $cart->profiles()->sync($request->profile_id);
        // deleta diretório de pdf e imagens comprimidas
        $this->deletedirectoryCartProfile($cart);
        // cria um novo diretório
        $this->savePDFPhotos($cart, $request->get('fotos'));
      
        if(count($request->get('client_ids')) > 0){
            $this->createCartsClients($request);
        }

        if ($request->action == 'edit_send') {
            if (!$this->send($cart)) {
                return response()->json([
                    'status' => 1,
                    'message'   =>'Ocorreu um erro ao enviar o pedido!'
                ]);
            }
            return response()->json([
                'status' => 2,
                'message'   =>'O carrinho foi atualizado com sucesso e enviado para produtora!!'
            ]);
        }else{
            return response()->json([
                'status' => 3,
                'message' =>'O pedido foi atualizado na lista de carrinhos!'
            ]);
        }
    }
    public function createCartsClients($request){
        foreach ($request->client_ids as $client_id){
            $dataCreate = array(
                "client_id" => $client_id,
                "name" => $request->name,
                "photos_select" => serialize($request->get('fotos'))
            );

            $cart = Cart::create($dataCreate);
            $cart->profiles()->sync($request->profile_id);
            $foto_principal = $this->savePDFPhotos($cart, $request->get('fotos'));
           
            if ($request->action == 'edit_send') {
                if (!$this->send($cart, $foto_principal)) {
                    return response()->json([
                        'status' => 1,
                        'message'   =>'Ocorreu um erro ao enviar o pedido para a produtora!'
                    ]);
                }
            }
            
        }
    }

    public function updateItemPhoto(Request $request, Cart $cart)
    {
        $cart->update(["photos_select" => serialize($request->get('fotos'))]);
        $cart->profiles()->sync($request->profile_id);
    }

    public function deletedirectoryCartProfile($cart){
        Storage::disk('public')->deleteDirectory("/carts/{$cart->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function sendCart(Cart $cart)
    {
        if (!$this->send($cart)) {
            return redirect()->route('carts.index')
                ->with('warning', 'Ocorreu um erro ao enviar o pedido!');
        }

        return redirect()->route('carts.index')
            ->with('success', 'O pedido foi enviado para a produtora!');
    }

    /**
     * Gera e salva o PDF
     *
     * @param Cart $cart
     * @return void
     */
    private function savePDFPhotos(Cart $cart, $profiles_photos){
        if ($cart->profiles->count() > 0) {
            try {
                $path = public_path("uploads/carts/{$cart->id}");
                File::makeDirectory($path, 0775, true);

                foreach ($cart->profiles as $profile) {
                    $name = str_slug($profile->user->name);
                    $fotos = $profiles_photos[$profile->user_id];

                    $fotos_grupos = [];
                    if($fotos){
                        for ($i=0; $i < count($fotos); $i++) {
                            if($i <= 4){
                                  $dataPhoto = explode("\\", $fotos[$i]["src"]);
                                  if(count($dataPhoto) == 1){
                                      $dataPhoto = explode("/", $fotos[$i]["src"]);
                                  }

                                  $imgCompress = ImgCompressPath::where('user_id', $profile->user_id)
                                                                ->where('img_name', $dataPhoto[4])
                                                                ->first();
                               if($imgCompress == null){
                                      create_dir_comp($profile->user_id);
                                      $imgPath = "uploads/profiles/{$profile->user_id}/compress";
                                      $dimensions = return_dimension(public_path($fotos[$i]["src"]));
                                      $foto = create_thumbnail(public_path($fotos[$i]["src"]), $imgPath."/comp-".$dataPhoto[4], $dimensions["width"], $dimensions["height"]);
                                      array_push($fotos_grupos, $foto->dirname."/".$foto->basename);
                                      $imgCompress = array(
                                          "url_compress" => $foto->dirname."/".$foto->basename,
                                          "img_name" => $dataPhoto[4],
                                          "user_id" => $profile->user_id
                                      );
                                      ImgCompressPath::create($imgCompress);
                                }else{

                                array_push($fotos_grupos, $imgCompress->url_compress);
                                }
                            }
                        }
                        $foto_principal =  $fotos_grupos[0]; // foto principal
                        unset($fotos_grupos[0]); // remove a primeira foto do array
                        PDF::loadView('emails.profile', compact('profile', 'foto_principal', 'fotos_grupos'))->setPaper('a4', 'landscape')->save("{$path}/{$name}.pdf");
                        return $foto_principal;
                    }
                }

            }catch(Exception $e){
                return redirect()->back()->withInput()->with('error', 'Não foi prosseguir com a solicitação ERRO. Linha: ' . $e->getLine() . ' - Mensagem: ' . $e->getMessage());
            }
        }
    }

    private function savePDF(Cart $cart)
    {
        if ($cart->profiles->count() > 0) {
            $path = public_path("uploads/carts/{$cart->id}");

            File::makeDirectory($path, 0775, true);

            foreach ($cart->profiles as $profile) {
                $name = str_slug($profile->user->name);

                PDF::loadView('emails.profile', compact('profile'))->save("{$path}/{$name}.pdf");
            }
        }

        return;
    }

    public function previewPDF(Cart $cart, Profile $profile)
    {
        $path = public_path("uploads/carts/{$cart->id}/");
        $nameFile = str_slug($profile->user->name);

        return response()->file("{$path}{$nameFile}.pdf");
    }

    public function send(Cart $cart, $foto_principal)
    {
        // return new \App\Mail\CartProfiles($cart);

        try {
            Mail::to($cart->client->user->email)->send(new CartProfiles($cart, $foto_principal));

            $cart->sent = true;
            $cart->save();
        } catch (Exception $e) {
            Log::info('Falha ao enviar pedido via e-mail.', [$e->getMessage()]);

            return false;
        }

        return true;
    }

    public function delete(Request $cart)
    {
        //obtem dados do carrinho
        $carrinho = Cart::find($cart->id);  
        $carrinho->delete();

        Storage::disk('public')->deleteDirectory("/carts/{$cart->id}"); 
        

        return redirect()->route('carts.index')
            ->with('success', 'Carrinho excluido com sucesso!'); 

    }

    public function deleteCartPending(Request $cart)
    {
        //obtem dados do carrinho
        $carrinho = Cart::find($cart->id);  
        $carrinho->delete();

        Storage::disk('public')->deleteDirectory("/carts/{$cart->id}"); 
        

        return redirect()->route('carts.pendings')
            ->with('success', 'Carrinho excluido com sucesso!'); 

    }
    /**
     *  1 - Find no carrinho anterior
     *  2 - Adiciona ao array $profiles_id os id's para utilização do sync
     *  3 - Salva novo carrinho com status de não enviado
     *  4 - Copia o diretório anterior para o armazenamento dos pdf's e fotos
     **/
    public function duplicate(Request $cart){

        $carrinho = Cart::find($cart->id);
        $profiles_id = [];
        foreach ($carrinho->profiles as $profile){
            array_push($profiles_id, $profile->id);
        }

        $novoCarrinho = array(
            "name" => $carrinho->name,
            "client_id" => $carrinho->client_id,
            "photos_select" => $carrinho->photos_select,
            "sent" => 0
        );
        $cartNew = Cart::create($novoCarrinho);
        $cartNew->profiles()->sync($profiles_id);

        // copia o diretório do pdf e imagens comprimidas
        $path = public_path("uploads/carts/{$cart->id}");
        $pathNew = public_path("uploads/carts/{$cartNew->id}");
        if (!is_dir($pathNew)) {
            File::makeDirectory($pathNew, 0775, true);
        }

        recurse_copy($path, $pathNew);

        return redirect()->route('carts.index')
            ->with('success', 'Carrinho duplicado com sucesso!');
    }
}
