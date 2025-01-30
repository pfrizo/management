<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Raw;

class HRManagementController extends Controller
{
    public function home(){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $colaborators = User::with('detail', 'department')
                        ->where('role', 'colaborator')
                        ->withTrashed()
                        ->get();
    
        return view('colaborators.colaborators', compact('colaborators'));
    }

    public function newColaborator(){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $departments = Department::where('id', '>', 2)->get();

        if($departments->count() === 0){
            abort(403, 'There are no departments to add a new colaborator. Please, contact the system admin to add a new department.');
        }

        return view('colaborators.add-colaborator', compact('departments'));
    }

    public function createColaborator(Request $request){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

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

        if($request->select_department <= 2){
            return redirect()->route('home');
        }

        $token = Str::random(60);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = 'colaborator';
        $user->department_id = $request->select_department;
        $user->permissions = '["colaborator"]';
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

        return redirect()->route('hr.management')->with('success', 'Colaborator created successfully!');
    }
    
    public function editColaborator($id){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $colaborator = User::with('detail')->findOrFail($id);
        $departments = Department::where('id', '>', 2)->get();

        return view('colaborators.edit-colaborator', compact('colaborator', 'departments'));
    }

    public function updateColaborator(Request $request){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d',
            'select_department' => 'required|exists:departments,id'
        ]);

        if($request->select_department <= 2){
            return redirect()->route('home');
        }

        $user = User::with('detail')->findOrFail($request->user_id);
        $user->detail->salary = $request->salary;
        $user->detail->admission_date = $request->admission_date;
        $user->department_id = $request->select_department;

        $user->save();
        $user->detail->save();

        return redirect()->route('hr.management');
    }

    public function showDetails($id){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $colaborator = User::with('detail', 'department')->findOrFail($id);

        return view('colaborators.show-details', compact('colaborator'));
    }

    public function deleteColaborator($id){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $colaborator = User::findOrfail($id);

        return view('colaborators.delete-colaborator', compact('colaborator'));
    }

    public function deleteColaboratorConfirm($id){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $colaborator = User::findOrfail($id);

        $colaborator->delete();

        return redirect()->route('hr.management');
    }

    public function restoreColaborator($id){
        Auth::user()->can('hr') ?: abort(403, 'You are not authorized to access this page.');

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('hr.management');
    }
    
}
