<?php

namespace App\Http\Controllers\Api;

use App\Favorito;
use App\ImgCompressPath;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use Intervention\Image\Facades\Image;

use Exception;
use App\Media;

use App\Profile;
use App\User;
use App\Skills;
use App\Video;
use App\Post;
use App\ProfileChangeRequest;
use App\Mail\ProfileBookmarked;
use App\Mail\ProfileChangeRequested;

use Carbon\Carbon;

use DB;

class HelperController extends Controller
{
    protected $changes;

    function __construct(ProfileChangeRequest $changes)
    {
        $this->changes = $changes;
    }

    function getLoginAgenciado(Request $request){

        $messages = [
            'required' => 'O :attribute é necessário.',
            'email' => 'O :attribute é inválido.',
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);


        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }

        $user = User::where(['email' => $request->get('email'), 'level' => 3 ])->first();

        if (!$user){
            return response()->json(['error' => ['Usuário e/ou Senha incorreto!'] ] );
        } else if( !password_verify ( $request->get('password') , $user->password ) ) {
            return response()->json(['error' => ['Usuário e/ou Senha incorreto!'] ] );
        }

        return response()->json(['success' => 'successo']);

    }

    function getRegisterAgenciado(Request $request){

        $messages = [
            'required' => 'O :attribute é necessário.',
            'unique' => 'O :attribute já existe.',
            'password.confirmed' => 'A senha não confere!',
            'email' => 'O :attribute é inválido.',
            'password.min' => 'A senha deve conter no mínimo :min caracteres.'
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'level' => 'between:1,3|required'
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);


        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }

        return response()->json(['success' => 'successo']);

    }

    function getResendAgenciado(Request $request){
        $messages = [
            'required' => 'O :attribute é requerido.',
            'email' => 'O :attribute é inválido.',
        ];

        $rules = [
            'email' => [
                'required',
                'email',
                function($attribute, $value, $fail){
                    $user = User::where('email', $value )->first();
                    if (!$user) {
                        $fail( $attribute.' não cadastrado.');
                    }
                }
            ],
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }

        return response()->json(['success' => $request->get('email')]);

    }

    function getEditAgenciadoData(Request $request)
    {
        $messages = [
            'fancy_name.required' => 'O Nome é necessário',
            'date_birth.required' => 'A Data de Nascimento é necessário',
            'date_birth.date_format' => 'A Data de Nascimento é inválida.',
            'height.required' => 'A Altura é necessário',
            'dummy.required' => 'O Manequim é necessário',
            'feet.required' => 'O Calçado é necessário',
            'gender.required' => 'O Sexo é necessário',
        ];

        $rules = [
            'fancy_name' => 'required',
            'date_birth' => 'required',
            'height' => 'required',
            'dummy' => 'required',
            'feet' => 'required',
            'gender' => 'required',
        ];

        try {
            $data = $request->except('_token');
            $data['height'] = str_replace(',', '.', $data['height']);
            $data['slug'] = str_slug($data['fancy_name']);

            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()]);
            }

            if ($profile = Profile::whereUserId($request->get('user_id'))->first()) {
                $changes = $this->changes->create([
                    'changes' => $data,
                    'profile_id' => $profile->id,
                ]);

                Mail::to(config('pharos.system_notifications'))
                    ->send(new ProfileChangeRequested($profile, $changes));

                return response()->json(['success' => 'As edições foram solicitadas com sucesso']);
            }

            $profile = Profile::create(array_merge([
                'user_id' => $request->user_id,
            ], $data));

            return response()->json(['success' => 'Perfil criado com sucesso']);

        } catch (Exception $e) {
            return response([
                'stacktrace' => $e->getTrace(),
                'user' => $request->all(),
                'error' => [
                    'error' => [$e->getMessage()]
                ],
            ], 400);
        }
    }


    function getEditAgenciadoMediaMain(Request $request){
        $messages = [
            'required' => 'O :attribute é requerido.',
        ];

        $rules = [
            'user_id' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }


        $imageName = time().'.'.request()->file->getClientOriginalExtension();
        request()->file->move( public_path('uploads/profiles/' . $request->get('user_id') ), $imageName);

        $data = [
            'entity_id' => $request->get('user_id'),
            'entity_type' => 'App\Profile',
            'type' => 'image',
            'path' => $imageName,
            'order' =>  99,
            'title' =>  null,
        ];

        $media = Media::where(['entity_id' => $request->get('user_id'), 'order' => 99] )->first();
        if($media){
            Media::where(['entity_id' => $request->get('user_id'), 'order' => 99])->update($data);
        } else {
            Media::insert($data);
        }

        $imgCompress = ImgCompressPath::where('user_id', $request->get('user_id'))
            ->where('img_name', $imageName)
            ->first();

        if($imgCompress == null) {

            create_dir_comp($request->get('user_id'));

            $imgPath = "uploads/profiles/{$request->get('user_id')}/compress";
            // gera imagem comprimida
            $dimensions = return_dimension(public_path("uploads/profiles/{$request->get('user_id')}/{$imageName}"));
            $foto = create_thumbnail(public_path("uploads/profiles/{$request->get('user_id')}/{$imageName}"), $imgPath . "/comp-" . $imageName, $dimensions["width"], $dimensions["height"]);

            $imgCompress = array(
                "url_compress" => $foto->dirname . "/" . $foto->basename,
                "img_name" => $imageName,
                "user" => $request->get('user_id')
            );

            ImgCompressPath::create($imgCompress);
        }

        return response()->json(['success' => $data]);
    }

    /**
     * Adiciona Imagens Após a primeira,
     * isso é feito ignorando o form antes de
     * adicionar a primeira foto.
     */
    function getAddAgenciadoMediaImages(Request $request){
        $messages = [
            'file.required' => 'Nenhum arquivo selecionado!',
            'file.image' => 'O arquivo deve ser uma imagem!',
            'file.mimes' => 'Os formátos válidos são jpeg,png,jpg ou gif!',
        ];

        $rules = [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);

        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }

        $order = Media::where('entity_id', $request->get('user_id'))->count();
        $imageName = time().'.'.request()->file->getClientOriginalExtension();
        request()->file->move( public_path('uploads/profiles/' . $request->get('user_id') ), $imageName);

            $image = 'uploads/profiles/'.$request->get('user_id').'/'.$imageName;

           // open and resize an image file
            $img = Image::make($image)->resize(200, 200);


            //CRIA DIRETÓRIO MINIATURA, CASO NAO EXISTA
            if (!file_exists("uploads/profiles/".$request->get('user_id')."/thumb")) {
                    File::makeDirectory("uploads/profiles/".$request->get('user_id')."/thumb", 0775, true);
                }


            // save file as jpg with medium quality
            $img->save('uploads/profiles/'.$request->get('user_id').'/thumb/'.$imageName, 60);


        /*
        foreach ($request->file as $image) {

        $thumb = Image::make($image);
                $thumb->fit(200, 200, function ($constraint) {
                    $constraint->upsize();
                });

       
        //CRIA DIRETÓRIO MINIATURA, CASO NAO EXISTA
        if (!file_exists("uploads/profiles/".$request->get('user_id')."/thumb")) {
                    File::makeDirectory("uploads/profiles/".$request->get('user_id')."/thumb", 0775, true);
                }
        //SALVA MINIATURA
        $thumb->save("uploads/profiles/".$request->get('user_id')."/thumb/{$imageName}");
        }*/

        // dd($this->media_fields());


        $data = [
            'entity_id' => $request->get('user_id'),
            'entity_type' => 'App\Profile',
            'type' => 'image',
            'path' => $imageName,
            'order' =>  $order,
            'title' =>  null,
        ];

        $media = Media::insert($data);


        $imgCompress = ImgCompressPath::where('user_id', $request->get('user_id'))
            ->where('img_name', $imageName)
            ->first();

        if($imgCompress == null) {

            create_dir_comp($request->get('user_id'));

            $imgPath = "uploads/profiles/{$request->get('user_id')}/compress";
            // gera imagem comprimida
            $dimensions = return_dimension(public_path("uploads/profiles/{$request->get('user_id')}/{$imageName}"));
            $foto = create_thumbnail(public_path("uploads/profiles/{$request->get('user_id')}/{$imageName}"), $imgPath . "/comp-" . $imageName, $dimensions["width"], $dimensions["height"]);

            $imgCompress = array(
                "url_compress" => $foto->dirname . "/" . $foto->basename,
                "img_name" => $imageName,
                "user" => $request->get('user_id')
            );

            ImgCompressPath::create($imgCompress);
        }


        return response()->json(['success' => $data]);
    }

    function getRemoveAgenciadoMediaImages(Request $request, $id){

        $idExplode = explode('|', base64_decode($id));
        $data['user_id'] = $idExplode[0];
        $data['id'] = $idExplode[1];

        $mediaToDelete = Media::where(['id'=> $data['id'] ])->delete();

        if($mediaToDelete){
            return response()->json(['success' => 'Foto do perfil removida com sucesso!']);
        } else {
            return response()->json(['error' => 'Not Found!']);
        }


    }




    function getEditAgenciadoMediaImages(Request $request, $id){

        $idExplode = explode('|', base64_decode($id));
        $data['entity_id'] = $idExplode[0];
        $data['id'] = $idExplode[1];
        $data['order'] = base64_decode($request->orderEncode);

        //$mediaToEdit = Media::where(['id'=> $data['id'] ])->delete();
        $mediaToEdit = Media::where(['id'=> $data['id'], 'entity_id'=> $data['entity_id']])->update($data);
        //$mediaToEdit = Media::where(['id'=> $data['id'] ]), 'order' => 99])->update($data);

        if($mediaToEdit){
            return response()->json(['success' => 'Foto do perfil editada com sucesso!']);
        } else {
            return response()->json(['error' => 'Not Found!']);
        }


    }




    //Apenas para cliente
    public function postFavorito(Request $request){

        $cliente = $request->logado_id; // cliente logado
        $usuario = $request->usuario_id;

        $noRepeat = Favorito::where('user_id', $cliente)
                ->where('agenciado_id', $usuario);

        if($noRepeat->count()){
            return response()->json([
                'error' => 'Você já adicionou esse perfil em seus favoritos'
            ]);
        } else {
            Favorito::create([
                'user_id' => $cliente,
                'agenciado_id' => $usuario
            ]);

            Mail::to(config('pharos.system_notifications'))
                ->send(new ProfileBookmarked(Profile::whereUserId($usuario)->first(), User::find($cliente)));

            return response()->json([
                'success' => 'Favorito adicionado com sucesso!'
            ]);
        }

    }

    function getLoginCliente(Request $request){

        $messages = [
            'required' => 'O :attribute é necessário.',
            'email' => 'O :attribute é inválido.',
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);


        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }

        $user = User::where(['email' => $request->get('email'), 'level' => 2 ])->first();

        if (!$user){
            return response()->json(['error' => ['Usuário e/ou Senha incorreto!'] ] );
        } else if( !password_verify ( $request->get('password') , $user->password ) ) {
            return response()->json(['error' => ['Usuário e/ou Senha incorreto!'] ] );
        }

        return response()->json(['success' => 'successo']);

    }

    function getRegisterCliente(Request $request){

        $messages = [
            'required' => 'O :attribute é necessário.',
            'unique' => 'O :attribute já existe.',
            'password.confirmed' => 'A senha não confere!',
            'email' => 'O :attribute é inválido.',
            'password.min' => 'A senha deve conter no mínimo :min caracteres.'
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'level' => 'between:1,3|required'
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);


        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }

        return response()->json(['success' => 'successo']);

    }

    function profile_fields(){
        $profile =  DB::select('describe profiles');
        $Fields = [];
        foreach($profile as $key => $value){

            $exclude = ['id','created_at','updated_at'];
            if(!in_array($value->Field, $exclude) ){
                $Fields[ $value->Field ] = null;
            }

            $custom_fields = [
                'tattoo' => 0,
                'film_outside' => false,
                'make_figuration' => false,
                'make_event' => false,
            ];
            if( key_exists($value->Field, $custom_fields )){
                $Fields[$value->Field] = $custom_fields[$value->Field] ;
            }
        }


        return $Fields;
    }

    function media_fields(){
        $profile =  DB::select('describe media');
        $Fields = [];
        foreach($profile as $key => $value){

            $exclude = ['id','created_at','updated_at'];
            if(!in_array($value->Field, $exclude) ){
                $Fields[ $value->Field ] = null;
            }

            $custom_fields = [

            ];
            if( key_exists($value->Field, $custom_fields )){
                $Fields[$value->Field] = $custom_fields[$value->Field] ;
            }
        }

        return $Fields;

    }

    function linkSanatizer($link){


        $link = parse_url($link);
        try {

            if($link['host'] == 'www.youtube.com'){ // Youtube Original
                $link = $link['query'];

                if($link[0] == 'v' && $link[1] == '='){ //remove 'v='
                    $link = 'https://www.youtube.com/embed/'.substr( $link, strlen($link) - 11 );
                }
            } else if($link['host'] == 'youtu.be'){ // Youtube Shared
                $link = $link['path'];
                $link = 'https://www.youtube.com/embed'.$link;
            }
            else if ($link['host'] == 'vimeo.com'){ // Vimeo Video
                $link = $link['path'];
                $link = 'https://player.vimeo.com/video'.$link;
            }
            else {
                throw new exception('Utilize Links do youtube!');
            }


        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage() ] );
        }


        // 'https://player.vimeo.com/video/265186808'

        return $link;
    }

    function getAddAgenciadoMediaVideos(Request $request){
        $messages = [
            'description' => 'O descrição é necessário.',
            'link' => 'O endereço do vídeo é necessário.',
        ];

        $rules = [
            'description' => 'required',
            'link' => 'required',
        ];

        $validator = Validator::make( $request->all() , $rules, $messages);


        if ($validator->fails()) {
            return response()->json([ 'error' => $validator->messages() ]);
        }


        $data = [
            'entity_id'  => $request->get('user_id'),
            'link' => $this->linkSanatizer( $request->get('link') ),
            'description' => $request->get('description'),
            'order' => 0,
            'host' => parse_url($request->get('link'))['host']
        ];


        $video = Video::create($data);

        if (!$video){
            return response()->json(['error' => ['Falha em cadastrar vídeo..'] ] );
        }

        return response()->json(['success' => 'successo']);
    }

    function getRemoveAgenciadoMediaVideos(Request $request, $id){

        $idExplode = explode('|', base64_decode($id));
        $data['user_id'] = $idExplode[0];
        $data['id'] = $idExplode[1];
        $mediaToDelete = Video::where(['entity_id' =>  $data['user_id'], 'id'=> $data['id'] ])->delete();

        if($mediaToDelete){
            return response()->json(['success' => 'Foto do perfil removida com sucesso!']);
        } else {
            return response()->json(['error' => 'Not Found!']);
        }


    }




     public function getEditPasswordData(Request $request)
    {



        //$user = auth()->user();
        request()->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);



        $data = $request->all();

        $user = $request->user_id;

        $user->update($data);

   

        return redirect()->back()
            ->with('success', 'Seu perfil foi atualizado com sucesso!');
    }


}
