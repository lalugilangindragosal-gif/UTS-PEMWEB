<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTS Pem Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-plus-circle {
            width: 64px;
            height: 64px;
            font-size: 32px;
            line-height: 1;
            background: radial-gradient(circle at 30% 30%, #98d8a6, #4ca65e);
            border: none;
            color: #ffffff;
        }

        .btn-plus-circle:hover {
            opacity: 0.9;
            color: #ffffff;
        }

        .card-foto {
            width: 100%;
            height: 270px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-white">

    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-semibold" href="{{ route('anggota.index') }}">UTS Pem Web</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('anggota.index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><span class="nav-link disabled">Lalu Gilang Indragosal</span></li>
                    <li class="nav-item"><span class="nav-link disabled">24010110251</span></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @if (session('pesan'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('pesan') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100 shadow-sm" style="min-height: 430px;">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <button type="button"
                            class="btn rounded-circle d-flex justify-content-center align-items-center shadow-sm btn-plus-circle"
                            data-bs-toggle="modal" data-bs-target="#modalTambah">+</button>
                    </div>
                </div>
            </div>

            @foreach ($anggotas as $row)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column p-3">
                            <img src="{{ asset('uploads/' . $row->foto) }}" class="card-foto mb-3" alt="Foto">
                            <h5 class="card-title fw-bold mb-1">{{ $row->nama }}</h5>
                            <p class="card-text text-muted small mb-4">{{ $row->deskripsi }}</p>
                            <div class="mt-auto d-flex gap-1">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $row->id }}">Edit</button>
                                <form action="{{ route('anggota.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus biodata ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalEdit{{ $row->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('anggota.update', $row->id) }}" method="POST"
                            enctype="multipart/form-data" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Biodata</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ $row->nama }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">NIM</label>
                                    <input type="text" name="nim" class="form-control"
                                        value="{{ $row->nim }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Peran</label>
                                    <select name="peran" class="form-select" required>
                                        <option value="master" {{ $row->peran == 'master' ? 'selected' : '' }}>Master
                                            (Front-End)</option>
                                        <option value="pegawai" {{ $row->peran == 'pegawai' ? 'selected' : '' }}>
                                            Pegawai (Back-End)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ganti Foto (Opsional)</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Biodata Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select name="peran" class="form-select" required>
                            <option value="master">Master (Front-End)</option>
                            <option value="pegawai">Pegawai (Back-End)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container mt-5">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Features</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Pricing</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">About</a></li>
            </ul>
            <p class="text-center text-body-secondary small">
                © 2025 Dibuat Sejujurjurnya Oleh Team Lalu Gilang Indragosal dan Mulya khadman Tanpa Mencotek dari Tim
                Lain
            </p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
