<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class FormController extends Controller
{
    public function step1($citySlug)
    {
        $city = City::where('slug', $citySlug)->firstOrFail();
        return view('pages.form-step1', compact('city'));
    }

    public function step2($citySlug)
    {
        $city = City::where('slug', $citySlug)->firstOrFail();
        return view('pages.form-step2', compact('city'));
    }

    public function step3($citySlug)
    {
        $city = City::where('slug', $citySlug)->firstOrFail();
        return view('pages.form-step3', compact('city'));
    }
}
