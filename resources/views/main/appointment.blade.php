@extends('main.layout.main')
@section('content')

<!-- ======= Appointment Section ======= -->
<section id="appointment" class="appointment">
  <div class="container" data-aos="fade-up" style="margin-top: 25vh;">

    <div class="section-title">
      <h2>Reservasi Janji Temu (Appointment)</h2>
      <p>Isi data diri Anda pada form di bawah ini. Tim Klinik FAM Dental Care akan mengonfirmasi jadwal Anda melalui WhatsApp.</p>
    </div>

    @if (session('sent-message'))
      <div class="alert alert-success mb-4">
        {{ session('sent-message') }}
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger mb-4">
        {{ session('error') }}
      </div>
    @endif

    <form action="/appointment" method="POST" role="form" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="100" autocomplete="off">
      @csrf
      <div class="row">
        <div class="col-md-6 form-group mt-3">
          <label for="nama_pasien">Nama Lengkap Pasien *</label>
          <input type="text" id="nama_pasien" name="nama_pasien" class="form-control" value="{{ old('nama_pasien') }}" required placeholder="Nama lengkap">
          @error('nama_pasien')
          <div class="form-text bg-warning text-black p-1 rounded">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6 form-group mt-3">
          <label for="email_pasien">Alamat Email Pasien *</label>
          <input type="email" id="email_pasien" class="form-control" name="email_pasien" value="{{ old('email_pasien') }}" required placeholder="email@contoh.com">
          @error('email_pasien')
          <div class="form-text bg-warning text-black p-1 rounded">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 form-group mt-3">
          <label for="no_hp_pasien">No. WhatsApp / Telepon *</label>
          <input type="text" id="no_hp_pasien" class="form-control" name="no_hp_pasien" value="{{ old('no_hp_pasien') }}" required placeholder="081234567890">
          @error('no_hp_pasien')
          <div class="form-text bg-warning text-black p-1 rounded">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-4 form-group mt-3">
          <label for="tanggal_janji">Rencana Tanggal & Jam *</label>
          <input type="datetime-local" id="tanggal_janji" name="tanggal_janji" class="form-control" value="{{ old('tanggal_janji') }}" required>
          @error('tanggal_janji')
          <div class="form-text bg-warning text-black p-1 rounded">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-4 form-group mt-3">
          <label for="dokter_pilihan">Pilih Dokter Spesialis (Opsional)</label>
          <select name="dokter_pilihan" id="dokter_pilihan" class="form-select form-control">
            <option value="">-- Pilih Dokter (Bebas / Jadwal Klinik) --</option>
            @if (isset($dokter))
              @foreach ($dokter as $d)
                <option value="{{ $d->nama_dokter }}" {{ old('dokter_pilihan') == $d->nama_dokter ? 'selected' : '' }}>
                  {{ $d->nama_dokter }} ({{ $d->jadwal_dokter ?? 'Praktik Klinik' }})
                </option>
              @endforeach
            @endif
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 form-group mt-3">
          <label for="alamat_pasien">Alamat Tempat Tinggal *</label>
          <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" rows="4" required placeholder="Alamat lengkap">{{ old('alamat_pasien') }}</textarea>
          @error('alamat_pasien')
          <div class="form-text bg-warning text-black p-1 rounded">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6 form-group mt-3">
          <label for="keluhan_pasien">Keluhan Gigi / Tujuan Perawatan *</label>
          <textarea class="form-control" id="keluhan_pasien" name="keluhan_pasien" rows="4" required placeholder="Jelaskan keluhan sakit gigi atau perawatan yang diinginkan (misal: Tambal, Scaling, Behel)">{{ old('keluhan_pasien') }}</textarea>
          @error('keluhan_pasien')
          <div class="form-text bg-warning text-black p-1 rounded">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row mt-4 mb-5">
        <div class="col-12 text-center">
          <button class="btn btn-primary btn-lg px-5" type="submit">Kirim Form Reservasi</button>
        </div>
      </div>   
    </form>
  </div>
</section><!-- End Appointment Section -->
@endsection