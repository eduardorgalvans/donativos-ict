<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


use App\Model\ContaPagosCatConceptos;

class DonacionesExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents,
    WithCustomStartCell
{
    use Exportable;

    protected $year;

    public function __construct($oRegistros, $donacion, $donacionPorComunidades)
    {
        $this->oRegistros = $oRegistros;
        $this->donacion = $donacion;
        $this->donacionPorComunidades = $donacionPorComunidades;
        $this->inicioRow = count($donacionPorComunidades);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('ICT');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->setCellValue('B2',  'Causa: ' . $this->donacion->n_causa);
                $event->sheet->setCellValue('C2',  'Total recudado: ' . $this->donacion->total);
                $event->sheet->setCellValue('D2',  'Total donaciones: ' . $this->donacion->donaciones);


                $event->sheet->setCellValue('B5',  'Comunidad');
                $event->sheet->setCellValue('C5',  'Donaciones');
                $event->sheet->setCellValue('D5',  'Numero de donaciones');



                foreach ($this->donacionPorComunidades as $key => $comunidad) {
                    $event->sheet->setCellValue('B' . 5 + $key + 1,  $comunidad->n_comunidad);
                    $event->sheet->setCellValue('C' . 5 + $key + 1,  $comunidad->total);
                    $event->sheet->setCellValue('D' . 5 + $key + 1,  $comunidad->donaciones);
                }









                $event->sheet->getDelegate()->getStyle('A' . 7 + $this->inicioRow  . ':O' . 7 + $this->inicioRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => ['argb' => 'FFA0A0A0',],
                        'endColor' => ['argb' => 'FFFFFFFF',],
                    ],
                ]);




                $event->sheet->getDelegate()->getStyle('B5:D5')->applyFromArray([
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => true,
                    ],
                ]);


                $aColumnas = ['A' => 10, 'B' => 15, 'C' => 50, 'D' => 10, 'E' => 10, 'F' => 20, 'G' => 15, 'H' => 10,];
                foreach ($aColumnas as $key => $value) {
                    $event->sheet->getDelegate()->getColumnDimension($key)->setWidth($value);
                }
            },
        ];
    }
    public function collection()
    {
        // obtenemos los registros
        return $this->oRegistros;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'Causa', 'Referencia', 'Fecha', 'Donador', 'Importe', 'Email', 'Teléfono', 'Comunidad', 'Deducible', 'Tipo de Persona', 'RFC', 'Razon Social', 'Regimen fiscal', 'CP'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            //'B' => '@',
        ];
    }

    public function startCell(): string
    {
        return 'A' . 7 + $this->inicioRow;
    }
}
