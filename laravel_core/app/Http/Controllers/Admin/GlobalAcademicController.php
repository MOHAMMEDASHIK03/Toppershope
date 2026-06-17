<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Batch;

class GlobalAcademicController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')->withCount('batches')->latest()->get();
        $batches = Batch::with(['course.category', 'category', 'subcategory'])->latest()->take(10)->get();
        
        return view('admin.academic.index', compact('courses', 'batches'));
    }
}
