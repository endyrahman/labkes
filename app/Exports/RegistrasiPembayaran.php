<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class RegistrasiPembayaran implements FromView, WithEvents, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $tgl_awal = $this->request->tgl_awal;
        $tgl_akhir = $this->request->tgl_akhir;
        $berdasarkan_tgl = $this->request->berdasarkan_tgl;
        $status_bayar = $this->request->status_bayar;

        $laporan = DB::table('view_pemeriksaan as a')
                    ->select('a.id as pemeriksaan_id', 'a.jenis_lab_id', 'a.total_biaya', 'a.no_registrasi', 'b.nama_jenis_lab', 'c.nama_lengkap')
                    ->leftJoin('jenis_lab as b', 'a.jenis_lab_id', 'b.id')
                    ->leftJoin('users as c', 'a.user_id', 'c.id')
                    ->leftJoin('pembayaran as d', 'a.id', 'd.pemeriksaan_id');

                    if ($berdasarkan_tgl == 1) {
                        $laporan->whereBetween(DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y")'), [$tgl_awal, $tgl_akhir]);
                        if ($status_bayar == 2) {
                            $laporan->where('a.status_bayar', 2);
                        }
                    } elseif ($berdasarkan_tgl == 2) {
                        $laporan->whereBetween(DB::raw('DATE_FORMAT(d.tgl_transfer, "%d-%m-%Y")'), [$tgl_awal, $tgl_akhir]);
                    }

        $laporan = $laporan->get();

        return view('pelaporan.registrasipembayaran', [
            'laporan' => $laporan
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $lastRow = $event->sheet->getHighestRow();

                $rangeBorderOutline = 'A5:' . $lastColumn . $lastRow;
                $rangeBorderInner = 'A6:' . $lastColumn . $lastRow;


                $event->sheet->getStyle($rangeBorderInner)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle($rangeBorderOutline)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('A5:G5')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('G6:G'.$lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('B6:B'.$lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('A6:A'.$lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getStyle('A1:G1') ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->mergeCells('A3:G3');
                $event->sheet->getDelegate()
                    ->getStyle('A3:G3')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A1:G1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A5:G5')->getFont()->setBold(true);
            },
        ];
    }
}
