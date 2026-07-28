<?php

namespace Database\Seeders;

use App\Models\StandarMutu;
use Illuminate\Database\Seeder;

class StandarMutuSeeder extends Seeder
{
    /**
     * The 24 SN-Dikti standards (Permendikbud No. 3 Tahun 2020).
     */
    public function run(): void
    {
        $standars = [
            // ── Standar Pendidikan (1–9) ──────────────────────────────────────
            ['kode_standar' => 'SN-01', 'nama_standar' => 'Standar Kompetensi Lulusan',
             'deskripsi'    => 'Standar yang mengatur kriteria kualifikasi kemampuan lulusan yang mencakup sikap, pengetahuan, dan keterampilan.',
             'indikator_kinerja' => 'Persentase lulusan yang memenuhi profil kompetensi yang ditetapkan.',
             'target_capaian'    => '≥ 80%'],

            ['kode_standar' => 'SN-02', 'nama_standar' => 'Standar Isi Pembelajaran',
             'deskripsi'    => 'Standar yang mengatur keluasan dan kedalaman materi pembelajaran.',
             'indikator_kinerja' => 'Kesesuaian kurikulum dengan capaian pembelajaran yang ditetapkan.',
             'target_capaian'    => '100%'],

            ['kode_standar' => 'SN-03', 'nama_standar' => 'Standar Proses Pembelajaran',
             'deskripsi'    => 'Standar yang mengatur perencanaan, pelaksanaan, dan penilaian pembelajaran.',
             'indikator_kinerja' => 'Persentase kehadiran dosen dalam melaksanakan perkuliahan.',
             'target_capaian'    => '≥ 90%'],

            ['kode_standar' => 'SN-04', 'nama_standar' => 'Standar Penilaian Pembelajaran',
             'deskripsi'    => 'Standar yang mengatur mekanisme, prosedur, dan instrumen penilaian hasil belajar mahasiswa.',
             'indikator_kinerja' => 'Ketepatan waktu penginputan nilai oleh dosen.',
             'target_capaian'    => '≥ 95%'],

            ['kode_standar' => 'SN-05', 'nama_standar' => 'Standar Dosen dan Tenaga Kependidikan',
             'deskripsi'    => 'Standar yang mengatur kualifikasi akademik, kompetensi, dan rasio dosen.',
             'indikator_kinerja' => 'Rasio dosen terhadap mahasiswa.',
             'target_capaian'    => '1:20'],

            ['kode_standar' => 'SN-06', 'nama_standar' => 'Standar Sarana dan Prasarana Pembelajaran',
             'deskripsi'    => 'Standar yang mengatur ketersediaan dan kualitas sarana dan prasarana pembelajaran.',
             'indikator_kinerja' => 'Persentase ruang kelas yang memenuhi standar kelayakan.',
             'target_capaian'    => '100%'],

            ['kode_standar' => 'SN-07', 'nama_standar' => 'Standar Pengelolaan Pembelajaran',
             'deskripsi'    => 'Standar yang mengatur perencanaan, pelaksanaan, dan evaluasi pengelolaan program studi.',
             'indikator_kinerja' => 'Ketersediaan dokumen rencana pengembangan prodi yang terbarui.',
             'target_capaian'    => 'Ada dan diperbarui tahunan'],

            ['kode_standar' => 'SN-08', 'nama_standar' => 'Standar Pembiayaan Pembelajaran',
             'deskripsi'    => 'Standar yang mengatur biaya investasi dan biaya operasional pembelajaran.',
             'indikator_kinerja' => 'Kecukupan pembiayaan per mahasiswa per tahun.',
             'target_capaian'    => 'Sesuai standar BAN-PT'],

            ['kode_standar' => 'SN-09', 'nama_standar' => 'Standar Penelitian – Hasil',
             'deskripsi'    => 'Standar yang mengatur mutu hasil penelitian di perguruan tinggi.',
             'indikator_kinerja' => 'Jumlah publikasi ilmiah per dosen per tahun.',
             'target_capaian'    => '≥ 1 publikasi/dosen/tahun'],

            // ── Standar Penelitian (10–12) ────────────────────────────────────
            ['kode_standar' => 'SN-10', 'nama_standar' => 'Standar Penelitian – Isi',
             'deskripsi'    => 'Standar yang mengatur kedalaman dan keluasan materi penelitian.',
             'indikator_kinerja' => 'Kesesuaian topik penelitian dengan roadmap penelitian institusi.',
             'target_capaian'    => '≥ 80%'],

            ['kode_standar' => 'SN-11', 'nama_standar' => 'Standar Penelitian – Proses',
             'deskripsi'    => 'Standar yang mengatur kegiatan perencanaan, pelaksanaan, dan pelaporan penelitian.',
             'indikator_kinerja' => 'Persentase proposal penelitian yang terealisasi.',
             'target_capaian'    => '≥ 75%'],

            ['kode_standar' => 'SN-12', 'nama_standar' => 'Standar Penelitian – Penilaian',
             'deskripsi'    => 'Standar yang mengatur mekanisme penilaian/review penelitian.',
             'indikator_kinerja' => 'Ketersediaan SOP review proposal penelitian.',
             'target_capaian'    => 'Tersedia dan diterapkan'],

            // ── Standar Penelitian – Lanjutan (13–15) ─────────────────────────
            ['kode_standar' => 'SN-13', 'nama_standar' => 'Standar Penelitian – Peneliti',
             'deskripsi'    => 'Standar kualifikasi dan kompetensi peneliti.',
             'indikator_kinerja' => 'Persentase dosen yang aktif melakukan penelitian.',
             'target_capaian'    => '≥ 70%'],

            ['kode_standar' => 'SN-14', 'nama_standar' => 'Standar Penelitian – Sarana dan Prasarana',
             'deskripsi'    => 'Standar ketersediaan fasilitas laboratorium dan infrastruktur penelitian.',
             'indikator_kinerja' => 'Kecukupan fasilitas penelitian sesuai bidang ilmu.',
             'target_capaian'    => 'Memadai'],

            ['kode_standar' => 'SN-15', 'nama_standar' => 'Standar Penelitian – Pengelolaan',
             'deskripsi'    => 'Standar yang mengatur kelembagaan dan pengelolaan penelitian (LPPM).',
             'indikator_kinerja' => 'Ketersediaan roadmap penelitian yang diperbarui.',
             'target_capaian'    => 'Diperbarui setiap 4 tahun'],

            // ── Standar Pengabdian Kepada Masyarakat (16–24) ──────────────────
            ['kode_standar' => 'SN-16', 'nama_standar' => 'Standar Pengabdian Masyarakat – Hasil',
             'deskripsi'    => 'Standar mutu hasil pengabdian kepada masyarakat.',
             'indikator_kinerja' => 'Jumlah kegiatan PkM per dosen per tahun.',
             'target_capaian'    => '≥ 1 kegiatan/dosen/tahun'],

            ['kode_standar' => 'SN-17', 'nama_standar' => 'Standar Pengabdian Masyarakat – Isi',
             'deskripsi'    => 'Standar keluasan dan kedalaman materi PkM.',
             'indikator_kinerja' => 'Relevansi kegiatan PkM dengan kebutuhan masyarakat.',
             'target_capaian'    => '≥ 80% sesuai kebutuhan'],

            ['kode_standar' => 'SN-18', 'nama_standar' => 'Standar Pengabdian Masyarakat – Proses',
             'deskripsi'    => 'Standar perencanaan, pelaksanaan, dan pelaporan PkM.',
             'indikator_kinerja' => 'Persentase laporan PkM yang diselesaikan tepat waktu.',
             'target_capaian'    => '≥ 90%'],

            ['kode_standar' => 'SN-19', 'nama_standar' => 'Standar Pengabdian Masyarakat – Penilaian',
             'deskripsi'    => 'Standar mekanisme penilaian kegiatan PkM.',
             'indikator_kinerja' => 'Ketersediaan instrumen evaluasi kegiatan PkM.',
             'target_capaian'    => 'Tersedia'],

            ['kode_standar' => 'SN-20', 'nama_standar' => 'Standar Pengabdian Masyarakat – Pelaksana',
             'deskripsi'    => 'Standar kualifikasi dan kompetensi pelaksana PkM.',
             'indikator_kinerja' => 'Persentase dosen dengan kompetensi PkM yang relevan.',
             'target_capaian'    => '≥ 80%'],

            ['kode_standar' => 'SN-21', 'nama_standar' => 'Standar Pengabdian Masyarakat – Sarana dan Prasarana',
             'deskripsi'    => 'Standar ketersediaan fasilitas untuk kegiatan PkM.',
             'indikator_kinerja' => 'Kecukupan dukungan fasilitas untuk PkM.',
             'target_capaian'    => 'Memadai'],

            ['kode_standar' => 'SN-22', 'nama_standar' => 'Standar Pengabdian Masyarakat – Pengelolaan',
             'deskripsi'    => 'Standar kelembagaan dan pengelolaan PkM.',
             'indikator_kinerja' => 'Ketersediaan rencana strategis PkM.',
             'target_capaian'    => 'Tersedia dan diperbarui'],

            ['kode_standar' => 'SN-23', 'nama_standar' => 'Standar Pengabdian Masyarakat – Pendanaan dan Pembiayaan',
             'deskripsi'    => 'Standar mekanisme pendanaan dan pertanggungjawaban biaya PkM.',
             'indikator_kinerja' => 'Rasio dana PkM terhadap total anggaran PT.',
             'target_capaian'    => '≥ 5%'],

            ['kode_standar' => 'SN-24', 'nama_standar' => 'Standar Pendanaan dan Pembiayaan Penelitian',
             'deskripsi'    => 'Standar mekanisme pendanaan dan pertanggungjawaban biaya penelitian.',
             'indikator_kinerja' => 'Rasio dana penelitian terhadap total anggaran PT.',
             'target_capaian'    => '≥ 5%'],
        ];

        foreach ($standars as $standar) {
            StandarMutu::firstOrCreate(
                ['kode_standar' => $standar['kode_standar']],
                $standar
            );
        }

        $this->command->info('24 SN-Dikti standards seeded successfully.');
    }
}
