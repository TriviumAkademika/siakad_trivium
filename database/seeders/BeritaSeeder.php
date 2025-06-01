<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        Berita::create([
            'gambar' => '',
            'judul' => 'PENGUMUMAN RESMI: Pelaksanaan Ujian Akhir Semester Genap Tahun Akademik 2024/2025',
            'isi_berita' => <<<EOT
Sehubungan dengan berakhirnya perkuliahan Semester Genap Tahun Akademik 2024/2025, kami informasikan bahwa pelaksanaan Ujian Akhir Semester (UAS) akan dilaksanakan mulai tanggal 17 Juni hingga 28 Juni 2025. Ujian akan dilaksanakan secara offline di masing-masing ruang kelas sesuai dengan jadwal yang telah ditentukan oleh bagian akademik.

Seluruh mahasiswa diharapkan untuk memperhatikan jadwal ujian yang akan diumumkan melalui Sistem Informasi Akademik (SIAKAD) mulai tanggal 10 Juni 2025. Adapun mahasiswa yang tidak dapat mengikuti ujian karena alasan mendesak wajib mengajukan surat permohonan disertai bukti pendukung paling lambat 14 Juni 2025 melalui email resmi fakultas.

Demi kelancaran pelaksanaan ujian, mahasiswa wajib hadir tepat waktu, membawa kartu ujian, serta mematuhi tata tertib ujian yang berlaku. Ketidakhadiran tanpa keterangan akan dikenakan sanksi akademik sesuai dengan peraturan yang berlaku di lingkungan kampus.

Demikian informasi ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih. Semoga pelaksanaan UAS berjalan lancar dan seluruh mahasiswa dapat memperoleh hasil yang memuaskan.
EOT,
            'tanggal' => now()->toDateString(),
            'penulis' => 'Admin',
        ]);

        Berita::create([
            'gambar' => '',
            'judul' => 'PENDAFTARAN WISUDA PERIODE II TAHUN 2025 RESMI DIBUKA',
            'isi_berita' => <<<EOT
Diberitahukan kepada seluruh mahasiswa yang telah memenuhi syarat akademik bahwa pendaftaran Wisuda Periode II Tahun 2025 dibuka mulai 9 Juni hingga 23 Juni 2025. Pelaksanaan wisuda dijadwalkan pada 27 Juli 2025 bertempat di Auditorium Universitas.

Mahasiswa diwajibkan mendaftar melalui laman akademik dengan melampirkan dokumen pendukung seperti surat keterangan lulus, transkrip nilai sementara, dan bukti pembayaran administrasi.

Informasi lebih lanjut mengenai gladi bersih, ketentuan pakaian, dan teknis pelaksanaan akan diumumkan melalui laman resmi fakultas. Mohon kepada seluruh calon wisudawan untuk terus memantau informasi terbaru dari kampus.
EOT,
            'tanggal' => now()->toDateString(),
            'penulis' => 'Admin',
        ]);

        Berita::create([
            'gambar' => '',
            'judul' => 'PENDAFTARAN PROGRAM KREATIVITAS MAHASISWA (PKM) TAHUN 2025',
            'isi_berita' => <<<EOT
Diberitahukan kepada seluruh mahasiswa aktif bahwa pendaftaran Program Kreativitas Mahasiswa (PKM) Tahun 2025 telah dibuka mulai tanggal 3 Juni hingga 20 Juni 2025. Program ini bertujuan mendorong inovasi dan kreativitas mahasiswa melalui berbagai skema seperti PKM-Penelitian, PKM-Kewirausahaan, PKM-Pengabdian Masyarakat, dan lainnya.

Pendaftaran dilakukan melalui sistem SIMBELMAWA dengan mengunggah proposal dan dokumen sesuai ketentuan. Mahasiswa diminta membaca pedoman PKM 2025 dengan saksama agar tidak terjadi kesalahan administrasi.

Kami mengajak seluruh mahasiswa untuk berpartisipasi aktif dalam program ini sebagai bagian dari pengembangan diri dan kontribusi kepada masyarakat.
EOT,
            'tanggal' => now()->toDateString(),
            'penulis' => 'Admin',
        ]);
    }
}
