<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
        ]);
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully created.'));
    }
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findorFail($id);
        return view('users.userEdit', compact('user'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
{
    $user->update($request->validated()); // Use validated data from UserRequest
    return redirect()->route('user.getAllUsers')->withStatus(__('User successfully updated.'));
}


    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request, User $user)
    {
        $user->update(['password' => Hash::make($request->get('password'))]); 
        return redirect()->route('user.getAllUsers')->withStatus(__('User password successfully updated.'));
    }

    public function destroy(Request $request, User $user)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
    
        $user->delete();
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully deleted.'));
    }

    /**get all users*/
    public function getAllUsers()
    {
        $users = User::all();
        return view('pages.tables', compact('users'));
    
    }
}
