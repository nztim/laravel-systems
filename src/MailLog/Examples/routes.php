<?php declare(strict_types=1);

// System Messages
Route::get('admin/system-messages',         'SystemMessagesController@index')   ->name('admin.system-messages.index');
Route::get('admin/system-messages/{id}',    'SystemMessagesController@show')    ->name('admin.system-messages.show');
