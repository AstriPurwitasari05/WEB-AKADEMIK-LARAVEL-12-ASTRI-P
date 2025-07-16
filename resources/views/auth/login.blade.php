<!DOCTYPE html>
<html>
<head>
    <title>Login Siswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-custom {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        img.gambar-anak {
            max-height: 230px;
            margin-right: 30px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="card-custom bg-white d-flex flex-row align-items-center justify-content-center">
                <img src="{{ asset('images/gambarguruanak.png') }}" class="gambar-anak" alt="Gambar Anak">

                <div style="flex: 1">
                    <h3 class="text-center">PPDB 2025</h3>
                    <p class="text-center">Login Siswa - SDN Sawotratap 1</p>

                    @if($errors->any())
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Gagal',
                                text: '{{ $errors->first("message") }}',
                            });
                        </script>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-dark btn-block">Login</button>
                    </form>

                    <div class="mt-3 text-center">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>