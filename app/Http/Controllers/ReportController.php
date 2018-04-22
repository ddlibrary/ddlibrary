<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\User;

class ReportController extends Controller
{
    public function index()
    {
        $resourceModel  = new Resource();
        $usersModel     = new User();

        //total resources by language
        $totalResources             = $resourceModel->totalResourcesByLanguage();
        $totalResourcesBySubject    = $resourceModel->totalResourcesBySubject();
        $totalResourcesByLevel      = $resourceModel->totalResourcesByLevel();
        $totalResourcesByType       = $resourceModel->totalResourcesByType();
        $totalResourcesByFormat     = $resourceModel->totalResourcesByFormat();
        $totalUsersByGender         = $usersModel->totalUsersByGender();
        $totalUsersByCountry        = $usersModel->totalUsersByCountry();
        $totalResourcesByRoles      = $usersModel->totalResourcesByRoles();

        return view('admin.reports', compact(
            'totalResources',
            'totalUsersByGender',
            'totalResourcesBySubject',
            'totalResourcesByLevel',
            'totalResourcesByType',
            'totalResourcesByFormat',
            'totalUsersByCountry',
            'totalResourcesByRoles'
        ));
    }
}
