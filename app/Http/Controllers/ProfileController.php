<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileStore;
use App\Http\Requests\ProfileUpdate;
use App\Media;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    protected $education_list;

    protected $gender_list;

    protected $status_list;

    protected $hair_color_list;

    protected $marital_status_list;

    function __construct()
    {
        $this->education_list = config('pharos.education_list'); 
        $this->gender_list = config('pharos.gender_list');
        $this->status_list = config('pharos.status_list');
        $this->hair_color_list = config('pharos.hair_color_list');
        $this->marital_status_list = config('pharos.marital_status_list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::with('user')->get();

        return view("profiles.index", compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $education_list = $this->education_list;
        $gender_list = $this->gender_list;
        $status_list = $this->status_list;
        $hair_color_list = $this->hair_color_list;
        $marital_status_list = $this->marital_status_list;

        return view('profiles.create', compact(
            'education_list',
            'gender_list',
            'hair_color_list',
            'marital_status_list',
            'status_list'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileStore $request)
    {
        $request->merge(['password' => bcrypt('minhaagencia10')]);
        $user = User::create($request->all());

        $profile = new Profile;
        $request->merge(['slug' => str_slug($request->fancy_name)]);
        $profile->fill($request->all());

        $user->profile()->save($profile);

        if ($request->hasFile('images') && is_array($request->images)) {
            foreach ($request->images as $image) {



                $media = new Media;
                $path = $image->store("profiles/{$user->profile->user_id}", 'public');

                $split = explode("/", $path);
                $mediaName = end($split);

                $media->path = $mediaName;
                $media->type = "image";

                $thumb = Image::make($image);
                $thumb->fit(200, 200, function ($constraint) {
                    $constraint->upsize();
                });

                if (!file_exists("uploads/profiles/{$user->profile->user_id}/thumb")) {
                    File::makeDirectory("uploads/profiles/{$user->profile->user_id}/thumb", 0775, true);
                }

                $thumb->save("uploads/profiles/{$user->profile->user_id}/thumb/{$mediaName}");

                $user->profile->medias()->save($media);
            }
        }

        return redirect()->route('profiles.index')
            ->with('success', 'Agenciado cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        $education_list = $this->education_list;
        $gender_list = $this->gender_list;
        $status_list = $this->status_list;
        $hair_color_list = $this->hair_color_list;
        $marital_status_list = $this->marital_status_list;

        return view('profiles.edit', compact(
            'profile',
            'education_list',
            'gender_list',
            'status_list',
            'hair_color_list',
            'marital_status_list'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdate $request, Profile $profile)
    {
        $profile->fill($request->all());
        $profile->save();

        $user = $profile->user;
        $user->fill($request->all());
        $user->save();

        if ($request->hasFile('images') && is_array($request->images)) {
            foreach ($request->images as $image) {
                $media = new Media;
                $path = $image->store("profiles/{$user->profile->user_id}", 'public');
                $split = explode("/", $path);
                $mediaName = end($split);

                $order = Media::where('entity_id', $user->profile->user_id)->count();

                $media->path = $mediaName;
                $media->type = "image";
                $media->order = $order;

                $thumb = Image::make($image);
                $thumb->fit(200, 200, function ($constraint) {
                    $constraint->upsize();
                });

                if (!file_exists("uploads/profiles/{$user->profile->user_id}/thumb")) {
                    File::makeDirectory("uploads/profiles/{$user->profile->user_id}/thumb", 0775, true);
                }

                $thumb->save("uploads/profiles/{$user->profile->user_id}/thumb/{$mediaName}");

                $user->profile->medias()->save($media);
            }
        }

        return redirect()->route('profiles.index')
            ->with('success', 'Agenciado alterado com sucesso!'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }

    public function solicitation()
    {
        $profiles = Profile::with('user')->get();

        return view("profiles.solicitation", compact('profiles'));
    }
}
