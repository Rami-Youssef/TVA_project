<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findorFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {

        $user = User::findorFail($request->id)->update($request->all());
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        $user = User::findorFail($request->id)->update(['password' => Hash::make($request->get('password'))]);

        return redirect()->route('user.getAllUsers')->withStatus(__('User password successfully updated.'));
    }

    public function destroy($id)
    {
        User::findorFail($id)->delete();
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully deleted.'));
    }

    /**get all users*/
    public function getAllUsers()
    {
        $users = User::all();
        return view('pages.tables', compact('users'));
    
    }
}
