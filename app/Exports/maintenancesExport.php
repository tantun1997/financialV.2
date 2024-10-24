<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class maintenancesExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return DB::table('แผนครุภัณฑ์')
            ->select('PLAN_ID', 'PLAN_NAME', 'PLAN_SET_YEAR', 'PLAN_PRICE_PER', 'PLAN_QTY', 'PLAN_REASON', 'CREATE_DATE', 'TCHN_LOCAT_NAME', 'Budget_NAME')
            ->where('TYPE_ID', 1)
            ->orderBy('PLAN_ID', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'เลขที่แผน',
            'ชื่อแผนงาน',
            'ปีงบประมาณ',
            'ราคาต่อหน่วย',
            'จำนวน',
            'เหตุผลและความจำเป็น',
            'วันที่สร้าง',
            'หน่วยงานที่เบิก',
            'ประเภทงบ',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // กำหนดหัวเรื่องในเซลล์ A1
        $sheet->setCellValue('A1', 'ชื่อเรื่อง: แผนการจัดซื้อ');

        // ตั้งค่าการจัดรูปแบบให้กับเซลล์หัวเรื่อง
        $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
        $sheet->mergeCells('A1:I1'); // รวมเซลล์ A1 ถึง I1

        // กำหนดหัวข้อในแถวที่ 2
        $sheet->fromArray($this->headings(), null, 'A2');

        // ตั้งค่าการจัดรูปแบบให้กับแถวหัวข้อ
        $sheet->getStyle('A2:I2')->getFont()->setBold(true);
        $sheet->getStyle('A2:I2')->getAlignment()->setHorizontal('center');
    }
}
