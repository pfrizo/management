<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

class HrUserController extends Controller
{
    public function index(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        //$colaborators = User::where('role', 'hr')->get();
        $colaborators = User::with('detail')
                            ->where('role', 'hr')
                            ->get();

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
            'address' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'city' => 'required|string|max:50',
            'phone' => 'required|string|max:50',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d'
        ]);

        if($request->select_department != 2){
            return redirect()->route('home');
        }

        $token = Str::random(60);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = 'hr';
        $user->department_id = $request->select_department;
        $user->permissions = '["hr"]';
        $user->save();

        $user->detail()->create([
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'admission_date' => $request->admission_date
        ]);

        Mail::to($user->email)->send(new ConfirmAccountEmail(route('confirm-account', $token)));

        return redirect()->route('hr-users')->with('success', 'Colaborator created successfully!');
    }

    public function editHRColaborator($id){
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $id = decrypt($id);

        $colaborator = User::with('detail')->where('role', 'hr')->findOrFail($id);

        return view('colaborators.edit-hr-user', compact('colaborator'));
    }

    public function updateHRColaborator(Request $request){
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $id = decrypt($request->user_id);

        $request->validate([
            'user_id' => 'required',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d'
        ]);

        $user = User::findOrFail($id);

        $user->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date
        ]);

        return Redirect()->route('hr-users')->with('success', 'Colaborator updated successfully');
    }

    public function deleteColaborator($id){
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $id = decrypt($id);

        $colaborator = User::findOrFail($id);

        return view('colaborators.delete-hr-user', compact('colaborator'));
    }

    public function deleteColaboratorConfirm($id){
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $id = decrypt($id);

        $colaborator = User::findOrFail($id);
        $colaborator->delete();

        return Redirect()->route('hr-users')->with('success', 'Colaborator deleted successfully');
    }
}
