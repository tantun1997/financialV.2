<?php

namespace App\Services;

use Mpdf\Mpdf;

class PDFService
{
    protected $mpdf;

    public function __construct()
    {

        $this->mpdf = new Mpdf([
            // 'autoPageBreak' => true,
            // 'autoScriptToLang' => true,
            // 'autoLangToFont' => true,
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 9,
            'margin_left' => 20,
            'margin_right' => 14,
            'margin-bottom' => 3,
            'orientation' => 'P',
            'debug' => true,
        ]);
        // $this->mpdf->text_input_as_HTML = true;
        // $this->mpdf->allow_charset_conversion = true;
        // $this->mpdf->charset_in = 'utf-8';

        $this->mpdf->defaultheaderfontstyle = 'R';
        $this->mpdf->defaultheaderfontsize = 9;
        $this->mpdf->defaultheaderline = 0;
        $this->mpdf->defaultfooterfontstyle = 'R';
        $this->mpdf->defaultfooterfontsize = 9;
        $this->mpdf->defaultfooterline = 0;
        $this->mpdf->useDictionaryLBR = true;
    }
    public function setDefaultFont($font)
    {
        $this->mpdf->SetDefaultFont($font);
        $this->mpdf->watermark_font = $font;
    }

    public function addContent($view, $data = [])
    {
        $html = view($view, $data)->render();
        $this->mpdf->WriteHTML($html);
    }

    public function setHeader($data)
    {
        $this->mpdf->SetHeader($data, '', 'ALL', true);
    }

    public function setFooter($data)
    {
        $this->mpdf->SetFooter($data, '', 'ALL', true);
    }

    public function setWatermark($text, $alpha)
    {
        $this->mpdf->SetWatermarkText($text, $alpha);
        $this->mpdf->showWatermarkText = true;
    }

    public function addNewPage($orientation, $type, $resetpagenum, $pagenumstyle, $suppress, $marginLeft, $marginRight, $marginTop, $marginBottom, $marginHeader, $marginFooter, $oddHeaderName, $evenHeaderName, $oddFooterName, $evenFooterName, $oddHeaderValue, $evenHeaderValue, $oddFooterValue, $evenFooterValue, $pageselector, $sheetSize)
    {
        $this->mpdf->AddPage($orientation, $type, $resetpagenum, $pagenumstyle, $suppress, $marginLeft, $marginRight, $marginTop, $marginBottom, $marginHeader, $marginFooter, $oddHeaderName, $evenHeaderName, $oddFooterName, $evenFooterName, $oddHeaderValue, $evenHeaderValue, $oddFooterValue, $evenFooterValue, $pageselector, $sheetSize);
    }

    public function generateFromView($name)
    {
        $this->mpdf->Output($name, \Mpdf\Output\Destination::INLINE);
    }
}