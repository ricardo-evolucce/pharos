<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Media;
use Illuminate\Http\Request;
use App\Profile;
use File;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $profiles = Profile::with('user')
            ->when($request->dummy, function ($query, $dummy) {
                $dummy = json_decode($dummy)->value;
                $query->whereBetween('dummy', $dummy);
            })
            ->when($request->feet, function ($query, $feet) {
                $feet = json_decode($feet)->value;
                $query->whereBetween('feet', $feet);
            })
            ->when($request->age, function ($query, $age) {
                $age = json_decode($age)->value;

                // Get years
                $age = collect($age)->map(function ($age) {
                    return now()->subYears($age)->format('Y-m-d');
                })
                ->sort()
                ->toArray();

                $query->whereBetween('date_birth', $age);
            })
            ->when($request->weight, function ($query, $weight) {
                $weight = json_decode($weight)->value;
                $query->whereBetween('weight', $weight);
            })
            ->when($request->height, function ($query, $height) {
                $height = json_decode($height)->value;
                $query->whereBetween('height', $height);
            })
            ->when($request->gender, function ($query, $gender) {
                $query->whereIn('gender', $gender);
            })
            ->orderBy('fancy_name', 'asc')
            // ->limit(10)
            ->get();

        return $profiles;
    }
    public function userPhotos(Request $request)
    {
        $ids = request('ids');
        $array = [];
        if($ids){
            foreach ($ids as $key => $user_id) {
                $path = public_path(implode(DIRECTORY_SEPARATOR, ['uploads','profiles',$user_id]));
                $photos = Media::where('entity_id', $user_id)->orderBy('order','desc')->get()->toArray();
                $files = array_map(function($photo) use ($path) {
                    return $path.DIRECTORY_SEPARATOR.$photo['path'];
                }, $photos);

                foreach ($files as $key => $file) {
                    $path_temp = str_replace(public_path(),'', $file);
                    $array[$user_id][] = $path_temp;

                }
            }
        }
        return $array;
    }
    public function userPhotosCarts(Request $request)
    {
        $idCart = request('id');
        $cart = Cart::where('id', $idCart)->with('profiles')->get()->toArray();
        $array = [];
            if($idCart && $cart){
            foreach (unserialize($cart[0]["photos_select"]) as $photos){
                foreach ($photos as $photo){
                   $arrayUser = explode('\\', $photo["src"]);
                   if(!isset($arrayUser[3]) && empty(isset($arrayUser[3]))){
                       $arrayUser = explode('/', $photo["src"]);
                   }
                   $array[intval($arrayUser[3])][] = $photo["src"];
                }
            }
        }
        return $array;
    }
}
