<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Digital Perpustakaan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="{{ asset('assets/img/logoBuku.png') }}" rel="icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">


    <link href="assets/css/style.css" rel="stylesheet">


</head>

<body>

    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/logoBuku.png') }}" alt="">
                <span class="d-none d-lg-block">Digital Perpustakaan</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>

        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li>

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ session('name') }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ session('name') }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="/logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Keluar</span>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>

    </header>

    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Data Buku</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/" class="active">
                            <i class="bi bi-circle"></i><span>Data Buku</span>
                        </a>
                    </li>
                    <li>
                        <a href="/tambah-data-buku">
                            <i class="bi bi-circle"></i><span>Tambah Data Buku</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="/datakategori">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Kategori Buku</span>
                </a>
            </li>

        </ul>

    </aside>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>List Data Buku</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Data Buku</a></li>
                    <li class="breadcrumb-item active">List Data Buku</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"></h5>
                            <div class=" d-flex justify-content-between align-items-center">
                                <div>
                                    <label for="kategoriFilter">Filter Kategori Buku:</label>
                                    <select id="kategoriFilter" onchange="filterByCategory()">
                                        <option value="all">Semua Kategori</option>
                                        @foreach ($kategori as $category)
                                            <option value="{{ $category->kategori_buku }}">
                                                {{ $category->kategori_buku }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <a href="/export-to-pdf" class=" btn btn-sm btn-success mx-2">Export PDF</a>
                                </div>
                            </div>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>
                                            No.
                                        </th>
                                        <th>
                                            Judul Buku
                                        </th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Deskripsi</th>
                                        <th>Cover Buku</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buku as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->judul_buku }}</td>
                                            <td>{{ $item->kategori_buku }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td><img src="{{ asset('cover_buku/' . $item->cover_buku) }}"
                                                    class="" width="160px" height="200px" alt="">
                                            </td>
                                            <td>
                                                {{-- <a class="btn btn-sm btn-primary" href="">Lihat Buku</a> --}}
                                                <a class="btn btn-sm btn-primary"
                                                    href="/editbuku/{{ $item->judul_buku }}">Edit</a>
                                                <a class="btn btn-sm btn-success my-2"
                                                    href="{{ asset('file_buku/' . $item->file_buku) }}"
                                                    target="_blank">Lihat</a>
                                                <a class="btn btn-sm btn-danger"
                                                    href="/delete/{{ $item->judul_buku }}">Hapus</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <script>
        function filterByCategory() {
            var filter = document.getElementById('kategoriFilter').value.toLowerCase();
            var rows = document.querySelectorAll('.table.datatable tbody tr');

            rows.forEach(function(row) {
                var category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                if (filter === category || filter === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>



    <script src="assets/js/main.js"></script>
    @include('sweetalert::alert')
</body>

</html>
