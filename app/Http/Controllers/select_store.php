<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class select_store extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin == 'Y') {

            $PLAN_1 = DB::table('E_PLAN_PRODUCT')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 30)
                ->count();

            $PLAN_2 = DB::table('E_PLAN_PRODUCT')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 31)
                ->count();
        } else {
            $PLAN_1 = DB::table('E_PLAN_PRODUCT')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 30)
                ->count();
            $PLAN_2 = DB::table('E_PLAN_PRODUCT')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 31)

                ->count();
        }
        return view('select_store', [
            'PLAN_1' => $PLAN_1,
            'PLAN_2' => $PLAN_2,
        ]);
    }
}
