<?php

namespace App\Exports;

use App\Models\Pasien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasienExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pasien::select(
            'nama_pasien',
            'tanggal_janji',
            'email_pasien',
            'no_hp_pasien',
            'alamat_pasien',
            'keluhan_pasien',
            'total_harga_pasien',
            'tindakan_pasien'
        )->get()->map(function ($row) {
            // #36 Fix: Proteksi terhadap CSV/Excel Formula Injection
            return [
                'nama_pasien'        => self::sanitizeFormula($row->nama_pasien),
                'tanggal_janji'      => $row->tanggal_janji,
                'email_pasien'       => self::sanitizeFormula($row->email_pasien),
                'no_hp_pasien'       => self::sanitizeFormula($row->no_hp_pasien),
                'alamat_pasien'      => self::sanitizeFormula($row->alamat_pasien),
                'keluhan_pasien'     => self::sanitizeFormula($row->keluhan_pasien),
                'total_harga_pasien' => self::sanitizeFormula($row->total_harga_pasien),
                'tindakan_pasien'    => self::sanitizeFormula($row->tindakan_pasien),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pasien',
            'Tanggal Janji',
            'Email',
            'No HP',
            'Alamat',
            'Keluhan',
            'Total Biaya',
            'Tindakan',
        ];
    }

    /**
     * Netralkan karakter yang memicu eksekusi formula di Excel/Calc (=, +, -, @).
     */
    private static function sanitizeFormula(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = ltrim($value);
        if ($trimmed !== '' && in_array($trimmed[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'" . $value;
        }

        return $value;
    }
}
