<?php

use App\Livewire\TicTacToe;
use Illuminate\Support\Facades\Route;

Route::get('/tic-tac-toe', TicTacToe::class)
    ->name('tic-tac-toe');

Route::view('/', 'welcome')->name('welcome');
