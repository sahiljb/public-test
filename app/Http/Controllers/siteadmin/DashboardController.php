<?php

namespace App\Http\Controllers\siteadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function Analytics(){
        $title = 'Dashboard';
        $breadcrumb = 'Dashboard';
        return view('pages.dashboard.analytics',compact('title','breadcrumb'));
    }
}
