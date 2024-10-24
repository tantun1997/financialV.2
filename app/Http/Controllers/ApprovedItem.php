<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovedItem extends Controller
{

    public function approved(Request $request, $id)
    {

        // ตรวจสอบค่าที่ส่งมา
        $approvedId = $request->input('REQ_APPROVED_ID', 0); // ใช้ค่าเริ่มต้นเป็น 0 หากไม่มีการส่งข้อมูล

        // อัปเดตข้อมูลในฐานข้อมูล
        DB::table('E_PLAN')
            ->where('PLAN_ID', $id)
            ->update([
                'REQ_APPROVED_ID' => $approvedId,
            ]);

        if ($approvedId == 1) {
            return response()->json([
                'success' => true,
                'message' => 'ขออนุมัติสำเร็จ!!',
            ]);

        } elseif ($approvedId == 2) {
            return response()->json([
                'success' => true,
                'message' => 'อนุมัติสำเร็จ!!',
            ]);

        } elseif ($approvedId == 3) {
            return response()->json([
                'success' => true,
                'message' => 'ไม่อนุมัติ!!',
            ]);

        } elseif ($approvedId == 4) {
            return response()->json([
                'success' => true,
                'message' => 'ยกเลิกการอนุมัติ!!',
            ]);

        }

        // ส่งกลับไปยังหน้าเดิมพร้อมกับข้อความสำเร็จ
        return response()->json([
            'success' => false,
            'message' => 'เกิดข้อผิดพลาดในการประมวลผล!',
        ]);
    }

    public function approved_store(Request $request, $id)
    {
        // ตรวจสอบค่าที่ส่งมา
        $approvedId = $request->input('REQ_APPROVED_ID', 0); // ใช้ค่าเริ่มต้นเป็น 0 หากไม่มีการส่งข้อมูล

        // อัปเดตข้อมูลในฐานข้อมูล
        DB::table('E_PLAN_PRODUCT')
            ->where('PLAN_ID', $id)
            ->update([
                'REQ_APPROVED_ID' => $approvedId,
            ]);

        // ตรวจสอบว่าอนุมัติสำเร็จ
        if ($approvedId == 2) {
            return response()->json([
                'success' => true,
                'message' => 'อนุมัติสำเร็จ!!',
            ]);
        } elseif ($approvedId == 0) {
            return response()->json([
                'success' => true,
                'message' => 'ผ่านอนุมัติ!',
            ]);
        }

        // ส่งกลับไปยังหน้าเดิมพร้อมกับข้อความข้อผิดพลาด
        return response()->json([
            'success' => false,
            'message' => 'เกิดข้อผิดพลาดในการประมวลผล!',
        ]);
    }

    public function destroy(string $id)
    {
        DB::table('E_PLAN')
            ->where('PLAN_ID', $id)
            ->update([
                'PLAN_IS_ACTIVE' => 'N',
            ]);
        toastr()->success('ปิดใช้งานสำเร็จ!!');

        return redirect()->back();
    }

    public function destroy_store(string $id)
    {
        DB::table('E_PLAN_PRODUCT')
            ->where('PLAN_ID', $id)
            ->update([
                'PLAN_IS_ACTIVE' => 'N',
            ]);
        toastr()->success('ปิดใช้งานสำเร็จ!!');

        return redirect()->back();
    }

    public function index()
    {
        $E_PLAN_COUNT = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->where('USAGE_STATUS_ID', 1)
            ->whereIn('REQ_APPROVED_ID', [1, 4])
            ->orderBy('PLAN_ID', 'desc')
            ->count();

        $E_PLAN_PASSED_COUNT = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->where('REQ_APPROVED_ID', 2)
            ->orderBy('PLAN_ID', 'desc')
            ->count();
        $E_PLAN_NO_PASSED_COUNT = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->where('REQ_APPROVED_ID', 3)
            ->orderBy('PLAN_ID', 'desc')
            ->count();

        $E_PLAN_PRODUCT_COUNT = DB::table('แผนวัสดุ')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->whereIn('REQ_APPROVED_ID', [1, 4])
            ->orderBy('PLAN_ID', 'desc')
            ->count();

        return view('approved_items.select_approved', compact('E_PLAN_COUNT', 'E_PLAN_PASSED_COUNT', 'E_PLAN_NO_PASSED_COUNT', 'E_PLAN_PRODUCT_COUNT'));
    }

    public function approved_items_plans()
    {
        $E_PLAN = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->where('USAGE_STATUS_ID', 1)
            ->whereIn('REQ_APPROVED_ID', [1, 4])
            ->orderBy('PLAN_ID', 'desc')
            ->get();

        return view('approved_items.approved_items_plans', compact('E_PLAN'));

    }

    public function approved_items_stores()
    {
        $E_PLAN = DB::table('แผนวัสดุ')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->whereIn('REQ_APPROVED_ID', [1, 4])
            ->orderBy('PLAN_ID', 'desc')
            ->get();

        return view('approved_items.approved_items_stores', compact('E_PLAN'));

    }

    public function passed_approved_items()
    {

        $E_PLAN_PASSED = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->where('REQ_APPROVED_ID', 2)
            ->orderBy('PLAN_ID', 'desc')
            ->get();

        return view('approved_items.passed_approved_items', compact('E_PLAN_PASSED'));

    }
    public function no_passed_approved_items()
    {

        $E_PLAN_NO_PASSED = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->where('REQ_APPROVED_ID', 3)
            ->orderBy('PLAN_ID', 'desc')
            ->get();

        return view('approved_items.no_passed_approved_items', compact('E_PLAN_NO_PASSED'));

    }
}
