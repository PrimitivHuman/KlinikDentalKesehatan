<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KlinikDataSeeder extends Seeder
{
    /**
     * Seed initial klinik data (tentang, kategori, galeri, dokter).
     */
    public function run(): void
    {
        // ── Tentang ─────────────────────────────────────────────────────────
        if (DB::table('tentang')->count() === 0) {
            DB::table('tentang')->insert([
                'id_tentang'     => 'TG-001',
                'informasi_umum' => '<div style="font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;"><span style="background-color: rgb(255, 255, 255);">Family Dental Care Group didirikan pada tanggal 3 September 2019. Berlokasi di wilayah Bandung dan mempunyai 5 cabang, Family Dental Care memiliki beberapa layanan perawatan gigi, diantaranya Scaling, Pemasangan Kawat Gigi, Bleaching, dan lain-lain.</span></div>',
                'foto_sampul'    => 'about.png',
                'visi'           => '<div style="font-family: Consolas, &quot;Courier New&quot;, monospace; font-size: 14px; line-height: 19px; white-space: pre;"><span style="background-color: rgb(255, 255, 255);">Menjadi pusat layanan kesehatan gigi keluarga yang nyaman, ramah, profesional.</span></div><p><br></p>',
                'misi'           => '<ul><li>Memberikan pelayanan kesehatan gigi yang berkualitas dan profesional.</li><li>Fleksibel dengan perkembangan dan terus berbenah diri untuk memenuhi kebutuhan pasien.</li><li>Menciptakan suasana dan lingkungan yang nyaman dan juga ramah bagi keluarga.</li><li>Memberikan edukasi kepada pasien dan keluarga dalam menjaga kesehatan mulut.</li></ul>',
                'tupoksi'        => 'Pemberian layanan kesehatan gigi paripurna untuk seluruh keluarga.',
            ]);
        }

        // ── Kategori ────────────────────────────────────────────────────────
        if (DB::table('kategori')->count() === 0) {
            DB::table('kategori')->insert([
                ['id_kategori' => 'KT-001', 'nama_kategori' => 'Estetika Gigi'],
                ['id_kategori' => 'KT-002', 'nama_kategori' => 'Perawatan Medis'],
            ]);
        }

        // ── Galeri ──────────────────────────────────────────────────────────
        if (DB::table('galeri')->count() === 0) {
            DB::table('galeri')->insert([
                [
                    'id_galeri'   => 'GL-005',
                    'id_kategori' => 'KT-001',
                    'judul'       => 'Tindakan Bleaching',
                    'deskripsi'   => '<b>Bleaching gigi</b> atau pemutihan gigi merupakan prosedur estetika yang digunakan untuk membuat permukaan gigi tampak lebih putih.',
                    'images'      => '1673006494-GL-005.jpg',
                ],
                [
                    'id_galeri'   => 'GL-006',
                    'id_kategori' => 'KT-001',
                    'judul'       => 'Pemasangan Kawat Gigi',
                    'deskripsi'   => '<p>Kawat gigi atau behel adalah salah satu alat yang digunakan untuk mendapatkan susunan gigi yang ideal. Kawat gigi bekerja dengan cara memberikan tekanan ke gigi untuk secara perlahan menggerakkan gigi ke posisi idealnya.</p>',
                    'images'      => '1673006541-GL-006.jpg',
                ],
                [
                    'id_galeri'   => 'GL-007',
                    'id_kategori' => 'KT-002',
                    'judul'       => 'Penambalan Gigi Depan',
                    'deskripsi'   => '<p>Tambal gigi adalah perawatan yang dilakukan untuk memperbaiki gigi rusak atau gigi berlubang. Tambal gigi dilakukan dengan prosedur memasukkan bahan tambalan ke bagian gigi yang rusak atau berlubang.</p>',
                    'images'      => '1673006639-GL-007.jpg',
                ],
                [
                    'id_galeri'   => 'GL-008',
                    'id_kategori' => 'KT-001',
                    'judul'       => 'Tindakan Diastema Enclosure',
                    'deskripsi'   => '<p>Perawatan penutupan celah antara dua gigi depan (diastema) untuk senyum yang lebih proporsional.</p>',
                    'images'      => '1673008703-GL-008.jpg',
                ],
            ]);
        }

        // ── Dokter ──────────────────────────────────────────────────────────
        if (DB::table('dokter')->count() === 0) {
            DB::table('dokter')->insert([
                [
                    'id_dokter'     => 'DOK-001',
                    'nama_dokter'   => 'DRG. Martiyanti Doanna',
                    'no_hp_dokter'  => '08123456701',
                    'images'        => '1673007258-DOK-001.jpg',
                    'email_dokter'  => 'martiyanti@famdentalcare.com',
                    'jadwal_dokter' => 'Selasa jam 10:00 – 17:00',
                    'str_dokter'    => '31.2.2.100.1.20.123456',
                    'sip_dokter'    => '0013/IPFK-DG/VII/2021/DPMPTSP',
                ],
                [
                    'id_dokter'     => 'DOK-002',
                    'nama_dokter'   => 'DRG. Amalia Meisyafitri',
                    'no_hp_dokter'  => '08123456702',
                    'images'        => '1673007237-DOK-002.jpg',
                    'email_dokter'  => 'amalia@famdentalcare.com',
                    'jadwal_dokter' => 'Senin, Selasa, Kamis jam 10:00 – 15:00, Jumat jam 15:00 – 20:00',
                    'str_dokter'    => '31.2.2.100.1.20.123457',
                    'sip_dokter'    => '0013/IPFK-DG/VII/2021/DPMPTSP',
                ],
                [
                    'id_dokter'     => 'DOK-003',
                    'nama_dokter'   => 'DRG. Munawar Chalid',
                    'no_hp_dokter'  => '08123456703',
                    'images'        => '1673007328-DOK-003.jpg',
                    'email_dokter'  => 'munawar@famdentalcare.com',
                    'jadwal_dokter' => 'Selasa, Kamis, Sabtu jam 15:00 – 20:00',
                    'str_dokter'    => '31.2.2.100.1.20.123458',
                    'sip_dokter'    => '0014/IPFK-DG/III/2022/DPMPTSP',
                ],
                [
                    'id_dokter'     => 'DOK-004',
                    'nama_dokter'   => 'DRG. Fathridi Thoriq',
                    'no_hp_dokter'  => '08123456704',
                    'images'        => '1673007423-DOK-004.jpg',
                    'email_dokter'  => 'fathridi@famdentalcare.com',
                    'jadwal_dokter' => 'Senin, Rabu jam 13:00 – 19:00',
                    'str_dokter'    => '31.2.2.100.1.20.123459',
                    'sip_dokter'    => '503/0346-SIP-DR/DPMPTSP/XI/2020',
                ],
                [
                    'id_dokter'     => 'DOK-005',
                    'nama_dokter'   => 'DRG. Geta Widhi',
                    'no_hp_dokter'  => '08123456705',
                    'images'        => '1673007494-DOK-005.jpg',
                    'email_dokter'  => 'geta@famdentalcare.com',
                    'jadwal_dokter' => 'Rabu jam 10:00 – 20:00, Jumat jam 10:00 – 15:00',
                    'str_dokter'    => '31.2.2.100.1.20.123460',
                    'sip_dokter'    => '503/0347-SIP-DR/DPMPTSP/XII/2020',
                ],
                [
                    'id_dokter'     => 'DOK-006',
                    'nama_dokter'   => 'DRG. Deborah Cerfina',
                    'no_hp_dokter'  => '08123456706',
                    'images'        => '1673007539-DOK-006.jpg',
                    'email_dokter'  => 'deborah@famdentalcare.com',
                    'jadwal_dokter' => 'Kamis, Jumat jam 10:00 – 16:00',
                    'str_dokter'    => '31.2.2.100.1.20.123461',
                    'sip_dokter'    => '503/0348-SIP-DR/DPMPTSP/I/2021',
                ],
                [
                    'id_dokter'     => 'DOK-007',
                    'nama_dokter'   => 'DRG. Jane Firsty',
                    'no_hp_dokter'  => '08123456707',
                    'images'        => '1673007608-DOK-007.jpg',
                    'email_dokter'  => 'jane@famdentalcare.com',
                    'jadwal_dokter' => 'Senin, Rabu, Sabtu jam 10:00 – 15:00',
                    'str_dokter'    => '31.2.2.100.1.20.123462',
                    'sip_dokter'    => '503/0349-SIP-DR/DPMPTSP/II/2021',
                ],
                [
                    'id_dokter'     => 'DOK-008',
                    'nama_dokter'   => 'DRG. Stacia Ariella',
                    'no_hp_dokter'  => '08123456708',
                    'images'        => '1673007824-DOK-008.jpg',
                    'email_dokter'  => 'stacia@famdentalcare.com',
                    'jadwal_dokter' => 'Senin jam 15:00 – 20:00, Sabtu jam 10:00 – 15:00',
                    'str_dokter'    => '31.2.2.100.1.20.123463',
                    'sip_dokter'    => '503/0350-SIP-DR/DPMPTSP/III/2021',
                ],
            ]);
        }

        // ── Berita / Artikel ────────────────────────────────────────────────
        if (DB::table('beritas')->count() === 0) {
            DB::table('beritas')->insert([
                [
                    'id_berita'   => 'BRT-001',
                    'judul'       => 'Pentingnya Perawatan Scaling Gigi Secara Berkala Setiap 6 Bulan',
                    'slug'        => 'pentingnya-perawatan-scaling-gigi-secara-berkala-setiap-6-bulan',
                    'isi'         => '<p>Scaling gigi adalah prosedur pembersihan karang gigi yang menumpuk pada permukaan gigi hingga di bawah garis gusi. Karang gigi atau kalkulus tidak dapat dibersihkan hanya dengan menyikat gigi biasa.</p><p>Dokter gigi merekomendasikan pembersihan karang gigi minimal setiap 6 bulan sekali guna mencegah radang gusi (gingivitis), bau mulut tak sedap, serta kerusakan tulang penyangga gigi.</p>',
                    'images'      => '1791204021-BRT-001.jpg',
                    'penulis'     => 'DRG. Martiyanti Doanna',
                    'status'      => 'published',
                    'tgl_terbit'  => '2026-10-01',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'id_berita'   => 'BRT-002',
                    'judul'       => 'Mengenal Perbedaan Tambal Gigi Komposit dan Perawatan Saluran Akar',
                    'slug'        => 'mengenal-perbedaan-tambal-gigi-komposit-dan-perawatan-saluran-akar',
                    'isi'         => '<p>Banyak pasien bertanya kapan gigi hanya perlu ditambal biasa dan kapan memerlukan perawatan saluran akar (PSA). Jika lubang masih di lapisan email atau dentin, tambalan komposit estetis sewarna gigi sudah cukup.</p><p>Namun, jika infeksi telah mencapai ruang pulpa saraf gigi, perawatan saluran akar mutlak dilakukan sebelum gigi ditambal permanen atau diberi mahkota jaket (crown).</p>',
                    'images'      => null,
                    'penulis'     => 'DRG. Munawar Chalid',
                    'status'      => 'published',
                    'tgl_terbit'  => '2026-10-03',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'id_berita'   => 'BRT-003',
                    'judul'       => 'Panduan Menjaga Kebersihan Gigi Saat Menggunakan Kawat Gigi (Behel)',
                    'slug'        => 'panduan-menjaga-kebersihan-gigi-saat-menggunakan-kawat-gigi-behel',
                    'isi'         => '<p>Menggunakan kawat gigi ortodonti membutuhkan komitmen kebersihan ekstra. Sisa makanan sangat mudah terselip di sela bracket dan kawat archwire.</p><p>Gunakan sikat gigi khusus ortodonti, sikat interdental (interdental brush), serta dental floss secara rutin agar tidak timbul bercak putih (white spot) atau radang gusi selama masa perawatan.</p>',
                    'images'      => null,
                    'penulis'     => 'DRG. Amalia Meisyafitri',
                    'status'      => 'published',
                    'tgl_terbit'  => '2026-10-05',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }

        // ── Kegiatan / Agenda ───────────────────────────────────────────────
        if (DB::table('kegiatans')->count() === 0) {
            DB::table('kegiatans')->insert([
                [
                    'id_kegiatan'        => 'KGT-001',
                    'judul_kegiatan'     => 'Penyuluhan & Pemeriksaan Kesehatan Gigi Anak Sekolah Dasar',
                    'deskripsi_kegiatan' => '<p>Tim dokter gigi Klinik FAM Dental Care mengadakan kegiatan edukasi cara menyikat gigi yang baik dan benar serta pemeriksaan gigi gratis bagi 150 siswa sekolah dasar.</p>',
                    'tgl_kegiatan'       => '2026-10-15',
                    'images'             => '1791204059-KGT-001.png',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ],
                [
                    'id_kegiatan'        => 'KGT-002',
                    'judul_kegiatan'     => 'Bakti Sosial Senyum Sehat Bersama Komunitas Lansia',
                    'deskripsi_kegiatan' => '<p>Pelayanan konsultasi kesehatan gigi mulut, pembersihan karang gigi gratis, dan penyuluhan perawatan gigi tiruan bagi komunitas lansia wilayah Bandung.</p>',
                    'tgl_kegiatan'       => '2026-10-22',
                    'images'             => '1789790067-KGT-001.jpg',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ],
            ]);
        }

        $this->command->info('✅ Data klinik dasar (Tentang, Kategori, Galeri, Dokter, Berita, Kegiatan) berhasil disiapkan.');
    }
}
