<?php

namespace App\Http\Controllers\Faculty\Admin;

use App\Http\Controllers\Admin\CategoryController;

class AdminCategoryController extends CategoryController
{
    protected string $routeNamePrefix = 'faculty.head.categories';

    protected string $layoutView = 'layouts.faculty';
}
