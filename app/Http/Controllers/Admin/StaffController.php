<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ads\AdsUser;
use App\Models\Admission\AdmissionUser;
use App\Models\User; // Faculty

class StaffController extends Controller
{
    public function index()
    {
        $adsUsers = AdsUser::latest()->get();
        $admissionUsers = AdmissionUser::latest()->get();
        $facultyUsers = User::where('role', 'faculty')->orWhere('role', 'faculty_head')->latest()->get();

        return view('admin.staff.index', compact('adsUsers', 'admissionUsers', 'facultyUsers'));
    }
}
