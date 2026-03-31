<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ViviendaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Viviendas extends BaseController
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
        return view('viviendas/index', [
            'title' => 'Viviendas - Inconel Building',
        ]);
    }

    /**
     * DataTables AJAX
     */
    public function datatable(): \CodeIgniter\HTTP\ResponseInterface
    {
        $params = $this->request->getGet();
        $data   = $this->model->getDatatableData($params);

        $userRole = session()->get('user_role');

        foreach ($data['data'] as &$row) {
            $row['acciones'] = $this->buildActions($row, $userRole);
        }

        return $this->response->setJSON($data);
    }

    public function crear(): string
    {
        $tecnicos = $this->usuarioModel->getTecnicos();

        // Pre-select current user if technician
        $defaultTecnico = null;
        if (session()->get('user_role') === 'tecnico') {
            $defaultTecnico = session()->get('user_id');
        }

        return view('viviendas/form', [
            'title'          => 'Registrar Vivienda - Inconel Building',
            'vivienda'       => null,
            'tecnicos'       => $tecnicos,
            'action'         => site_url('viviendas/guardar'),
            'defaultTecnico' => $defaultTecnico,
        ]);
    }

    public function guardar(): \CodeIgniter\HTTP\RedirectResponse
    {
        $rules = [
            'direccion'            => 'required|min_length[5]|max_length[300]',
            'fecha_instalacion_ac' => 'required',
            'serie_handler'        => 'required|max_length[100]',
            'serie_condenser'      => 'required|max_length[100]',
            'tecnico_id'           => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'direccion'            => $this->request->getPost('direccion'),
            'fecha_instalacion_ac' => $this->request->getPost('fecha_instalacion_ac'),
            'fecha_arranque_ac'    => $this->request->getPost('fecha_arranque_ac') ?: null,
            'serie_handler'        => $this->request->getPost('serie_handler'),
            'serie_condenser'      => $this->request->getPost('serie_condenser'),
            'fecha_venta'          => $this->request->getPost('fecha_venta') ?: null,
            'tecnico_id'           => (int) $this->request->getPost('tecnico_id'),
            'notas'                => $this->request->getPost('notas'),
        ];

        $this->model->insert($data);

        return redirect()->to('/viviendas')->with('success', 'Vivienda registrada correctamente.');
    }

    public function ver(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $vivienda = $this->model->getWithTecnico($id);

        if (empty($vivienda)) {
            return redirect()->to('/viviendas')->with('error', 'Vivienda no encontrada.');
        }

        // Calculate warranties (only if fecha_venta is set and valid)
        $fechaVentaValida = ! empty($vivienda['fecha_venta']) && $vivienda['fecha_venta'] !== '0000-00-00';

        if ($fechaVentaValida) {
            $fechaVenta = new \DateTime($vivienda['fecha_venta']);
            $hoy        = new \DateTime();

            $vencimientoLabor = (clone $fechaVenta)->modify('+1 year');
            $vencimientoEquip = (clone $fechaVenta)->modify('+10 years');

            $vivienda['vencimiento_labor']        = $vencimientoLabor->format('Y-m-d');
            $vivienda['vencimiento_equipamiento'] = $vencimientoEquip->format('Y-m-d');
            $vivienda['dias_labor']               = (int) $hoy->diff($vencimientoLabor)->format('%r%a');
            $vivienda['dias_equipamiento']        = (int) $hoy->diff($vencimientoEquip)->format('%r%a');
            $vivienda['garantia_labor']           = $vivienda['dias_labor'] > 0 ? ($vivienda['dias_labor'] <= 30 ? 'por_vencer' : 'activa') : 'vencida';
            $vivienda['garantia_equipamiento']    = $vivienda['dias_equipamiento'] > 0 ? ($vivienda['dias_equipamiento'] <= 90 ? 'por_vencer' : 'activa') : 'vencida';
        } else {
            $vivienda['vencimiento_labor']        = null;
            $vivienda['vencimiento_equipamiento'] = null;
            $vivienda['dias_labor']               = null;
            $vivienda['dias_equipamiento']        = null;
            $vivienda['garantia_labor']           = 'sin_fecha';
            $vivienda['garantia_equipamiento']    = 'sin_fecha';
        }

        return view('viviendas/ver', [
            'title'    => 'Ver Vivienda - Inconel Building',
            'vivienda' => $vivienda,
        ]);
    }

    public function editar(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $vivienda = $this->model->find($id);

        if ($vivienda === null) {
            return redirect()->to('/viviendas')->with('error', 'Vivienda no encontrada.');
        }

        $tecnicos = $this->usuarioModel->getTecnicos();

        return view('viviendas/form', [
            'title'    => 'Editar Vivienda - Inconel Building',
            'vivienda' => $vivienda,
            'tecnicos' => $tecnicos,
            'action'   => site_url('viviendas/actualizar/' . $id),
        ]);
    }

    public function actualizar(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $vivienda = $this->model->find($id);

        if ($vivienda === null) {
            return redirect()->to('/viviendas')->with('error', 'Vivienda no encontrada.');
        }

        $rules = [
            'direccion'            => 'required|min_length[5]|max_length[300]',
            'fecha_instalacion_ac' => 'required',
            'serie_handler'        => 'required|max_length[100]',
            'serie_condenser'      => 'required|max_length[100]',
            'tecnico_id'           => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'direccion'            => $this->request->getPost('direccion'),
            'fecha_instalacion_ac' => $this->request->getPost('fecha_instalacion_ac'),
            'fecha_arranque_ac'    => $this->request->getPost('fecha_arranque_ac') ?: null,
            'serie_handler'        => $this->request->getPost('serie_handler'),
            'serie_condenser'      => $this->request->getPost('serie_condenser'),
            'fecha_venta'          => $this->request->getPost('fecha_venta') ?: null,
            'tecnico_id'           => (int) $this->request->getPost('tecnico_id'),
            'notas'                => $this->request->getPost('notas'),
        ];

        $this->model->update($id, $data);

        return redirect()->to('/viviendas')->with('success', 'Vivienda actualizada correctamente.');
    }

    public function eliminar(int $id): \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\ResponseInterface
    {
        $vivienda = $this->model->find($id);

        if ($vivienda === null) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Vivienda no encontrada.']);
            }
            return redirect()->to('/viviendas')->with('error', 'Vivienda no encontrada.');
        }

        $this->model->delete($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Vivienda eliminada correctamente.']);
        }

        return redirect()->to('/viviendas')->with('success', 'Vivienda eliminada correctamente.');
    }

    /**
     * Export to Excel
     */
    public function exportarExcel(): void
    {
        $viviendas = $this->model->getWithTecnico();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Viviendas');

        // Header style
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a3a5c']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Title
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'INCONEL BUILDING - LISTADO DE VIVIENDAS');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Generado el: ' . date('m/d/Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = ['#', 'Dirección', 'Fecha Instalación A/C', 'Serie Handler', 'Serie Condenser', 'Fecha Venta', 'Técnico', 'Notas'];
        $col     = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->applyFromArray($headerStyle);
            $col++;
        }

        // Data
        $row = 4;
        foreach ($viviendas as $i => $v) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $v['direccion']);
            $sheet->setCellValue('C' . $row, $v['fecha_instalacion_ac']);
            $sheet->setCellValue('D' . $row, $v['serie_handler']);
            $sheet->setCellValue('E' . $row, $v['serie_condenser']);
            $sheet->setCellValue('F' . $row, $v['fecha_venta']);
            $sheet->setCellValue('G' . $row, $v['tecnico_nombre_completo']);
            $sheet->setCellValue('H' . $row, $v['notas'] ?? '');
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = 'viviendas_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export to PDF
     */
    public function exportarPdf(): void
    {
        $viviendas = $this->model->getWithTecnico();
        $html      = view('viviendas/pdf', ['viviendas' => $viviendas, 'fecha' => date('m/d/Y H:i:s')], ['saveData' => true]);

        $mpdf = new \Mpdf\Mpdf([
            'mode'        => 'utf-8',
            'format'      => 'A4-L',
            'orientation' => 'L',
            'margin_top'  => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->SetTitle('Listado de Viviendas - Inconel Building');
        $mpdf->SetAuthor('Inconel Building');
        $mpdf->WriteHTML($html);
        $mpdf->Output('viviendas_' . date('Ymd') . '.pdf', 'D');
        exit;
    }

    /**
     * Print view
     */
    public function imprimir(): string
    {
        $viviendas = $this->model->getWithTecnico();

        return view('viviendas/imprimir', [
            'title'     => 'Imprimir Viviendas',
            'viviendas' => $viviendas,
            'fecha'     => date('m/d/Y H:i:s'),
        ]);
    }

    private function buildActions(array $row, string $role): string
    {
        $verUrl    = site_url('viviendas/ver/' . $row['id']);
        $editUrl   = site_url('viviendas/editar/' . $row['id']);
        $deleteUrl = site_url('viviendas/eliminar/' . $row['id']);

        $deleteBtn = '';
        if ($role === 'admin' || $role === 'tecnico') {
            $deleteBtn = <<<HTML
            <button type="button" class="btn btn-danger btn-sm btn-delete"
                    data-id="{$row['id']}" data-url="{$deleteUrl}" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
            HTML;
        }

        return <<<HTML
        <div class="btn-group btn-group-sm" role="group">
            <a href="{$verUrl}" class="btn btn-info btn-sm" title="Ver">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{$editUrl}" class="btn btn-warning btn-sm" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            {$deleteBtn}
        </div>
        HTML;
    }
}
