<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FinancialReport extends Controller
{
    public function financial_report()
    {
        $Administration_total = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านอำนวยการ')
            ->sum('Total_Price');
        $Administration_true = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านอำนวยการ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Total_Price');
        $Administration_true_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านอำนวยการ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Currently_Price');
        $Administration_true_remaining = $Administration_true - $Administration_true_used;
        $Administration_spare = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านอำนวยการ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Total_Price');
        $Administration_spare_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านอำนวยการ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Currently_Price');
        $Administration_spare_remaining = $Administration_spare - $Administration_spare_used;

        $Nursing_total = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านการพยาบาล')
            ->sum('Total_Price');
        $Nursing_true = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านการพยาบาล')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Total_Price');
        $Nursing_true_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านการพยาบาล')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Currently_Price');
        $Nursing_true_remaining = $Nursing_true - $Nursing_true_used;
        $Nursing_spare = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านการพยาบาล')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Total_Price');
        $Nursing_spare_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านการพยาบาล')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Currently_Price');
        $Nursing_spare_remaining = $Nursing_spare - $Nursing_spare_used;

        $Secondary_total = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านบริการทุติยภูมิและตติยภูมิ')
            ->sum('Total_Price');
        $Secondary_true = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการทุติยภูมิและตติยภูมิ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Total_Price');
        $Secondary_true_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการทุติยภูมิและตติยภูมิ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Currently_Price');
        $Secondary_true_remaining = $Secondary_true - $Secondary_true_used;
        $Secondary_spare = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการทุติยภูมิและตติยภูมิ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Total_Price');
        $Secondary_spare_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการทุติยภูมิและตติยภูมิ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Currently_Price');
        $Secondary_spare_remaining = $Secondary_spare - $Secondary_spare_used;

        $Primary_total = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านบริการปฐมภูมิ')
            ->sum('Total_Price');
        $Primary_true = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการปฐมภูมิ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Total_Price');
        $Primary_true_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการปฐมภูมิ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Currently_Price');
        $Primary_true_remaining = $Primary_true - $Primary_true_used;
        $Primary_spare = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการปฐมภูมิ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Total_Price');
        $Primary_spare_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านบริการปฐมภูมิ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Currently_Price');
        $Primary_spare_remaining = $Primary_spare - $Primary_spare_used;

        $Supporting_total = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')
            ->where('WK_Group', 'ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ')
            ->sum('Total_Price');
        $Supporting_true = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Total_Price');
        $Supporting_true_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ')
            ->where('USAGE_STATUS_ID', 1)
            ->sum('Currently_Price');
        $Supporting_true_remaining = $Supporting_true - $Supporting_true_used;
        $Supporting_spare = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Total_Price');
        $Supporting_spare_used = DB::table('วงเงินแต่ละกลุ่มภารกิจ_ใหม่')->where('WK_Group', 'ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ')
            ->where('USAGE_STATUS_ID', 2)
            ->sum('Currently_Price');
        $Supporting_spare_remaining = $Supporting_spare - $Supporting_spare_used;

        return view('report.financial_report', compact(
            'Administration_total',
            'Administration_true',
            'Administration_true_used',
            'Administration_true_remaining',
            'Administration_spare',
            'Administration_spare_used',
            'Administration_spare_remaining',

            'Nursing_total',
            'Nursing_true',
            'Nursing_true_used',
            'Nursing_true_remaining',
            'Nursing_spare',
            'Nursing_spare_used',
            'Nursing_spare_remaining',

            'Secondary_total',
            'Secondary_true',
            'Secondary_true_used',
            'Secondary_true_remaining',
            'Secondary_spare',
            'Secondary_spare_used',
            'Secondary_spare_remaining',

            'Primary_total',
            'Primary_true',
            'Primary_true_used',
            'Primary_true_remaining',
            'Primary_spare',
            'Primary_spare_used',
            'Primary_spare_remaining',

            'Supporting_total',
            'Supporting_true',
            'Supporting_true_used',
            'Supporting_true_remaining',
            'Supporting_spare',
            'Supporting_spare_used',
            'Supporting_spare_remaining',

        ));
    }
    public function report($name_report)
    {
        switch ($name_report) {
            case 'administration_report':
                $name_group = 'ด้านอำนวยการ';
                break;
            case 'nursing_report':
                $name_group = 'ด้านการพยาบาล';
                break;
            case 'secondary_report':
                $name_group = 'ด้านบริการทุติยภูมิและตติยภูมิ';
                break;
            case 'primary_report':
                $name_group = 'ด้านบริการปฐมภูมิ';
                break;
            case 'supporting_report':
                $name_group = 'ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ';
                break;
        }

        $plan_true = DB::table('วงเงินแต่ละแผนก_ใหม่')
            ->where('WK_Group', $name_group)
            ->where('USAGE_STATUS_ID', 1)
            ->select(
                'TCHN_LOCAT_NAME',
                DB::raw('SUM(Total_Price) as total_price'),
                DB::raw('SUM(Total_Current_Price) as total_current_price')
            )->groupBy('TCHN_LOCAT_NAME')
            ->get();

        $plan_spare = DB::table('วงเงินแต่ละแผนก_ใหม่')
            ->where('WK_Group', $name_group)
            ->where('USAGE_STATUS_ID', 2)
            ->select(
                'TCHN_LOCAT_NAME',
                DB::raw('SUM(Total_Price) as total_price'),
                DB::raw('SUM(Total_Current_Price) as total_current_price')
            )->groupBy('TCHN_LOCAT_NAME')
            ->get();

        return view('report.report', compact(
            'plan_true',
            'plan_spare',
            'name_group'
        ));

    }
}
