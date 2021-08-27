<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfileChangeRequest;
use App\Profile;
use Exception;

class ProfileChangeRequestsController extends Controller
{
    protected $changes;

    protected $profiles;

    function __construct(ProfileChangeRequest $changes, Profile $profiles)
    {
        $this->changes = $changes;
        $this->profiles = $profiles;
    }

    function accept($id)
    {
        try {
            $change = $this->changes->findOrFail($id);
            $profile = $this->profiles->findOrFail($change->profile_id);

            $profile->fill($change->changes);
            $profile->save();

            return 'As Mudanças foram incorporadas.';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function show($id)
    {
        return $this->changes->findOrFail($id);
    }
}
