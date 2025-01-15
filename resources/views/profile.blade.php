<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <!-- Tambahkan CSS Bootstrap atau lainnya di sini -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk icon (opsional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{
            background-color: rgb(151, 149, 100);
        }
        .navbar.navbar-dark {
            background-color: rgb(110, 108, 71);
            color: white;

        }

        .btn {
            background-color: rgb(61, 58, 34);
        }

        .image {
        background-size: cover; /* Ukuran gambar penuh */
        background-position: center; /* Posisi tengah */
        height: 100vh; /* Tinggi penuh viewport */
    }


       
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top">
        <div class="container">
            <a href="#" class="navbar-brand mb-0 h1 d-flex align-items-center">
                <img src="{{ Vite::asset('resources/images/llogo.png') }}" alt="Gambar" width="55" height="70" class="me-2">
                <h3 class="mb-0">My Profile</h3>
            </a>
        </div>
    </nav>
    
    <div class="container-sm mt-5">
        
        <div class="row justify-content-center">
            <div class="p-5 bg-light rounded-3 col-xl-4 border text-center">
                <div class="mb-3">
                    <h3>Profile</h3>
                </div>
                <hr>
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-3">
                        <label for="profile" class="form-label">Nama Pengguna</label>
                        <h5>{{ Auth::user()->name }}</h5>
                    </div>
                </div>
                <hr> 
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    


    <!-- Tambahkan script Bootstrap JS di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
