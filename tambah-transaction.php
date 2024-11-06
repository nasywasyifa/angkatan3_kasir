<?php
session_start();
session_regenerate_id(true);
date_default_timezone_set("Asia/Jakarta");
require_once "config/koneksi.php";



// Waktu :
$currentTime = date('d-M-y');

// generate function (method)
function generateTransactionCode()
{
    $kode = date('dMyhis');

    return $kode;
}
// click count
if (empty($_SESSION['click_count'])) {
    $_SESSION['click_count'] = 0;
}

//Jika session nya isi, maka melempar ke dashboard.php
// if(isset($_SESSION['NAMALENGKAPNYA'])){
//     header("Location: kasir.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body style="background-image: url(image/qwerty.png); background-size:cover">
    <nav class="navbar navbar-expand-lg  sticky-top" style="background-color: pink;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAltMarkup"
                aria-controls="navAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                </span>
            </button>

            <div class="collapse navbar-collapse" id="navAltMarkup">
                <div class="navbar-nav mt-2 mb-2">
                    <a href=" index.php" class="nav-link ">Dashboard</a>
                    <a href="manageaccounts.php" class="nav-link ">Manage Accounts</a>
                    <a href="managebooks.php" class="nav-link ">Manage Books</a>
                </div>
            </div>
            <a style="border: 2px;" class="btn btn-outline-primary rounded-button"
                onclick="return confirm('Apakah Anda Yakin untuk Log-Out?')" href="controller/logout.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Garis vertikal untuk simbol power -->
                    <line x1="12" y1="2" x2="12" y2="12"></line>
                    <!-- Lingkaran di sekitar garis -->
                    <path d="M16.24 7.76a6 6 0 1 1-8.48 0"></path>
                </svg>
            </a>
        </div>
    </nav>
    <div class="container justify-content-center position-absolute" style="margin-top: 110px; margin-left: 270px;">
        <div class="row">
            <div class="card shadow-lg p-3 mb-5  rounded" style="background-color: pink;">
                <div class="card-header">
                    <h4 class="text-center">Add Transaksi</h1>
                </div>
                <div class="col-8 offset-2">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="form-label ">No. Transaksi</label>
                            <input style="border-radius: 20px;" class="form-control w-50" name="kode_transaksi"
                                id="kode_transaksi" type="text" value="<?php echo "TR-" . generateTransactionCode() ?>"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label ">Tanggal Transaksi</label>
                            <input style="border-radius: 20px;" class="form-control w-50" name="tanggal_transaksi"
                                id="tanggal_transaksi" type="date" value="<?php echo $currentTime ?>" readonly>
                        </div>
                        <div class="mb-1">
                            <button style="border-radius: 20px;" class="btn btn-primary" type="button"
                                id="counterBtn">Tambah</button>
                            <input type="number" class="text-center form-control" style="width:100px; border-radius: 20px; display: inline;"
                                name="countDisplay" value="<?php echo $_SESSION['click_count'] ?>" id="countDisplay"
                                readonly>
                        </div>
                        <div class="table table-responsive">
                            <table class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Kategori</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Sisa Produk</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <!-- Data ditambah disini -->

                                </tbody>
                                <tfoot class="text-center">
                                    <tr>
                                        <th colspan="5">Total Harga</th>
                                        <td><input type="number" id="total_harga_keseluruhan" name="total_harga"
                                                class="form-control" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="5">Nominal Bayar</th>
                                        <td><input type="number" id="nominal_bayar_keseluruhan" name="nominal_bayar"
                                                class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <th colspan="5">Kembalian</th>
                                        <td><input type="number" class="form-control" id="kembalian_keseluruhan"
                                                name="kembalian" readonly>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <br><br>
                            <div class="mb-3">
                                <input type="submit" class="btn btn-primary" name="simpan" value="Hitung">
                                <a class="btn btn-danger" href="kasir.php">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
    <?php
    $query = mysqli_query($koneksi, "SELECT * from kategori_barang");
    $categories = mysqli_fetch_all($query, MYSQLI_ASSOC);
    ?>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            //Fungsi tambah baris
            const button = document.getElementById('counterBtn');
            const countDisplay = document.getElementById('countDisplay');
            const tbody = document.getElementById('tbody');

            button.addEventListener('click', function() {
                let currentCount = parseInt(countDisplay.value) || 0;
                currentCount++;
                countDisplay.value = currentCount;
                //Fungsi tambah td
                let newRow = "<tr>"
                newRow += "<td>" + currentCount + "</td>";
                newRow += "<td><select class='form-control category-select' name='id_kategori[]' required>";
                newRow += "<option value=''>--Pilih Kategori--</option>";
                <?php foreach ($categories as $category) { ?>
                    newRow += "<option value='<?php echo $category['id'] ?> '><?php echo $category['nama_kategori'] ?></option>";
                <?php
                } ?>
                newRow += "</select></td>";
                newRow += "</tr>";
                tbody.insertAdjacentHTML('beforeend', newRow);


            })
        })
    </script>
</body>

</html>