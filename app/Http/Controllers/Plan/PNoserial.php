<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PNoserial extends Controller
{

    public function index(Request $request)
    {
        $TCHN_LOCAT_NAME = $request->get('TCHN_LOCAT_NAME'); // รับค่า type_name จาก request

        // ตรวจสอบว่า user เป็น Admin หรือไม่
        if (Auth::user()->isAdmin == 'Y') {
            $query = DB::table('แผนครุภัณฑ์')
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_NUMBER', 7);
        } else {
            $query = DB::table('แผนครุภัณฑ์')
                ->where('deptId', Auth::user()->deptId)
                ->where('PLAN_IS_ACTIVE', '!=', 'N')
                ->where('TYPE_NUMBER', 7);
        }

        // หากมีการส่ง type_name ให้ค้นหาเพิ่มเติม
        if ($TCHN_LOCAT_NAME) {
            $query->where('TCHN_LOCAT_NAME', $TCHN_LOCAT_NAME); // ค้นหาตาม TYPE_NAME
        }

        // ดึงข้อมูลตามเงื่อนไขที่กำหนด
        $E_PLAN = $query->orderBy('PLAN_ID', 'desc')->get();

        // ดึงข้อมูลอื่นๆ ที่จำเป็น
        $E_TYPE = DB::table('E_TYPE')->where('TYPE_NUMBER', 7)->get();
        $E_BUDGET = DB::table('E_BUDGET')->get();
        $E_USAGE_STATUS = DB::table('E_USAGE_STATUS')->get();
        $E_CLOSE_PLAN = DB::table('E_CLOSE_PLAN')->where('id', 1)->first();

        return view('noserials.index', compact('E_PLAN', 'E_TYPE', 'E_BUDGET', 'E_USAGE_STATUS', 'E_CLOSE_PLAN'));
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
    public function create(string $EQUP_LINK_NO, string $PLAN_ID)
    {
        // ตรวจสอบว่า EQUP_LINK_NO และ PLAN_ID รับค่าอย่างถูกต้อง
        $VW_EQUIPMENT = DB::table('VW_EQUIPMENT')
            ->where('EQUP_LINK_NO', $EQUP_LINK_NO)
            ->first();

        DB::table('E_LIST_ITEM')->insert([
            'EQUP_SERIAL_NUMBER' => $VW_EQUIPMENT->EQUP_ID,
            'EQUP_NAME' => $VW_EQUIPMENT->EQUP_NAME,
            'EQUP_TCHN_LOCAT_ID' => $VW_EQUIPMENT->TCHN_LOCAT_ID,
            'EQUP_STS_ID' => $VW_EQUIPMENT->EQUP_STS_ID,
            'EQUP_PRICE' => $VW_EQUIPMENT->EQUP_PRICE,
            'EQUP_LINK_NO' => $VW_EQUIPMENT->EQUP_LINK_NO,
            'EQUP_USED' => 0,
            'PLAN_ID' => $PLAN_ID,
            'EQUP_QTY' => 1,
            'STATUS_ID' => 0,
            'EQUP_STATUS_DATE' => now(),
            'EQUP_CREATED_DATE' => now(),
            'EQUP_AGE' => $VW_EQUIPMENT->age,
        ]);
    }

    public function updateEquip(Request $request, $id)
    {
        // อัพเดตข้อมูลในฐานข้อมูล
        DB::table('E_LIST_ITEM')
            ->where('EQUP_ID', $id)
            ->update([
                'EQUP_NAME' => $request->input('EQUP_NAME'),
                'EQUP_CURRENT_PRICE' => $request->input('EQUP_CURRENT_PRICE'),
                'EQUP_QTY' => $request->input('EQUP_QTY'),
                'STATUS_ID' => $request->input('STATUS_ID'),
                'EQUP_STATUS_DATE' => now(),
            ]);
        toastr()->success('อัปเดตสำเร็จ!!');

        // กลับไปหน้าเดิมพร้อมแจ้งเตือน
        return redirect()->back();
    }

    public function updateEquipUsed(Request $request, $id, $PLAN_ID)
    {
        $clickedItem = DB::table('E_LIST_ITEM')->where('EQUP_ID', $id)->first();

        if ($clickedItem->EQUP_USED == '0') {
            // Update the selected equipment to 'used'
            DB::table('E_LIST_ITEM')
                ->where('EQUP_ID', $id)
                ->where('Plan_ID', $PLAN_ID)
                ->update(['EQUP_USED' => '1']);

            // Update all other equipment in the same plan to 'not used'
            DB::table('E_LIST_ITEM')
                ->where('EQUP_ID', '!=', $id)
                ->where('Plan_ID', $PLAN_ID)
                ->update(['EQUP_USED' => '0']);
        }

        // Return JSON response
        return response()->json(['success' => true]);
    }

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
            'PLAN_IS_ACTIVE' => 'Y',
            'Budget_ID' => $request->input('Budget_ID'),
            'REQ_APPROVED_ID' => 0,

        ]);
        toastr()->success('เพิ่มสำเร็จ!!');
        return redirect()->back();
    }

    public function show(string $id)
    {

        $VW_EQUIPMENT = DB::table('VW_EQUIPMENT')->orderBy('EQUP_ID', 'asc')
            ->get();

        $E_PLAN = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_ID', $id)
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
        // ->where('TYPE_ID', 1)
            ->first();

        if (!$E_PLAN) {
            abort(404); // ถ้าไม่พบข้อมูลให้แสดงหน้า 404
        } elseif ($E_PLAN) {
            $EQUIP_LIST = DB::table('ครุภัณฑ์ทั้งหมด')
                ->where('PLAN_ID', $id)
                ->orderBy('EQUP_ID', 'asc')
                ->get();

            $EQUIP_STATUS = DB::table('ครุภัณฑ์ทั้งหมด')
                ->where('PLAN_ID', $id)
                ->first();
        }

        $E_TYPE = DB::table('E_TYPE')->get();
        $E_BUDGET = DB::table('E_BUDGET')->get();
        $E_USAGE_STATUS = DB::table('E_USAGE_STATUS')->get();
        $E_STATUS = DB::table('E_STATUS')->get();

        return view('noserials.show', compact('E_PLAN', 'EQUIP_LIST', 'EQUIP_STATUS', 'E_STATUS', 'E_TYPE', 'E_BUDGET', 'E_USAGE_STATUS', 'VW_EQUIPMENT'));
    }

    public function edit(string $id)
    {
        return view('noserials.edit');
    }

    public function update(Request $request, string $id)
    {
        $typeId = $request->TYPE_ID;

        // ตรวจสอบว่า TYPE_ID อยู่ในช่วงที่กำหนด
        if (
            $typeId == 1 || ($typeId >= 2 && $typeId <= 14) || ($typeId >= 15 && $typeId <= 25) || $typeId == 26
            || $typeId == 27 || $typeId == 28 || $typeId == 29 || $typeId == 30 || $typeId == 31
        ) {
            // อัปเดตข้อมูลในฐานข้อมูล
            DB::table('E_PLAN')
                ->where('PLAN_ID', $id)
                ->update([
                    'PLAN_SET_YEAR' => $request->PLAN_SET_YEAR,
                    'PLAN_NAME' => $request->PLAN_NAME,
                    'PLAN_PRICE_PER' => $request->PLAN_PRICE_PER,
                    'PLAN_QTY' => $request->PLAN_QTY,
                    'PLAN_REASON' => $request->PLAN_REASON,
                    'PLAN_REMARK' => $request->PLAN_REMARK,
                    'Budget_ID' => $request->Budget_ID,
                    'USAGE_STATUS_ID' => $request->USAGE_STATUS_ID,
                    'TYPE_ID' => $typeId,
                ]);

            toastr()->success('อัปเดตสำเร็จ!!');

            // ทำการ redirect ตาม TYPE_ID
            if ($typeId == 1) {
                return redirect()->route('noserials.show', $id);
            } elseif ($typeId >= 2 && $typeId <= 14) {
                return redirect()->route('noserials.show', $id);
            } elseif ($typeId >= 15 && $typeId <= 25) {
                return redirect()->route('contractservices.show', $id);
            } elseif ($typeId == 26) {
                return redirect()->route('calibrations.show', $id);
            } elseif ($typeId == 27) {
                return redirect()->route('potentials.show', $id);
            } elseif ($typeId == 28) {
                return redirect()->route('replacements.show', $id);
            } elseif ($typeId == 29) {
                return redirect()->route('noserials.show', $id);
            } elseif ($typeId == 30) {
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
        DB::table('E_PLAN')
            ->where('PLAN_ID', $id)
            ->update([
                'PLAN_IS_ACTIVE' => 'N',
            ]);
        toastr()->success('ปิดใช้งานสำเร็จ!!');

        return redirect()->back();
    }
    public function destroy_equip(string $id)
    {
        DB::table('E_LIST_ITEM')
            ->where('EQUP_ID', $id)
            ->delete();

        toastr()->success('ลบสำเร็จ!!');

        return redirect()->back();
    }

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
    public function getPlanStatus($id)
    {
        $plan = DB::table('แผนครุภัณฑ์')
            ->where('PLAN_ID', $id)
            ->where('PLAN_IS_ACTIVE', '!=', 'N')
            ->first();

        if (!$plan) {
            return response()->json(['error' => 'Plan not found'], 404);
        }

        return response()->json([
            'usage_status_id' => $plan->USAGE_STATUS_ID,
            'total_current_price' => $plan->Total_Current_Price,
            'checked_use' => $plan->Checked_use,
            'REQ_APPROVED_ID' => $plan->REQ_APPROVED_ID,

        ]);
    }
    public function addRow($id)
    {
        $selected = DB::table('E_PLAN')
            ->select(['PLAN_NAME', 'PLAN_PRICE_PER'])
            ->where('PLAN_ID', $id) // เปลี่ยน $PLAN_ID เป็น $id
            ->first();

        DB::table('E_LIST_ITEM')->insert([
            'EQUP_NAME' => $selected->PLAN_NAME,
            'PLAN_ID' => $id, // เปลี่ยน $PLAN_ID เป็น $id
            'EQUP_CURRENT_PRICE' => $selected->PLAN_PRICE_PER,
            'EQUP_USED' => 0,
            'EQUP_QTY' => 1,
            'STATUS_ID' => 0,
            'EQUP_STATUS_DATE' => now(),
            'EQUP_CREATED_DATE' => now(),
        ]);

        toastr()->success('เพิ่มสำเร็จ!!');

        return redirect()->back();

    }
}
