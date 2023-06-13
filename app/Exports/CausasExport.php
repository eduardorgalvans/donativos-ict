<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;


use App\Model\ContaPagosCatConceptos;

class CausasExport implements
    FromCollection,
    //  WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
{
    use Exportable;

    protected $year;

    public function __construct($oRegistros)
    {
        $this->oRegistros = $oRegistros;
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

                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
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
            '#', 'Causa', 'Mínimo', 'Máximo', 'Activo',
        ];
    }

    /**
     * @return array
     */
    public function map($oRow): array
    {
        return [
            $oRow->id,
            $oRow->id_Padre,
            $oRow->Nombre,
            $oRow->Permiso,
            $oRow->Orden,
            ($oRow->Tipo == 1) ? 'Carpeta' : 'Modulo',
            $oRow->Estatus,
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
}
