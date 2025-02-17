<?php

namespace Modules\FrontendExportPDF\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use TCPDF;  // 載入 TCPDF 套件
use Illuminate\Support\Facades\Log;  // 載入 Log 類別
use Modules\FrontendExportPDF\Http\Requests\FrontendExportPDFRequest;
use Modules\FrontendExportPDF\Services\FrontendExportPDFService;

class FrontendExportPDFController extends Controller
{
    protected $frontendExportPDFService;

    public function __construct(FrontendExportPDFService $frontendExportPDFService)
    {
        $this->frontendExportPDFService = $frontendExportPDFService;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    // 匯出 PDF
    public function exportPdf($request)
    {
        $pdf = $this->frontendExportPDFService->exportPdf($request);
        
        if ($pdf) {
            return $pdf;
        } else {
            return false;
        }
    }
}
