<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Menghitung pertemuan yang sudah diambil absensinya, per bulan.
 * Pertemuan dihitung dari jadwal yang sudah punya sesi absensi dengan minimal satu catatan kehadiran.
 */
class PertemuanService
{
    /**
     * @return array<int, int> jumlah pertemuan untuk bulan 1 sampai 12 pada tahun tertentu
     */
    public static function perBulan(Collection $ekstrakurikulerIds, int $tahun): array
    {
        $bulan = array_fill(1, 12, 0);

        if ($ekstrakurikulerIds->isEmpty()) {
            return $bulan;
        }

        $tanggal = Schedule::query()
            ->whereIn('extracurricular_id', $ekstrakurikulerIds)
            ->whereYear('activity_date', $tahun)
            ->whereHas('attendanceSession.attendances')
            ->pluck('activity_date');

        foreach ($tanggal as $tgl) {
            $bulan[Carbon::parse($tgl)->month]++;
        }

        return $bulan;
    }
}
