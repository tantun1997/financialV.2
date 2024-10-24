<?php
namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PInsidewarehouse extends Controller
{
    public function index(Request $request)
    {
        DB::statement("EXEC แผนGVinv");

        $TCHN_LOCAT_NAME = $request->get('TCHN_LOCAT_NAME'); // รับค่า type_name จาก request

        // ตรวจสอบว่า user เป็น Admin หรือไม่
        if (Auth::user()->isAdmin == 'Y') {
            $query = DB::table('แผนวัสดุ')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 31);
        } else {
            $query = DB::table('แผนวัสดุ')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_ID', 31);
        }

        // หากมีการส่ง type_name ให้ค้นหาเพิ่มเติม
        if ($TCHN_LOCAT_NAME) {
            $query->where('TCHN_LOCAT_NAME', $TCHN_LOCAT_NAME); // ค้นหาตาม TYPE_NAME
        }

        // ดึงข้อมูลตามเงื่อนไขที่กำหนด
        $E_PLAN = $query->orderBy('PLAN_ID', 'desc')->get();

        // ดึงข้อมูลอื่นๆ ที่จำเป็น
        $E_TYPE = DB::table('E_TYPE')->where('TYPE_NUMBER', 8)->get();
        $E_BUDGET = DB::table('E_BUDGET')->get();
        $E_USAGE_STATUS = DB::table('E_USAGE_STATUS')->get();
        $E_CLOSE_PLAN = DB::table('E_CLOSE_PLAN')->where('id', 1)->first();
        $categories = DB::table('VW_PRODUCT')
            ->select('PRODCT_CAT_ID', 'PRODCT_CAT_NAME')
            ->orderBy('PRODCT_CAT_ID', 'asc')
            ->distinct()
            ->get();

        return view('insidewarehouses.index', compact('categories', 'E_PLAN', 'E_TYPE', 'E_BUDGET', 'E_USAGE_STATUS', 'E_CLOSE_PLAN'));
    }

    public function updateClosePlan(Request $request)
    {
        // ดึง id และ status จาก AJAX request
        $id = $request->id;
        $status = $request->status;

        // อัปเดต status ในฐานข้อมูล
        DB::table('E_CLOSE_PLAN')
            ->where('id', $id)
            ->update(['status' => $status]);

        return response()->json(['message' => 'สถานะถูกอัปเดตเรียบร้อย']);
    }

    public function create(string $PRODCT_LINK_NO, string $PLAN_ID)
    {
        $E_PLAN = DB::table('แผนวัสดุ')
            ->where('PLAN_ID', $PLAN_ID)
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->first();

        // ตรวจสอบว่า EQUP_LINK_NO และ PLAN_ID รับค่าอย่างถูกต้อง
        $VW_PRODUCT = DB::table('VW_PRODUCT')
            ->where('PRODCT_LINK_NO', $PRODCT_LINK_NO)
            ->first();

        DB::table('E_LIST_PRODUCT')->insert([
            'PRODCT_LINK_NO' => $VW_PRODUCT->PRODCT_LINK_NO,
            'PRODCT_SERIAL_NUMBER' => $VW_PRODUCT->PRODCT_ID,
            'PRODCT_NAME' => $VW_PRODUCT->PRODCT_NAME,
            'PRODCT_CAT_ID' => $VW_PRODUCT->PRODCT_CAT_ID,
            'PRODCT_IOSTK_FLAG' => $VW_PRODUCT->PRODCT_IOSTK_FLAG,
            'PRODCT_CURRENT_PRICE' => $VW_PRODUCT->PRODCT_MIN_COSTRATE,
            'PRODCT_QTY' => $VW_PRODUCT->PRODCT_MIN_BALQTY,
            'PRODCT_USED' => 'N',
            'STATUS_ID' => 0, // ค่าเริ่มต้น
            'PRODCT_STATUS_DATE' => now(), // ใช้วันที่และเวลาปัจจุบัน
            'PRODCT_CREATED_DATE' => now(),
            'PLAN_ID' => $PLAN_ID, // ใช้ PLAN_ID ที่ส่งมา
            'IC_LOCAT_ID' => $VW_PRODUCT->IC_LOCAT_ID,
            'IC_USAGE_LINK_NO' => $E_PLAN->IC_USAGE_LINK_NO,
            'PRODCT_UNIT_ID' => $VW_PRODUCT->PRODCT_UNIT_ID,
            'PRODCT_REASON' => null,
            'REQ_APPROVED_ID' => 10,
            'PRODCT_WITHDRAW_QTY' => 0,
            'PRODCT_USED_QTY' => 0,
            'TYPE_ID' => '31',
        ]);

    }

    public function updateEquip(Request $request, $id)
    {
        // ดึงข้อมูลปัจจุบันของสินค้าจากฐานข้อมูล
        $product = DB::table('E_LIST_PRODUCT')->where('PRODCT_ID', $id)->first();

        // คำนวณค่าใหม่ของ PRODCT_USED_QTY
        $newUsedQty = $product->PRODCT_USED_QTY + $request->input('PRODCT_WITHDRAW_QTY');

        // อัปเดตข้อมูลในฐานข้อมูล
        DB::table('E_LIST_PRODUCT')
            ->where('PRODCT_ID', $id)
            ->update([
                'PRODCT_NAME' => $request->input('PRODCT_NAME'),
                'PRODCT_CURRENT_PRICE' => $request->input('PRODCT_CURRENT_PRICE'),
                'PRODCT_REASON' => $request->input('PRODCT_REASON'),
                'PRODCT_WITHDRAW_QTY' => $request->input('PRODCT_WITHDRAW_QTY'),
                'PRODCT_USED_QTY' => $newUsedQty, // อัปเดตค่าใหม่ของ PRODCT_USED_QTY
                'PRODCT_STATUS_DATE' => now(),
            ]);
        return response()->json([
            "success" => true,
            "PRODCT_QTY" => $product->PRODCT_QTY,
            "PRODCT_USED_QTY" => $newUsedQty, // Return the updated used quantity
        ]);
    }

    public function updateEquipUsed(Request $request, $id)
    {
        $currentValue = DB::table('E_LIST_PRODUCT')
            ->where('PRODCT_ID', $id)
            ->value('PRODCT_USED');

        $newValue = $currentValue == 'Y' ? 'N' : 'Y';

        DB::table('E_LIST_PRODUCT')
            ->where('PRODCT_ID', $id)
            ->update(['PRODCT_USED' => $newValue]);

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {

        DB::table('E_PLAN_PRODUCT')->insert([
            'CREATE_DATE' => now(),
            'BUDG_YEAR' => $request->input('BUDG_YEAR'),
            'TYPE_ID' => '31',
            'PRODCT_CAT_ID' => $request->input('PRODCT_CAT_ID'),
            'PRODCT_CAT_NAME' => $request->input('PRODCT_CAT_NAME'),
            'deptId' => Auth::user()->deptId,
            'userId' => Auth::user()->userId,
            'IC_USAGE_LINK_NO' => null,
            'PLAN_IS_ACTIVE' => 'Y',
            'Budget_ID' => '01',
            'REQ_APPROVED_ID' => '0',
        ]);

        toastr()->success('เพิ่มสำเร็จ!!');
        return redirect()->back();
    }

    public function show(string $id)
    {
        $E_PLAN = DB::table('แผนวัสดุ')
            ->where('PLAN_ID', $id)
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->first();

        $VW_PRODUCT = DB::table('VW_PRODUCT')->whereIn('IC_LOCAT_ID', [Auth::user()->deptId, 140]) // เพิ่มเงื่อนไขสำหรับ deptId
            ->whereIn('PRODCT_IOSTK_FLAG', ['I']) // เพิ่มเงื่อนไขสำหรับ PRODCT_IOSTK_FLAG->orderBy('PRODCT_ID', 'asc')
            ->get();

        if (!$E_PLAN) {
            abort(404); // ถ้าไม่พบข้อมูลให้แสดงหน้า 404
        } elseif ($E_PLAN) {
            $EQUIP_LIST = DB::table('วัสดุทั้งหมด')
                ->where('PLAN_ID', $id)
                ->orderBy('PRODCT_ID', 'desc')
                ->get();

            $EQUIP_STATUS = DB::table('วัสดุทั้งหมด')
                ->where('PLAN_ID', $id)
                ->first();
        }

        $E_TYPE = DB::table('E_TYPE')->whereIn('TYPE_ID', ['30', '31'])->get();
        $E_BUDGET = DB::table('E_BUDGET')->get();
        $E_STATUS = DB::table('E_STATUS')->get();

        $categories = DB::table('VW_PRODUCT')
            ->select('PRODCT_CAT_ID', 'PRODCT_CAT_NAME')
            ->orderBy('PRODCT_CAT_ID', 'asc')
            ->distinct()
            ->get();

        return view('insidewarehouses.show', compact('categories', 'E_PLAN', 'EQUIP_LIST', 'EQUIP_STATUS', 'E_STATUS', 'E_TYPE', 'E_BUDGET', 'VW_PRODUCT'));
    }

    public function update(Request $request, string $id)
    {
        $typeId = $request->TYPE_ID;

        if (
            $typeId == 30 || $typeId == 31
        ) {
            // อัปเดตข้อมูลในฐานข้อมูล
            DB::table('E_PLAN_PRODUCT')
                ->where('PLAN_ID', $id)
                ->update([
                    'BUDG_YEAR' => $request->BUDG_YEAR,
                    'PRODCT_CAT_ID' => $request->PRODCT_CAT_ID,
                    'PRODCT_CAT_NAME' => $request->PRODCT_CAT_NAME,
                    'Budget_ID' => $request->Budget_ID,
                    'TYPE_ID' => $typeId,
                ]);

            toastr()->success('อัปเดตสำเร็จ!!');

            if ($typeId == 30) {
                return redirect()->route('outsidewarehouses.show', $id);
            } elseif ($typeId == 31) {
                return redirect()->route('insidewarehouses.show', $id);
            }
        } else {
            // หาก TYPE_ID ไม่อยู่ในช่วงที่กำหนด ให้แสดงหน้า 404
            abort(404);
        }

    }

    public function destroy(string $id)
    {
        DB::table('E_PLAN_PRODUCT')
            ->where('PLAN_ID', $id)
            ->update([
                'PLAN_IS_ACTIVE' => 'N',
            ]);
        toastr()->success('ปิดใช้งานสำเร็จ!!');

        return redirect()->back();
    }

    public function destroy_equip(string $id)
    {
        DB::table('E_LIST_PRODUCT')
            ->where('PRODCT_ID', $id)
            ->delete();

        toastr()->success('ลบสำเร็จ!!');

        return redirect()->back();
    }

    public function approved(Request $request, $id)
    {
        $approvedId = $request->input('REQ_APPROVED_ID', 0); // Use 0 as default if nothing is sent

        if (Auth::user()->id != '114000041') {
            DB::table('E_PLAN_PRODUCT')
                ->where('PLAN_ID', $id)
                ->update(['REQ_APPROVED_ID' => $approvedId]);

            DB::table('E_LIST_PRODUCT')
                ->where('PLAN_ID', $id)
                ->where('PRODCT_USED', 'Y')
                ->where('REQ_APPROVED_ID', 10) // เพิ่มเงื่อนไขนี้
                ->update(['REQ_APPROVED_ID' => 1]);

            toastr()->success('ขออนุมัติสำเร็จ!!');
            return redirect()->back();

        }

        if (Auth::user()->id == '114000041') {
            DB::table('E_PLAN_PRODUCT')
                ->where('PLAN_ID', $id)
                ->update(['REQ_APPROVED_ID' => $approvedId]);
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

    }

    public function approved_list_product(Request $request, $id)
    {
        $approvedId = $request->input('REQ_APPROVED_ID', 10); // Default to 10 if no input

        DB::table('E_LIST_PRODUCT')
            ->where('PRODCT_ID', $id)
            ->where('PRODCT_USED', 'Y')
            ->update([
                'REQ_APPROVED_ID' => $approvedId,
            ]);

        // Check the approvedId and return the appropriate message
        if ($approvedId == 2) {
            return response()->json([
                'success' => true,
                'message' => 'อนุมัติสำเร็จ!!',
            ]);
        } elseif ($approvedId == 3) {
            return response()->json([
                'success' => true,
                'message' => 'ไม่อนุมัติ!!',
            ]);
        } elseif ($approvedId == 1) {
            return response()->json([
                'success' => true,
                'message' => 'ยกเลิกการอนุมัติ!!',
            ]);
        }

        // If something goes wrong, return an error message
        return response()->json([
            'success' => false,
            'message' => 'เกิดข้อผิดพลาดในการประมวลผล!',
        ]);
    }

    public function getPlanStatus($id)
    {
        $plans = DB::table('วัสดุทั้งหมด')
            ->where('PLAN_ID', $id)
            ->get(); // Returns a collection of records

        if ($plans->isEmpty()) {
            return response()->json(['error' => 'No plans found'], 404);
        }

        $result = $plans->map(function ($plan) {
            return [
                'PRODCT_USED' => $plan->PRODCT_USED,
                'PRODCT_REASON' => $plan->PRODCT_REASON,
                'REQ_APPROVED_ID' => $plan->REQ_APPROVED_ID,

            ];
        });

        return response()->json($result);
    }

}
