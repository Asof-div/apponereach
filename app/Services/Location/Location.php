<?php

namespace App\Services\Location;

use App\Models\Country;

class Location
{
	public static function getCountryByPhonenumber($phonenumber)
    {
        if (starts_with($phonenumber, '+234')) {
            return Country::where('phonecode', 234)->first();
        } else {
            return Country::where('iso', 'US')->first();
        }
    }
}