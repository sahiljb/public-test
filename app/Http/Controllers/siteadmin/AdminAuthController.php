<?php

namespace App\Http\Controllers\siteadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Change Password';
        $breadcrumb = '';
        return view('pages.profile.change-password',compact('title','breadcrumb'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
    
        $user = Auth::user();

        if (Hash::check($request->input('current_password'), $user->password)) {
            $saveuser = User::find($user->id);
            $saveuser->password = Hash::make($request->input('new_password'));
            $saveuser->save();
            return response()->json(['status'=> 'success','message' => 'Password updated successfully.']);

        }else{
            return response()->json(['status'=> 'false','message' => 'Current password is incorrect.']);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
