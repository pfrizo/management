<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function home(){
        Auth::user()->can('admin') ?: abort(403, 'You are not authorized to access this page.');

        $data = [];

        $data['total_colaborators'] = User::whereNull('deleted_at')->count();
        $data['total_colaborators_deleted'] = User::onlyTrashed()->count();
        $data['total_salary'] = User::withoutTrashed()->with('detail')
                                    ->get()
                                    ->sum(function($colaborator){
                                        return $colaborator->detail->salary;
                                    });

        $data['total_salary'] = '$' . number_format($data['total_salary'], 2, ',', '.');

        $data['total_colaborators_per_department'] = User::withoutTrashed()->with('department')->get()->groupBy('department_id')->map(function($department){
            return [
                'department' => $department->first()->department->name ?? '-',
                'total' => $department->count()
            ];
        });
        $data['total_salary_by_department'] = User::withoutTrashed()->with('detail', 'department')->get()->groupBy('department_id')->map(function($department){
            return [
                'department' => $department->first()->department->name ?? '-',
                'total' => $department->sum(function($colaborator){
                    return $colaborator->detail->salary;
                })
            ];
        });

        $data['total_salary_by_department'] = $data['total_salary_by_department']->map(function($department){
            return [
                'department' => $department['department'],
                'total' => '$' . number_format($department['total'], 2, ',', '.')
            ];
        });
        
        return view('home', compact('data'));
    }
}
