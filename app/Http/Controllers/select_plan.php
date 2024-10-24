<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class select_plan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->isAdmin == 'Y') {
            $PLAN_1 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 1)
                ->count();

            $PLAN_2 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->whereIn('TYPE_ID', range(2, 14)) // เพิ่มเงื่อนไข TYPE_ID in (2-14)
                ->count();

            $PLAN_3 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->whereIn('TYPE_ID', range(15, 25)) // เพิ่มเงื่อนไข TYPE_ID in (2-14)
                ->count();
            $PLAN_4 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 26)
                ->count();
            $PLAN_5 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 27)
                ->count();
            $PLAN_6 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 28)
                ->count();
            $PLAN_7 = DB::table('E_PLAN')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 29)
                ->count();
        } else {
            $PLAN_1 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 1)
                ->count();
            $PLAN_2 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->whereIn('TYPE_ID', range(2, 14)) // เพิ่มเงื่อนไข TYPE_ID in (2-14)

                ->count();

            $PLAN_3 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->whereIn('TYPE_ID', range(15, 25)) // เพิ่มเงื่อนไข TYPE_ID in (2-14)

                ->count();
            $PLAN_4 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 26)

                ->count();
            $PLAN_5 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 27)

                ->count();
            $PLAN_6 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 28)

                ->count();
            $PLAN_7 = DB::table('E_PLAN')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 29)

                ->count();
        }
        return view('select_plan', [
            'PLAN_1' => $PLAN_1,
            'PLAN_2' => $PLAN_2,
            'PLAN_3' => $PLAN_3,
            'PLAN_4' => $PLAN_4,
            'PLAN_5' => $PLAN_5,
            'PLAN_6' => $PLAN_6,
            'PLAN_7' => $PLAN_7,

        ]);
    }
}
