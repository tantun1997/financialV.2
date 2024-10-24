<?php

namespace App\Http\Controllers;

use App\Exports\maintenancesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export($type)
    {
        if ($type == 'maintenances') {
            return $this->maintenancesExport();
        } elseif ($type == 'repairs') {
            return $this->repairsExport();
        } elseif ($type == 'contractservices') {
            return $this->contractservicesExport();
        } elseif ($type == 'calibrations') {
            return $this->calibrationsExport();
        } elseif ($type == 'potentials') {
            return $this->replacementsExport();
        } elseif ($type == 'replacements') {
            return $this->potentialsExport();
        } elseif ($type == 'noserials') {
            return $this->noserialsExport();
        } elseif ($type == 'outsidewarehouses') {
            return $this->outsidewarehousesExport();
        } elseif ($type == 'insidewarehouses') {
            return $this->insidewarehousesExport();
        } else {
            abort(404, 'Export type not found');
        }
    }
    public function maintenancesExport()
    {
        return Excel::download(new maintenancesExport, 'รายการแผนบำรุงรักษา.xlsx');
    }

    // public function repairsExport()
    // {
    //     return Excel::download(new repairsExport, 'รายการแผนซ่อม.xlsx');
    // }
    // public function contractservicesExport()
    // {
    //     return Excel::download(new contractservicesExport, 'รายการแผนจ้างเหมาบริการ.xlsx');
    // }// public function calibrationsExport()
    // {
    //     return Excel::download(new calibrationsExport, 'รายการแผนสอบเทียบเครื่องมือ.xlsx');
    // }// public function replacementsExport()
    // {
    //     return Excel::download(new replacementsExport, 'รายการแผนทดแทน.xlsx');
    // }// public function potentialsExport()
    // {
    //     return Excel::download(new potentialsExport, 'รายการแผนเพิ่มศักยภาพ.xlsx');
    // }// public function noserialsExport()
    // {
    //     return Excel::download(new noserialsExport, 'รายการแผนม่มีเลขครุภัณฑ์.xlsx');
    // }// public function outsidewarehousesExport()
    // {
    //     return Excel::download(new outsidewarehousesExport, 'รายการแผนวัสดุนอกคลัง.xlsx');
    // }// public function insidewarehousesExport()
    // {
    //     return Excel::download(new insidewarehousesExport, 'รายการแผนวัสดุในคลัง.xlsx');
    // }
}
