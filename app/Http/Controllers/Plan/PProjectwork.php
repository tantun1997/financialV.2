<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PProjectwork extends Controller
{

    public function index()
    {
        $E_TYPE = DB::table('E_TYPE')->where('TYPE_ID', 32)->get();
        $E_BUDGET = DB::table('E_BUDGET')->get();
        $E_USAGE_STATUS = DB::table('E_USAGE_STATUS')->get();

        return view('projectworks.index', compact('E_TYPE', 'E_BUDGET', 'E_USAGE_STATUS'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projectworks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::table('E_PLAN')->insertGetId([
            'CREATE_DATE' => now(),
            'PLAN_SET_YEAR' => $request->input('PLAN_SET_YEAR'),
            'TYPE_ID' => $request->input('TYPE_ID'),
            'PLAN_NAME' => $request->input('PLAN_NAME'),
            'PLAN_PRICE_PER' => $request->input('PLAN_PRICE_PER'),
            'PLAN_QTY' => $request->input('PLAN_QTY'),
            'PLAN_REASON' => $request->input('PLAN_REASON'),
            'PLAN_REMARK' => $request->input('PLAN_REMARK'),
            'deptId' => Auth::user()->deptId,
            'userId' => Auth::user()->userId,
            'USAGE_STATUS_ID' => $request->input('USAGE_STATUS_ID'),
            'PLAN_IS_ACTIVE' => 1,
            'Budget_ID' => $request->input('Budget_ID'),
            'REQ_APPROVED_ID' => 0,

        ]);
        toastr()->success('เพิ่มสำเร็จ!!');

        return redirect()->route('projectworks');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $product = Product::findOrFail($id);

        return view('projectworks.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $product = Product::findOrFail($id);

        return view('projectworks.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $product = Product::findOrFail($id);

        // $product->update($request->all());

        return redirect()->route('projectworks')->with('success', 'product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $product = Product::findOrFail($id);

        // $product->delete();

        return redirect()->route('projectworks')->with('success', 'product deleted successfully');
    }
}
