<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Client;
use App\Http\Controllers\Controller;
use App\Mail\CartProfiles;
use App\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::orderBy('created_at', 'desc')->paginate(15);

        return view('carts.index', compact('carts'));
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
    public function store(Request $request)
    {
        
        request()->validate([
            'name' => 'required',
            'client_id' => 'required',
            'profile_id' => 'required_without_all',
        ]);

        // DB::beginTransaction();
        
        $cart = Cart::create($request->all());
        $cart->profiles()->sync($request->profile_id);

        dd($request->get('fotos'));
        exit();
        
        $message = "O pedido foi salvo na lista de carrinhos!";

        $this->savePDFPhotos($cart, $request->get('fotos'));

        if ($request->action == 'create_send') {
            if (!$this->send($cart)) {
                return redirect()->route('carts.index')
                    ->with('warning', 'Ocorreu um erro ao enviar o pedido!');
            }

            $message = "O pedido foi criado e enviado para produtora!";
        }

        return redirect()->route('carts.index')
            ->with('success', $message);
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
        return view('admin.carts.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
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
        // dd($profiles_photos);
        if ($cart->profiles->count() > 0) {

            $path = public_path("uploads/carts/{$cart->id}");
            File::makeDirectory($path, 0775, true);
            
            // $path = public_path("uploads/carts/760");

            foreach ($cart->profiles as $profile) {
                $name = str_slug($profile->user->name);
                
                $fotos = $profiles_photos[$profile->user_id];
                $foto_principal = array_splice($fotos,0,1)[0]; //sempre a primeira do array
                $fotos_grupos = array_chunk(array_splice($fotos,0,4), 2); //recupera apenas 4 fotos
                
                PDF::loadView('emails.profile', compact('profile', 'foto_principal', 'fotos_grupos'))->setPaper('a4', 'landscape')->save("{$path}/{$name}.pdf");
                // dd("{$path}/{$name}.pdf");
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

    public function send(Cart $cart)
    {
        // return new \App\Mail\CartProfiles($cart);

        try {
            Mail::to($cart->client->user)->send(new CartProfiles($cart));

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
}
