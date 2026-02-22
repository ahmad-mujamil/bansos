<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Penduduk;
use Illuminate\Database\Seeder;

class PendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatan = Kecamatan::query()->where('nama', 'Gerung')->first();
        $desa = Desa::query()->where('kecamatan_id', $kecamatan?->id)->first();

        $data = [
            ['nik' => '5201010101850001', 'no_kk' => '5201010101850001', 'nama' => 'Ahmad Wijaya', 'alamat' => 'Jl. Merdeka No. 10', 'tempat_lahir' => 'Mataram', 'tgl_lahir' => '1985-01-15', 'jk' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'Kawin', 'pekerjaan' => 'Wiraswasta', 'pendidikan' => 'SMA', 'rt_rw' => '01/01', 'level_desil' => 5],
            ['nik' => '5201010202860002', 'no_kk' => '5201010202860002', 'nama' => 'Siti Aminah', 'alamat' => 'Jl. Merdeka No. 10', 'tempat_lahir' => 'Gerung', 'tgl_lahir' => '1986-02-20', 'jk' => 'P', 'agama' => 'Islam', 'status_perkawinan' => 'Kawin', 'pekerjaan' => 'Ibu Rumah Tangga', 'pendidikan' => 'SMP', 'rt_rw' => '01/01', 'level_desil' => 5],
            ['nik' => '5201010303900003', 'no_kk' => '5201010303900003', 'nama' => 'Budi Santoso', 'alamat' => 'Jl. Garuda No. 5', 'tempat_lahir' => 'Kediri', 'tgl_lahir' => '1990-03-10', 'jk' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'Belum Kawin', 'pekerjaan' => 'Petani', 'pendidikan' => 'SD', 'rt_rw' => '02/01', 'level_desil' => 3],
            ['nik' => '5201010404910004', 'no_kk' => '5201010404910004', 'nama' => 'Dewi Lestari', 'alamat' => 'Jl. Sudirman No. 15', 'tempat_lahir' => 'Narmada', 'tgl_lahir' => '1991-04-25', 'jk' => 'P', 'agama' => 'Islam', 'status_perkawinan' => 'Kawin', 'pekerjaan' => 'Guru', 'pendidikan' => 'S1', 'rt_rw' => '01/02', 'level_desil' => 7],
            ['nik' => '5201010505920005', 'no_kk' => '5201010505920005', 'nama' => 'Rudi Hartono', 'alamat' => 'Jl. Sudirman No. 15', 'tempat_lahir' => 'Mataram', 'tgl_lahir' => '1992-05-08', 'jk' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'Kawin', 'pekerjaan' => 'PNS', 'pendidikan' => 'S1', 'rt_rw' => '01/02', 'level_desil' => 7],
            ['nik' => '5201010606930006', 'no_kk' => '5201010606930006', 'nama' => 'Maria Ulfa', 'alamat' => 'Jl. Pahlawan No. 22', 'tempat_lahir' => 'Gerung', 'tgl_lahir' => '1993-06-12', 'jk' => 'P', 'agama' => 'Kristen', 'status_perkawinan' => 'Belum Kawin', 'pekerjaan' => 'Karyawan Swasta', 'pendidikan' => 'D3', 'rt_rw' => '03/02', 'level_desil' => 6],
            ['nik' => '5201010707940007', 'no_kk' => '5201010707940007', 'nama' => 'Eko Prasetyo', 'alamat' => 'Jl. Kartini No. 7', 'tempat_lahir' => 'Lembar', 'tgl_lahir' => '1994-07-30', 'jk' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'Kawin', 'pekerjaan' => 'Nelayan', 'pendidikan' => 'SMP', 'rt_rw' => '02/02', 'level_desil' => 4],
            ['nik' => '5201010808950008', 'no_kk' => '5201010808950008', 'nama' => 'Rina Wulandari', 'alamat' => 'Jl. Kartini No. 7', 'tempat_lahir' => 'Sekotong', 'tgl_lahir' => '1995-08-18', 'jk' => 'P', 'agama' => 'Islam', 'status_perkawinan' => 'Kawin', 'pekerjaan' => 'Ibu Rumah Tangga', 'pendidikan' => 'SMA', 'rt_rw' => '02/02', 'level_desil' => 4],
            ['nik' => '5201010909960009', 'no_kk' => '5201010909960009', 'nama' => 'Fajar Nugroho', 'alamat' => 'Jl. Diponegoro No. 33', 'tempat_lahir' => 'Kediri', 'tgl_lahir' => '1996-09-05', 'jk' => 'L', 'agama' => 'Islam', 'status_perkawinan' => 'Belum Kawin', 'pekerjaan' => 'Mahasiswa', 'pendidikan' => 'S1', 'rt_rw' => '04/03', 'level_desil' => 2],
            ['nik' => '5201011010970010', 'no_kk' => '5201011010970010', 'nama' => 'Ani Rahayu', 'alamat' => 'Jl. Imam Bonjol No. 12', 'tempat_lahir' => 'Narmada', 'tgl_lahir' => '1997-10-22', 'jk' => 'P', 'agama' => 'Islam', 'status_perkawinan' => 'Belum Kawin', 'pekerjaan' => 'Pelajar', 'pendidikan' => 'SMA', 'rt_rw' => '01/03', 'level_desil' => 3],
        ];

        foreach ($data as $item) {
            Penduduk::updateOrCreate(
                ['nik' => $item['nik']],
                array_merge($item, [
                    'desa_id' => $desa?->id,
                    'kecamatan_id' => $kecamatan?->id,
                ])
            );
        }
    }
}
