<?php

Route::prefix('epanel/features')->as('epanel.')->middleware(['auth', 'check.permission:Slider'])->group(function() 
{
    Route::resources([
        'slider' => 'SliderController'
    ]);
});