<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        // Stub: عرض القوالب الجاهزة
        return view('dashboard.templates.index');
    }
}
