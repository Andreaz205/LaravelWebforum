<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return inertia('Admin/Role/Index');
    }

    public function create()
    {
        return inertia('Admin/Role/Create');

    }
}
