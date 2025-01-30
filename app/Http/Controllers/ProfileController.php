<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = User::with('detail')->findOrFail(auth()->id());

        return view('user.profile', compact('user'));
    }

    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required|min:8|max:16',
            'new_password' => 'required|min:8|max:16|different:current_password',
            'new_password_confirmation' => 'required|same:new_password'
        ]);

        $user = auth()->user();

        if(!password_verify($request->current_password, $user->password)){
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateUserData(Request $request){
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id()
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success_change_data', 'Profile updated successfully.');
    }

    public function updateUserAddress(Request $request){
        $request->validate([
            'address' => 'required|min:3|max:100',
            'zip_code' => 'required|min:8|max:8',
            'city' => 'required|min:3|max:50',
            'phone' => 'required|min:6|max:20'
        ]);

        $user = User::with('detail')->findOrFail(auth()->id());
        $user->detail->address = $request->address;
        $user->detail->zip_code = $request->zip_code;
        $user->detail->city = $request->city;
        $user->detail->phone = $request->phone;
        $user->detail->save();

        return redirect()->back()->with('success_change_address', 'Address updated successfully.');
    }
}
