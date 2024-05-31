<?php
require 'ceklogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>STOK BARANG</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark" style="background-color:#343a40;"> 
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">APLIKASI KASIR</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark "style="background-color:#6c757;" id="sidenavAccordion">
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
                        <a class="nav-link" href="Logout.php">
                            LOGOUT
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Barang Masuk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Toko Bangunan Lancar Jaya</li>
                    </ol>
                    <button type="button" class="btn btn-info mb-4" data-toggle="modal" data-target="#myModal">
                        Tambah Barang Masuk
                    </button>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Barang masuk
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get = mysqli_query($conn, "SELECT m.*, p.namaProduk, p.deskripsi FROM brgmasuk m JOIN produk p ON m.idProduk=p.idProduk");
                                    $i = 1;
                                    while ($p = mysqli_fetch_array($get)) {
                                        $namaProduk = $p['namaProduk'];
                                        $deskripsi = $p['deskripsi'];
                                        $qty = $p['qty']; 
                                        // menambah idmasuk
                                        $idmasuk = $p['idmasuk'];
                                        $idProduk =$p['idProduk'];
                                        $tanggal = $p['tanggalmasuk']; 
                                    ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$namaProduk;?></td>
                                            <td><?=$deskripsi;?></td>
                                            <td><?=$qty;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#edit<?=$idmasuk;?>">
                                                    Edit
                                                </button> 
                                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#delete<?=$idmasuk;?>">
                                                    Delete
                                                </button> 

                                            </td>
                                        </tr>

                                        <!-- membuat tombol responsive -->
                                        <!-- Modal edit -->
                                        <div class="modal fade" id="edit<?=$idmasuk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Ubah Data barang masuk <?=$namaProduk;?></h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <form method="post">
                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <input type="text" name="namaProduk" class="form-control" placeholder="Nama_Produk" value="<?=$namaProduk;?>:<?=$deskripsi;?>" disabled>
                                                            <input type="number" name="qty" class="form-control mt-2" placeholder="Harga_Produk" value="<?=$qty;?>" min="1" required>
                                                            <input type="hidden" name="idm" value="<?=$idmasuk;?>">
                                                            <!-- Tambah id produk -->
                                                            <input type="hidden" name="idp" value="<?=$idProduk;?>">
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success" name="editdatabarangmasuk">Submit</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                                </div>
                                            </div>
                                         </div>
                                          
                                         <!--  HAPUS BARANG MASUK-->
                                          <!-- The Modal delete -->
                                        <div class="modal fade" id="delete<?=$idmasuk;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus data barang masuk <?=$namaProduk;?></h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <form method="post">
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        Apakah anda yakin akan menghapus data ini?
                                                        <input type="hidden" name="idp" value="<?=$idProduk;?>">
                                                        <input type="hidden" name="idm" value="<?=$idmasuk;?>">
                                                    </div>
                                                    
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="hapusdatabarangmasuk">Submit</button>
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
</body>
   <!-- Modal -->
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
                        <select name="idProduk" class="form-control">
                            <?php
                            $getproduk = mysqli_query($conn, "SELECT * FROM produk");
                            while($pl = mysqli_fetch_array($getproduk)){
                                $namaProduk = $pl['namaProduk'];
                                $stok = $pl['stok'];
                                $deskripsi = $pl['deskripsi'];
                                $idProduk = $pl['idProduk'];
                            ?>
                            <option value="<?=$idProduk;?>"><?=$namaProduk;?> - <?=$deskripsi;?> ( Stok : <?=$stok;?>)</option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="number" name="qty" class="form-control mt-4" placeholder="Jumlah" min="1" required >
                        
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="barangmasuk">Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</html>
