<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function home()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $defaultYear = "{$currentYear}-{$nextYear}";
        $academicYear = SiteSetting::get('academic_year', $defaultYear) ?: $defaultYear;

        $form1Enabled = SiteSetting::isFormEnabled('form1');
        $form2Enabled = SiteSetting::isFormEnabled('form2');
        $form3Enabled = SiteSetting::isFormEnabled('form3');

        return view('home', [
            'academicYear' => $academicYear,
            'form1Enabled' => $form1Enabled,
            'form2Enabled' => $form2Enabled,
            'form3Enabled' => $form3Enabled,
            'anyFormEnabled' => ($form1Enabled || $form2Enabled || $form3Enabled),
        ]);
    }
}
