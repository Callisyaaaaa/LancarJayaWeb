<?php
require 'ceklogin.php';


$total_subtotal = 0;

if (isset($_GET['idp'])) {
    $idp = $_GET['idp'];
    $ambilnamapelanggan = mysqli_query($conn, "SELECT * FROM pesanan p, pelanggan pl WHERE p.idpelanggan=pl.idpelanggan AND p.idpesanan='$idp'");

    if ($ambilnamapelanggan && mysqli_num_rows($ambilnamapelanggan) > 0) {
        $np = mysqli_fetch_array($ambilnamapelanggan);
        $namapel = $np['namapelanggan'];
    } else {
        // Jika tidak ada data yang ditemukan, arahkan kembali ke index.php dengan pesan kesalahan
        header('location:index.php?error=DataNotFound');
        exit();
    }
} else {
    header('location:index.php');
    exit();
}

$get = mysqli_query($conn, "SELECT * FROM detailpesanan dp, produk pr WHERE dp.idProduk=pr.idproduk AND dp.idpesanan='$idp'");
while ($p = mysqli_fetch_array($get)) {
    $qty = $p['qty'];
    $harga = $p['harga'];
    $subtotal = $qty * $harga;
    $total_subtotal += $subtotal; // Tambahkan subtotal ke total
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">APLIKASI KASIR</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="far fa-edit"></i></div>
                            PESANAN
                        </a>
                        <a class="nav-link" href="stok.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-dice-d6"></i></div>
                            STOK BARANG
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="far fa-clipboard"></i></div>
                            BARANG MASUK
                        </a>
                        <a class="nav-link" href="pelanggan.php">
                            <div class="sb-nav-link-icon"><i class="far fa-clipboard"></i></div>
                            KELOLA PELANGGAN
                        </a>
                        <a class="nav-link" href="logout.php">
                            LOGOUT
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Pesanan: <?=$idp;?></h1>
                    <h4 class="mt-4">Nama Pelanggan: <?=$namapel;?></h4>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Toko Bangunan Lancar Jaya</li>
                    </ol>
                    <button type="button" class="btn btn-info mb-4 ml-3" data-toggle="modal" data-target="#myModal">
                        Tambah Barang
                    </button>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Pesanan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Nama Produk</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Sub-total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get = mysqli_query($conn, "SELECT * FROM detailpesanan dp, produk pr WHERE dp.idProduk=pr.idproduk AND dp.idpesanan='$idp'");
                                    $i = 1;
                                    while ($p = mysqli_fetch_array($get)) {
                                        $idpr = $p['idProduk'];
                                        $iddp = $p['iddetailpesanan'];
                                        $qty = $p['qty'];
                                        $harga = $p['harga'];
                                        $namaProduk = $p['namaProduk'];
                                        $desc = $p['deskripsi'];
                                        $subtotal = $qty * $harga;
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$namaProduk;?> (<?= $desc;?>)</td> 
                                        <td>Rp<?=number_format($harga);?></td>
                                        <td><?=number_format($qty);?></td>
                                        <td>Rp<?=number_format($subtotal);?></td>
                                        <td>  
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idpr;?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idpr;?>">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit<?=$idpr;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Ubah Data Detail Pesanan <?=$namaProduk;?></h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="post">
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <input type="text" name="namaProduk" class="form-control" placeholder="Nama_Produk" value="<?=$namaProduk;?>:<?=$desc;?>" disabled>
                                                        <input type="number" name="qty" class="form-control mt-2" placeholder="qty" value="<?=$qty;?>" min="1" required>
                                                        <input type="hidden" name="iddp" value="<?=$iddp;?>">
                                                        <input type="hidden" name="idp" value="<?=$idp;?>">
                                                        <input type="hidden" name="idpr" value="<?=$idpr;?>">
                                                    </div>
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="editdetailpesanan">Submit</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="delete<?=$idpr;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title"> Apakah Anda Yakin Ingin Menghapus barang ini? </h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form method="post">
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        Apakah Anda Yakin Ingin Menghapus Barang Ini? 
                                                        <input type="hidden" name="idp" value="<?=$iddp;?>">
                                                        <input type="hidden" name="idpr" value="<?=$idpr;?>">
                                                        <input type="hidden" name="idpesanan" value="<?=$idp;?>">
                                                    </div>
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="hapusprodukpesanan">Submit</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Total Yang Harus Dibayarkan: Rp<?=number_format($total_subtotal);?></div>
                            </div>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-danger" onclick="printPage()">Print</button>
                            </div>
                            <script>
                                function printPage() {
                                    window.print();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <!-- Modal body -->
                    <div class="modal-body">
                        Pilih Barang
                        <select name="idProduk" class="form-control" required>
                            <?php
                            $getproduk = mysqli_query($conn, "SELECT * FROM produk");
                            while ($pl = mysqli_fetch_array($getproduk)) {
                                $namaProduk = $pl['namaProduk'];
                                $stok = $pl['stok'];
                                $deskripsi = $pl['deskripsi'];
                                $idProduk = $pl['idProduk'];
                            ?>
                            <option value="<?=$idProduk;?>"><?=$namaProduk;?> - <?=$deskripsi;?> (Stok: <?=$stok;?>)</option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="number" name="qty" class="form-control mt-4" placeholder="Jumlah" min="1" required>
                        <input type="hidden" name="idp" value="<?=$idp;?>">
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="addproduk">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
