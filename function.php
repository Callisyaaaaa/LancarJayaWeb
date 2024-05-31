<?php

session_start();


$conn = mysqli_connect('localhost','root','','kasir');


// LOGIN.PHP'S FUNCTION
if(isset($_POST['login'])){
    $username = $_POST ['username'];
    $password = $_POST ['password'];

    $check = mysqli_query ($conn,"SELECT * FROM user WHERE username='$username' and password='$password' ");
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
        $_SESSION['login'] = 'True';
        header('location:index.php');

    } else {

        echo '
        <script>alert("Username atau Password SALAH");
        window.location.href="login.php"
        </script>
        ';
    }
}

// PESANAN.PHP'S FUNCTION
if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];
    
    
    $insert = mysqli_query($conn, "insert into pesanan ( idpelanggan ) values ('$idpelanggan')");

    if($insert){
        header('location:index.php');
    } else {
        echo '
        <script>alert(" Gagal Menambah pesanan Baru ");
        window.location.href="index.php"
        </script>
        ';
    }
}

if(isset($_POST['editdetailpesanan'])){
    $qty = $_POST['qty'];
    $iddp = $_POST['iddp'];
    $idpr = $_POST['idpr']; 
    $idp =  $_POST['idp'];

    //cari tahu qty nya sekarang berapa
    $caritahu=mysqli_query($conn,"select*from detailpesanan where iddetailpesanan='$iddp'");
    $caritahu2=mysqli_fetch_array($caritahu);
    $qtysekarang=$caritahu2['qty'];

    //cari tahu stok nya sekarang berapa
    $caristok=mysqli_query($conn,"select*from produk where idProduk='$idpr'");
    $caristok2=mysqli_fetch_array($caristok);
    $stoksekarang=$caristok2['stok'];

    if($qty >= $qtysekarang){
        //kalau inputan user lebih besar daripada qty yang dicatat
        // hitung selisih
        $selisih = $qty-$qtysekarang;
        $newstok = $stoksekarang-$selisih;

        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($conn, "update produk set stok='$newstok' where idProduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>alert("Gagal memperbarui data.");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    } else {
        //kalau lebih kecil
        // hitung selisih
        $selisih = $qtysekarang-$qty;
        $newstok = $stoksekarang+$selisih;

        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($conn, "update produk set stok='$newstok' where idProduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>alert("Gagal memperbarui data.");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    }
}if(isset($_POST['editdetailpesanan'])){
    $qty = $_POST['qty'];
    $iddp = $_POST['iddp'];
    $idpr = $_POST['idpr']; 
    $idp =  $_POST['idp'];

    
    $caritahu=mysqli_query($conn,"select*from detailpesanan where iddetailpesanan='$iddp'");
    $caritahu2=mysqli_fetch_array($caritahu);
    $qtysekarang=$caritahu2['qty'];

    
    $caristok=mysqli_query($conn,"select*from produk where idProduk='$idpr'");
    $caristok2=mysqli_fetch_array($caristok);
    $stoksekarang=$caristok2['stok'];

    if($qty >= $qtysekarang){
        
        $selisih = $qty-$qtysekarang;
        $newstok = $stoksekarang-$selisih;

        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($conn, "update produk set stok='$newstok' where idProduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>alert("Gagal memperbarui data.");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    } else {
        //kalau lebih kecil
        // hitung selisih
        $selisih = $qtysekarang-$qty;
        $newstok = $stoksekarang+$selisih;

        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($conn, "update produk set stok='$newstok' where idProduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>alert("Gagal memperbarui data.");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    }
}

if(isset($_POST['hapusdatapesanan'])){
    $idp = $_POST['idp'];

    $query = mysqli_query($conn, "DELETE FROM pesanan WHERE idpesanan ='$idp'");

    if ($query){
        header('location: index.php');
        exit(); 
    }else{
        echo '
        <script>alert("Gagal");
        window.location.href="index.php"
        </script>
        '; 
    }
}

if(isset($_POST['hapuspesanan'])){
    $idp = $_POST['idp'];//idpesanan

    $cekdata = mysqli_query($conn,"SELECT * FROM detailpesanan dp WHERE idpesanan='$idp'");

    $allQueriesSuccessful = true; 

    
    while($ok = mysqli_fetch_array($cekdata)){
        $qty = $ok['qty'];
        $idProduk = $ok['idProduk'];
        $iddp = $ok['iddetailpesanan'];

        $caristok = mysqli_query($conn, "SELECT * FROM produk WHERE idproduk='$idProduk'");
        if ($caristok && mysqli_num_rows($caristok) > 0) {
            $caristok2 = mysqli_fetch_array($caristok);
            $stoksekarang = $caristok2['stok'];

            $newstok = $stoksekarang + $qty;
            $queryupdate = mysqli_query($conn, "UPDATE produk SET stok='$newstok' WHERE idproduk='$idProduk'");

            $querydelete = mysqli_query($conn, "DELETE FROM detailpesanan WHERE iddetailpesanan='$iddp'");

            if (!$queryupdate || !$querydelete) {
                $allQueriesSuccessful = false;
                break;
            }
        } else {
            $allQueriesSuccessful = false;
            break;
        }
    }

    if ($allQueriesSuccessful) {
        $query = mysqli_query($conn, "DELETE FROM pesanan WHERE idpesanan='$idp'");
        if ($query) {
            header('location: index.php');
            exit();
        } else {
            echo '<script>alert("Gagal menghapus pesanan"); window.location.href="index.php";</script>';
        }
    } else {
        echo '<script>alert("Gagal memperbarui stok atau menghapus detail pesanan"); window.location.href="index.php";</script>';
    }
}

// VIEW.PHP'S FUNCTION
if (isset($_POST['addproduk'])) {
    if (!empty($_POST['idProduk']) && !empty($_POST['qty']) && !empty($_POST['idp'])) {
        $idProduk = $_POST['idProduk'];
        $qty = (int)$_POST['qty'];
        $idp = $_POST['idp'];

        // Cek stok produk
        $cekstok = mysqli_query($conn, "SELECT stok FROM produk WHERE idProduk = '$idProduk'");
        if ($cekstok && mysqli_num_rows($cekstok) > 0) {
            $ambilstok = mysqli_fetch_array($cekstok);
            $stok = $ambilstok['stok'];

            // Cek apakah stok mencukupi
            if ($stok >= $qty) {
                // Kurangi stok produk
                $newstok = $stok - $qty;
                $update = mysqli_query($conn, "UPDATE produk SET stok = '$newstok' WHERE idProduk = '$idProduk'");

                // Tambah detail pesanan
                $insert = mysqli_query($conn, "INSERT INTO detailpesanan (idPesanan, idProduk, qty) VALUES ('$idp', '$idProduk', '$qty')");

                if ($update && $insert) {
                    echo '<script>alert("Barang berhasil ditambahkan"); window.location.href="view.php?idp='.$idp.'";</script>';
                } else {
                    echo '<script>alert("Gagal menambahkan barang"); window.location.href="view.php?idp='.$idp.'";</script>';
                }
            } else {
                echo '<script>alert("Stok tidak cukup"); window.location.href="view.php?idp='.$idp.'";</script>';
            }
        } else {
            echo '<script>alert("Produk tidak ditemukan"); window.location.href="view.php?idp='.$idp.'";</script>';
        }
    } else {
        echo '<script>alert("Data tidak lengkap"); window.location.href="view.php?idp='.$idp.'";</script>';
    }
}

if(isset($_POST['hapusprodukpesanan'])){
    $idp = $_POST['idp'];
    $idpr = $_POST['idpr'];
    $idps  = $_POST['idpesanan'];

    $cek1 = mysqli_query($conn, "select * from detailpesanan where iddetailpesanan = ' $idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    $cek3 = mysqli_query($conn, "select * from produk where idProduk = '$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stoksekarang = $cek4['stok'];

    $hitung = $stoksekarang+$qtysekarang;

    $update = mysqli_query($conn, "update produk set stok = '$hitung' where idProduk='$idpr'");
    $hapus = mysqli_query($conn, "delete from detailpesanan where idProduk='$idpr' and iddetailpesanan = '$idp'");

    // var_dump($idp);
    // exit();
    if($update&&$hapus){
        header('location:view.php?idp='.$idps);
    } else {
        echo '
        <script>alert(" Gagal Menghapus Barang ");
        window.location.href="view.php?idp='.$idpesanan.'"
        </script> 
        ';

    }
}




// STOK.PHP'S FUNCTION

if(isset($_POST['tambahbarang'])){
    $namaProduk = $_POST['namaProduk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $insert = mysqli_query($conn, "insert into produk (namaProduk, deskripsi, harga, stok) values 
    ('$namaProduk', '$deskripsi', '$harga', '$stok')");

    if($insert){
        header('location:stok.php');
    } else {
        echo '
        <script>alert(" Gagal Menambah Barang Baru ");
        window.location.href="stok.php"
        </script>
        ';
    }

}
if(isset($_POST['editbarang'])){
    $namaProduk= $_POST['namaProduk'];
    $desc= $_POST['deskripsi'];
    $harga= $_POST['harga'];
    $idp= $_POST['idp'];

    $query=mysqli_query($conn, "update produk set namaProduk='$namaProduk', deskripsi='$desc', harga='$harga' where idProduk ='$idp' ");

    if ($query){
        Header('location: stok.php');
    }else{
        echo '
        <script>alert(" Gagal");
        window.location.href="stok.php"
        </script>
        '; 
        
    }
}

if(isset($_POST['hapusbarang'])){
    $idp= $_POST['idp'];

    $query = mysqli_query($conn, "DELETE FROM  produk WHERE idProduk ='$idp'");

    if ($query){
        Header('location: stok.php');
    }else{
        echo '
        <script>alert(" Gagal");
        window.location.href="stok.php"
        </script>
        '; 
            
    }
}

//PELANGGAN.PHP'S FUNCTION

if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    $insert = mysqli_query($conn, "insert into pelanggan (namapelanggan, notelp, alamat) values 
    ('$namapelanggan', '$notelp', '$alamat')");

    if($insert){
        header('location:pelanggan.php');
    } else {
        echo '
        <script>alert(" Gagal Menambah pelanggan Baru ");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}
if(isset($_POST['editpelanggan'])){
    $np = $_POST['namapelanggan'];
    $nt = $_POST['notelp'];
    $a = $_POST['alamat'];
    $id = $_POST['idpl'];

    $query = mysqli_query($conn,"update pelanggan set namapelanggan='$np', notelp='$nt', alamat='$a' where idpelanggan='$id'");

    if ($query){
        header('location:pelanggan.php');

    } else {
        echo'
        <script>alert("Gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}

if(isset($_POST['hapuspelanggan'])){
    $idpl= $_POST['idpl'];

    $query = mysqli_query($conn, "DELETE FROM pelanggan WHERE idpelanggan ='$idpl'");

    if ($query){
        Header('location: pelanggan.php');
    }else{
        echo '
        <script>alert(" Gagal");
        window.location.href="pelanggan.php"
        </script>
        '; 
            
    }
}

// MASUK.PHP'S FUNCTION
if(isset($_POST['barangmasuk'])){
    $idProduk = $_POST['idProduk'];
    $qty = $_POST['qty'];

    //cari tahu stok nya sekarang berapa
    $caristok=mysqli_query($conn,"select*from brgmasuk where idProduk='$idp'");
    $caristok2=mysqli_fetch_array($caristok);
    $stoksekarang=$caristok2['stok'];

    //hitung
    $newstok = $stoksekarang+$qty;

    $insertb = mysqli_query($conn, "insert into brgmasuk (idProduk,qty) values ('$idProduk','$qty')");
    $updatetb = mysqli_query($conn, "update produk set stok='$newstok' where idProduk='$idProduk'");

    if($insertb&&$updatetb){
        header('location:masuk.php');

    } else {
        echo '
        <script>alert("Gagal");
        window.location.href="masuk.php"
        </script>
        ';
    }
}

if(isset($_POST['editdatabarangmasuk'])){
    $qty = $_POST['qty'];
    $idm = $_POST['idm']; //id masuk
    $idp = $_POST['idp']; //id produk


    //cari tahu qty nya sekarang berapa
    $caritahu = mysqli_query($conn, "SELECT * FROM brgmasuk WHERE idmasuk='$idm'");
    if ($caritahu && mysqli_num_rows($caritahu) > 0) {
        $caritahu2 = mysqli_fetch_array($caritahu);
        $qtysekarang = $caritahu2['qty'];

        // cari tahu berapa stok sekarang
        $caristok = mysqli_query($conn, "SELECT * FROM produk WHERE idProduk='$idp'");
        if ($caristok && mysqli_num_rows($caristok) > 0) {
            $caristok2 = mysqli_fetch_array($caristok);
            $stoksekarang = $caristok2['stok'];

            if ($qty >= $qtysekarang) {
                // kalau inputan user lebih besar daripada qty yang dicatat
                // hitung selisih
                $selisih = $qty - $qtysekarang;
                $newstok = $stoksekarang + $selisih;

                $query1 = mysqli_query($conn, "UPDATE brgmasuk SET qty='$qty' WHERE idmasuk='$idm'");
                $query2 = mysqli_query($conn, "UPDATE produk SET stok='$newstok' WHERE idProduk='$idp'");

                if ($query1 && $query2) {
                    header('Location: masuk.php');
                    exit();
                } else {
                    echo '<script>alert("Gagal memperbarui data.");
                    window.location.href="masuk.php";
                    </script>';
                }
            } else {
                // kalau lebih kecil
                // hitung selisih
                $selisih = $qtysekarang - $qty;
                $newstok = $stoksekarang - $selisih;

                $query1 = mysqli_query($conn, "UPDATE brgmasuk SET qty='$qty' WHERE idmasuk='$idm'");
                $query2 = mysqli_query($conn, "UPDATE produk SET stok='$newstok' WHERE idProduk='$idp'");

                if ($query1 && $query2) {
                    header('Location: masuk.php');
                    exit();
                } else {
                    echo '<script>alert("Gagal memperbarui data.");
                    window.location.href="masuk.php";
                    </script>';
                }
            }
        } else {
            echo '<script>alert("Produk tidak ditemukan.");
            window.location.href="masuk.php";
            </script>';
        }
    } else {
        echo '<script>alert("Barang masuk tidak ditemukan.");
        window.location.href="masuk.php";
        </script>';
    }
}

if(isset($_POST['hapusdatabarangmasuk'])){
    $idm = $_POST['idm'];
    $idp = $_POST['idp'];

    // Cari tahu qty nya sekarang berapa
    $caritahu = mysqli_query($conn, "SELECT * FROM brgmasuk WHERE idmasuk='$idm'");
    if ($caritahu && mysqli_num_rows($caritahu) > 0) {
        $caritahu2 = mysqli_fetch_array($caritahu);
        $qtysekarang = $caritahu2['qty'];

        // Fungsi cari tahu berapa stok sekarang
        $caristok = mysqli_query($conn, "SELECT * FROM produk WHERE idProduk='$idp'");
        if ($caristok && mysqli_num_rows($caristok) > 0) {
            $caristok2 = mysqli_fetch_array($caristok);
            $stoksekarang = $caristok2['stok'];

            // Hitung selisih
            $newstok = $stoksekarang - $qtysekarang;

            // Lakukan penghapusan data dan pembaruan stok
            $query1 = mysqli_query($conn, "DELETE FROM brgmasuk WHERE idmasuk='$idm'");
            $query2 = mysqli_query($conn, "UPDATE produk SET stok='$newstok' WHERE idProduk='$idp'");

            if ($query1 && $query2) {
                header('Location: masuk.php');
                exit();
            } else {
                echo '
                <script>alert("Gagal menghapus data atau memperbarui stok.");
                window.location.href="masuk.php";
                </script>
                ';
            }
        } else {
            echo '
            <script>alert("Produk tidak ditemukan.");
            window.location.href="masuk.php";
            </script>
            ';
        }
    } else {
        echo '
        <script>alert("Barang masuk tidak ditemukan.");
        window.location.href="masuk.php";
        </script>
        ';
    }
}

//edit data detail pesanan


// if(isset($_POST['hapuspesanan'])){
//     $idp = $_POST['idp'];//idpesanan

//     $cekdata = mysqli_query($conn,"select * from detailpesanan dp where idpesanan='$idp'");

//     while($ok=mysqli_fetch_array($cekdata)){
//         //balikin stok
//         $qty = $ok['qty'];
//         $idProduk = $ok['idProduk'];
//         $iddp = $ok['iddetailpesanan'];

//         $caristok= mysqli_query($conn,"select * from produk where idproduk='$idProduk'");
//         $caristok2=mysqli_fetch_array($caristok);
//         $stoksekarang=$caristok2['stok'];

//         $newstok = $stoksekarang+$qty;
//         $queryupdate= mysqli_query($conn,"update produk set stok='$newstok' where idproduk='$idProduk'");


//         //hapus data
//         $querydelete = mysqli_query($conn,"delete from detailpesanan where iddetailpesanan='$iddp'");

//         //redirect
//     }

//     $query = mysql_query($conn,"delete from pelanggan where idpelanggan='$idpl'");

//     if($queryupdate && $querydelete && $query){
//         header('location:index.php');
//     }else {
//         echo'
//         <script>alert("gagal");
//         window.location.href="index.php"
//         </script>
//         ';
//     }
// }
 