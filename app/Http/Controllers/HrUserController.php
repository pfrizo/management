<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HrUserController extends Controller
{
    public function index(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $colaborators = User::where('role', 'hr')->get();

        return view('colaborators.rh-users', compact('colaborators'));
    }

    public function newColaborator(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $departments = Department::all();

        return view('colaborators.add-hr-user', compact('departments'));
    }

    public function createHRColaborator(Request $request){
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'select_department' => 'required|exists:departments,id',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 'hr';
        $user->department_id = $request->select_department;
        $user->permissions = '["hr"]';
        $user->save();

        return redirect()->route('hr-users')->with('success', 'Colaborator created successfully!');
    }
}
