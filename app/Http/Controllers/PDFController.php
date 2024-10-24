<?php

namespace App\Http\Controllers;

use App\Services\PDFService;
// use thsplitlib\Segment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

Carbon::setLocale('th');

class PDFController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
        $this->pdfService->setDefaultFont('garuda');
    }

    public function generatePDF($id, $type)
    {
        // Define table names and templates based on the type
        switch ($type) {
            case 'maintenances':
                $typeId = 1;
                break;
            case 'repairs':
                $typeId = 2;
                break;
            case 'contractservices':
                $typeId = 3;
                break;
            case 'calibrations':
                $typeId = 4;
                break;
            case 'potentials':
                $typeId = 5;
                break;
            case 'replacements':
                $typeId = 6;
                break;
            case 'noserials':
                $typeId = 7;
                break;

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

        // Fetch plan data based on the type
        $PLAN = DB::table('PDFชื่อแผนครุภัณฑ์')
            ->where('PLAN_ID', $id)
            ->when($typeId, function ($query) use ($typeId) {
                return $query->where('TYPE_NUMBER', $typeId);
            })
            ->first();

        if (!$PLAN) {
            return response()->json(['error' => 'Plan not found'], 404);
        }

        // Extract common plan fields
        $PLAN_NAME = $PLAN->PLAN_NAME;
        $PLAN_SET_YEAR = $PLAN->PLAN_SET_YEAR ?? $PLAN->PLAN_YEAR;
        $PLAN_DEPT = $PLAN->TCHN_LOCAT_NAME;
        $PLAN_REASON = $PLAN->PLAN_REASON;
        $Total_Used = $PLAN->Total_Used;

        // Fetch equipment list based on the type
        $List_Equip = DB::table('PDFรายการครุภัณฑ์แต่ละแผน')->where('EQUP_USED', 1)->where('PLAN_ID', $id)->get();
        $tel = '';

        // Common date and time formatting
        $dateExport = Carbon::now()->addYears(543)->translatedFormat('d F Y');
        $timeExport = Carbon::now()->format('H:i:s');
        $datePDF = Carbon::now()->addYears(543)->translatedFormat('Ymd');

        // Remaining price details
        $Remaining_Price = $PLAN->Total_Current_Price;
        $Remaining_Price_Text = $this->numberToThaiText($Remaining_Price);

        // Prepare data for the PDF
        $data = [
            'id' => $id,
            'PLAN_NAME' => $PLAN_NAME,
            'PLAN_SET_YEAR' => $PLAN_SET_YEAR,
            'PLAN_DEPT' => $PLAN_DEPT,
            'PLAN_REASON' => $PLAN_REASON,
            'Total_Used' => $Total_Used,
            'Remaining_Price' => $Remaining_Price,
            'Remaining_Price_Text' => $Remaining_Price_Text,
            'List_Equip' => $List_Equip,
            'tel' => $tel,
            'dateExport' => $dateExport,
        ];

        // Generate the PDF
        $this->pdfService->setWatermark('เอกสารราชการ รพ.สมเด็จฯลฯ ห้ามปลอมแปลง', 0.1);
        $this->pdfService->addContent('pdf.TemplatePage1', $data);
        // Check if typeId is not in the specified range
        if (!in_array($typeId, [3, 4, 5, 7, 8, 9])) {
            // Only add new page, header, and footer for certain typeIds
            $this->pdfService->addNewPage('L', '', '1', '', '', '10', '10', '20', '20', '5', '5', '', '', '', '', '', '', '', '', '', 'A4');
            $this->pdfService->setHeader('โรงพยาบาลสมเด็จพระพุทธเลิศหล้า||หน้า {PAGENO}/{nbpg}');
            $this->pdfService->setFooter('||วันที่พิมพ์ : ' . $dateExport . ' ' . $timeExport);
            $this->pdfService->addContent('pdf.TemplatePage2', $data);
        }

        // Generate and return the PDF
        return $this->pdfService->generateFromView($PLAN_SET_YEAR . '_' . $id . '-' . $datePDF);
    }

    public function pdf_generate_product($id)
    {
        // Fetch plan data
        $PLAN = DB::table('PDFชื่อแผนวัสดุ')
            ->where('PLAN_ID', $id)
            ->first();

        if (!$PLAN) {
            return response()->json(['error' => 'แผนไม่พบ'], 404);
        }
        // Fetch equipment list
        $List_Equip = DB::table('PDFรายการวัสดุ')->where('PRODCT_USED', 'Y')->where('PLAN_ID', $id)->where('REQ_APPROVED_ID', 2)->whereNotNull('PRODCT_REASON')->orderBy('PRODCT_ID', 'desc')->get();
        $tel = '';

        // Common date and time formatting
        $dateExport = Carbon::now()->addYears(543)->translatedFormat('d F Y');
        $timeExport = Carbon::now()->format('H:i:s');
        $datePDF = Carbon::now()->addYears(543)->translatedFormat('Ymd');

        // Remaining price details
        $Remaining_Price = $List_Equip->sum(function ($item) {
            return $item->PRODCT_CURRENT_PRICE * $item->PRODCT_WITHDRAW_QTY;
        });
        $Remaining_Price_Text = $this->numberToThaiText($Remaining_Price);

        // Prepare data for the PDF
        $data = [
            'id' => $id,
            'PRODCT_CAT_NAME' => $PLAN->PRODCT_CAT_NAME,
            'BUDG_YEAR' => $PLAN->BUDG_YEAR,
            'TCHN_LOCAT_NAME' => $PLAN->TCHN_LOCAT_NAME,
            'PRODCT_REASON' => $List_Equip->pluck('PRODCT_REASON')->implode(' '),
            'Total_Used' => count($List_Equip),
            'Remaining_Price' => $Remaining_Price,
            'Remaining_Price_Text' => $Remaining_Price_Text,
            'List_Equip' => $List_Equip,
            'tel' => $tel,
            'dateExport' => $dateExport,
        ];

        // Generate the PDF
        $this->pdfService->setWatermark('เอกสารราชการ รพ.สมเด็จฯลฯ ห้ามปลอมแปลง', 0.1);
        $this->pdfService->addContent('pdf.WarehouseTemplate', $data);

        // Generate and return the PDF
        return $this->pdfService->generateFromView($PLAN->BUDG_YEAR . '_' . $id . '-' . $datePDF);
    }

    protected function numberToThaiText($number, $include_unit = true, $display_zero = true)
    {
        $BAHT_TEXT_NUMBERS = ['ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        $BAHT_TEXT_UNITS = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบล้าน', 'ร้อยล้าน'];
        $BAHT_TEXT_ONE_IN_TENTH = 'เอ็ด';
        $BAHT_TEXT_TWENTY = 'ยี่';
        $BAHT_TEXT_INTEGER = 'ถ้วน';
        $BAHT_TEXT_BAHT = 'บาท';
        $BAHT_TEXT_SATANG = 'สตางค์';
        $BAHT_TEXT_POINT = 'จุด';

        if (!is_numeric($number)) {
            return null;
        }

        $log = floor(log($number, 10));
        if ($log > 5) {
            $millions = floor($log / 6);
            $million_value = pow(1000000, $millions);
            $normalised_million = floor($number / $million_value);
            $rest = $number - $normalised_million * $million_value;
            $millions_text = '';
            for ($i = 0; $i < $millions; $i++) {
                $millions_text .= $BAHT_TEXT_UNITS[6];
            }
            return $this->numberToThaiText($normalised_million, false) . $millions_text . $this->numberToThaiText($rest, true, false);
        }

        $number_str = (string) floor($number);
        $text = '';
        $unit = 0;

        if ($display_zero && $number_str == '0') {
            $text = $BAHT_TEXT_NUMBERS[0];
        } else {
            for ($i = strlen($number_str) - 1; $i > -1; $i--) {
                $current_number = (int) $number_str[$i];

                $unit_text = '';
                if ($unit == 0 && $i > 0) {
                    $previous_number = isset($number_str[$i - 1]) ? (int) $number_str[$i - 1] : 0;
                    if ($current_number == 1 && $previous_number > 0) {
                        $unit_text .= $BAHT_TEXT_ONE_IN_TENTH;
                    } elseif ($current_number > 0) {
                        $unit_text .= $BAHT_TEXT_NUMBERS[$current_number];
                    }
                } elseif ($unit == 1 && $current_number == 2) {
                    $unit_text .= $BAHT_TEXT_TWENTY;
                } elseif ($current_number > 0 && ($unit != 1 || $current_number != 1)) {
                    $unit_text .= $BAHT_TEXT_NUMBERS[$current_number];
                }

                if ($current_number > 0) {
                    $unit_text .= $BAHT_TEXT_UNITS[$unit];
                }

                $text = $unit_text . $text;
                $unit++;
            }
        }

        if ($include_unit) {
            $text .= $BAHT_TEXT_BAHT;

            $satang = explode('.', number_format($number, 2, '.', ''))[1];
            $text .= $satang == 0 ? $BAHT_TEXT_INTEGER : $this->numberToThaiText($satang, false) . $BAHT_TEXT_SATANG;
        } else {
            $exploded = explode('.', $number);
            if (isset($exploded[1])) {
                $text .= $BAHT_TEXT_POINT;
                $decimal = (string) $exploded[1];
                for ($i = 0; $i < strlen($decimal); $i++) {
                    $text .= $BAHT_TEXT_NUMBERS[$decimal[$i]];
                }
            }
        }

        return $text;
    }
}
