<?php

use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/email', function(){
    Mail::raw('management test message', function(Message $message){
        $message->to('test@gmail.com')
        ->subject('Welcome!')
        ->from('frz@management.com');
    });

    echo 'email susccessfully sended!';
});

Route::get('/admin', function(){
    $admin = User::with('detail', 'department')->find(1);

    return view('admin', compact('admin'));
});

Route::middleware('auth')->group(function(){
    Route::redirect('/', 'home');
    Route::view('/home', 'home')->name('home');
});
