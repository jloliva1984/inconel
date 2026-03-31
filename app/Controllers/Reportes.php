<?php

namespace App\Controllers;

use App\Models\ViviendaModel;
use App\Models\UsuarioModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Reportes extends BaseController
{
    protected ViviendaModel $model;
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->model        = new ViviendaModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index(): string
    {
        $stats = $this->model->getSummaryStats();

        return view('reportes/index', [
            'title' => 'Reportes - Inconel Building',
            'stats' => $stats,
        ]);
    }

    // ================================================================
    // GARANTÍAS
    // ================================================================

    public function garantias(): string
    {
        $tecnicos = $this->usuarioModel->getTecnicos();

        return view('reportes/garantias', [
            'title'    => 'Reporte de Garantías - Inconel Building',
            'tecnicos' => $tecnicos,
        ]);
    }

    public function garantiasDatatable(): \CodeIgniter\HTTP\ResponseInterface
    {
        $params = $this->request->getGet();
        $data   = $this->model->getWarrantyDatatableData($params);

        return $this->response->setJSON($data);
    }

    public function garantiasExcel(): void
    {
        $filters   = $this->request->getGet();
        $viviendas = $this->model->getWarrantyReport($filters);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Garantías');

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a3a5c']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Title
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'INCONEL BUILDING - REPORTE DE GARANTÍAS');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Generado el: ' . date('m/d/Y H:i:s'));

        // Legend
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Garantía Mano de Obra: 1 año | Garantía Equipamiento: 10 años');

        // Headers row 4
        $headers = ['#', 'Dirección', 'Fecha Venta', 'Técnico',
                    'Venc. Mano Obra', 'Estado M.O.', 'Días restantes M.O.',
                    'Venc. Equipamiento', 'Estado Equip.', 'Días restantes Equip.'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->applyFromArray($headerStyle);
            $col++;
        }

        $row = 5;
        foreach ($viviendas as $i => $v) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $v['direccion']);
            $sheet->setCellValue('C' . $row, $v['fecha_venta']);
            $sheet->setCellValue('D' . $row, $v['tecnico']);
            $sheet->setCellValue('E' . $row, $v['vencimiento_mano_obra']);
            $sheet->setCellValue('F' . $row, $this->translateStatus($v['garantia_mano_obra']));
            $sheet->setCellValue('G' . $row, $v['dias_garantia_labor']);
            $sheet->setCellValue('H' . $row, $v['vencimiento_equipamiento']);
            $sheet->setCellValue('I' . $row, $this->translateStatus($v['garantia_equipamiento']));
            $sheet->setCellValue('J' . $row, $v['dias_garantia_equipamiento']);

            // Color coding
            $colorF = $this->getStatusColor($v['garantia_mano_obra']);
            $colorI = $this->getStatusColor($v['garantia_equipamiento']);

            if ($colorF) {
                $sheet->getStyle('F' . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colorF]],
                    'font' => ['bold' => true],
                ]);
            }
            if ($colorI) {
                $sheet->getStyle('I' . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colorI]],
                    'font' => ['bold' => true],
                ]);
            }
            $row++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'garantias_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function garantiasPdf(): void
    {
        $filters   = $this->request->getGet();
        $viviendas = $this->model->getWarrantyReport($filters);
        $html      = view('reportes/garantias_pdf', [
            'viviendas' => $viviendas,
            'fecha'     => date('m/d/Y H:i:s'),
        ], ['saveData' => true]);

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'margin_top' => 15]);
        $mpdf->SetTitle('Reporte de Garantías - Inconel Building');
        $mpdf->WriteHTML($html);
        $mpdf->Output('garantias_' . date('Ymd') . '.pdf', 'D');
        exit;
    }

    public function garantiasImprimir(): string
    {
        $filters   = $this->request->getGet();
        $viviendas = $this->model->getWarrantyReport($filters);

        return view('reportes/garantias_imprimir', [
            'title'     => 'Reporte de Garantías',
            'viviendas' => $viviendas,
            'fecha'     => date('m/d/Y H:i:s'),
        ]);
    }

    // ================================================================
    // POR TÉCNICO
    // ================================================================

    public function porTecnico(): string
    {
        return view('reportes/por_tecnico', [
            'title' => 'Reporte por Técnico - Inconel Building',
        ]);
    }

    public function porTecnicoDatatable(): \CodeIgniter\HTTP\ResponseInterface
    {
        $params = $this->request->getGet();
        $data   = $this->model->getByTecnicoDatatable($params);

        return $this->response->setJSON($data);
    }

    public function porTecnicoExcel(): void
    {
        $data = $this->model->getByTecnicoDatatable([]);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Por Técnico');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a3a5c']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'INCONEL BUILDING - REPORTE POR TÉCNICO');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers = ['#', 'Técnico', 'Total Viviendas', 'G. Labor Activa', 'G. Labor Vencida', 'G. Equip. Activa', 'G. Equip. Vencida'];
        $col     = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '2', $header);
            $sheet->getStyle($col . '2')->applyFromArray($headerStyle);
            $col++;
        }

        $row = 3;
        foreach ($data['data'] as $i => $v) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $v['tecnico']);
            $sheet->setCellValue('C' . $row, $v['total']);
            $sheet->setCellValue('D' . $row, $v['labor_activa']);
            $sheet->setCellValue('E' . $row, $v['labor_vencida']);
            $sheet->setCellValue('F' . $row, $v['equip_activa']);
            $sheet->setCellValue('G' . $row, $v['equip_vencida']);
            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'reporte_tecnico_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function porTecnicoPdf(): void
    {
        $data = $this->model->getByTecnicoDatatable([]);
        $html = view('reportes/tecnico_pdf', [
            'viviendas' => $data['data'],
            'fecha'     => date('m/d/Y H:i:s'),
        ], ['saveData' => true]);

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 15]);
        $mpdf->SetTitle('Reporte por Técnico - Inconel Building');
        $mpdf->WriteHTML($html);
        $mpdf->Output('reporte_tecnico_' . date('Ymd') . '.pdf', 'D');
        exit;
    }

    // ================================================================
    // VENCIMIENTOS
    // ================================================================

    public function vencimientos(): string
    {
        return view('reportes/vencimientos', [
            'title' => 'Reporte de Vencimientos - Inconel Building',
        ]);
    }

    public function vencimientosDatatable(): \CodeIgniter\HTTP\ResponseInterface
    {
        $params = $this->request->getGet();
        $data   = $this->model->getWarrantyDatatableData($params);

        return $this->response->setJSON($data);
    }

    public function vencimientosExcel(): void
    {
        $this->garantiasExcel();
    }

    public function vencimientosPdf(): void
    {
        $this->garantiasPdf();
    }

    // ================================================================
    // RESUMEN GENERAL
    // ================================================================

    public function resumen(): string
    {
        $stats    = $this->model->getSummaryStats();
        $tecnicos = $this->model->getByTecnicoDatatable([]);

        return view('reportes/resumen', [
            'title'    => 'Resumen General - Inconel Building',
            'stats'    => $stats,
            'tecnicos' => $tecnicos['data'],
        ]);
    }

    public function resumenExcel(): void
    {
        $stats    = $this->model->getSummaryStats();
        $tecnicos = $this->model->getByTecnicoDatatable([]);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Resumen');

        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'INCONEL BUILDING - RESUMEN GENERAL');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'ESTADÍSTICAS GENERALES');
        $sheet->getStyle('A3')->getFont()->setBold(true);

        $statsData = [
            ['Total Viviendas', $stats['total_viviendas']],
            ['Garantía Labor Activa', $stats['garantia_labor_activa']],
            ['Garantía Labor Vencida', $stats['garantia_labor_vencida']],
            ['Garantía Labor Por Vencer', $stats['garantia_labor_por_vencer']],
            ['Garantía Equip. Activa', $stats['garantia_equip_activa']],
            ['Garantía Equip. Vencida', $stats['garantia_equip_vencida']],
            ['Garantía Equip. Por Vencer', $stats['garantia_equip_por_vencer']],
        ];

        $row = 4;
        foreach ($statsData as $stat) {
            $sheet->setCellValue('A' . $row, $stat[0]);
            $sheet->setCellValue('B' . $row, $stat[1]);
            $row++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'resumen_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function resumenPdf(): void
    {
        $stats    = $this->model->getSummaryStats();
        $tecnicos = $this->model->getByTecnicoDatatable([]);

        $html = view('reportes/resumen_pdf', [
            'stats'    => $stats,
            'tecnicos' => $tecnicos['data'],
            'fecha'    => date('m/d/Y H:i:s'),
        ], ['saveData' => true]);

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 15]);
        $mpdf->SetTitle('Resumen General - Inconel Building');
        $mpdf->WriteHTML($html);
        $mpdf->Output('resumen_' . date('Ymd') . '.pdf', 'D');
        exit;
    }

    // ================================================================
    // Helpers
    // ================================================================

    private function translateStatus(string $status): string
    {
        return match ($status) {
            'activa'     => 'ACTIVA',
            'vencida'    => 'VENCIDA',
            'por_vencer' => 'POR VENCER',
            default      => $status,
        };
    }

    private function getStatusColor(string $status): ?string
    {
        return match ($status) {
            'activa'     => '92D050',
            'vencida'    => 'FF0000',
            'por_vencer' => 'FFFF00',
            default      => null,
        };
    }
}
