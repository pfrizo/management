<?php

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    echo 'hello';
});

Route::get('/email', function(){
    Mail::raw('management test message', function(Message $message){
        $message->to('test@gmail.com')
        ->subject('Welcome!')
        ->from('frz@management.com');
    });

    echo 'email susccessfully sended!';
});
