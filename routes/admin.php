<?php

Route::group(['prefix' => 'admin'], function () {
    Route::get('/menu/get', 'Admin\MenuController@get_menu');
});