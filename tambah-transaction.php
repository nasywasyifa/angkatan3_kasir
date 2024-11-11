<?php
session_start();
session_regenerate_id(true);
date_default_timezone_set("Asia/Jakarta");
require_once "config/koneksi.php";



// Waktu :
$currentTime = date('Y-m-d');

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
                    <form action="controller/transaksi-store.php" method="post">
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
                            <!-- <input type="number" class="text-center form-control" style="width:100px; border-radius: 20px; display: inline;"
                                name="countDisplay" value="<?php echo $_SESSION['click_count'] ?>" id="countDisplay"
                                readonly> -->
                        </div>
                        <div class="table table-responsive">
                            <table class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Kategori</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Sisa Produk</th>
                                        <th>Harga</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <!-- Data ditambah disini -->

                                </tbody>
                                <tfoot class="text-center">
                                    <tr>
                                        <th colspan="6">Total Harga</th>
                                        <td><input type="number" id="total_harga_keseluruhan" name="total_harga"
                                                class="form-control" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">Nominal Bayar</th>
                                        <td><input type="number" id="nominal_bayar_keseluruhan" name="nominal_bayar"
                                                class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">Kembalian</th>
                                        <td><input type="number" class="form-control" id="kembalian_keseluruhan_input"
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
            const table = document.getElementById('table');
            let no = 0;

            button.addEventListener('click', function() {
                no++

                //Fungsi tambah td
                let newRow = "<tr>"
                newRow += "<td>" + no + "</td>";
                newRow += "<td><select class='form-control category-select' name='id_kategori[]' required>";
                newRow += "<option value=''>--Pilih Kategori--</option>";
                <?php foreach ($categories as $category) { ?>
                    newRow += "<option value='<?php echo $category['id'] ?> '><?php echo $category['nama_kategori'] ?></option>";
                <?php
                } ?>
                newRow += "</select></td>";
                newRow += "<td><select class='form-control item-select' name='id_barang[]' required>";
                newRow += "<option value=''>--Pilih Barang--</option>";
                newRow += "</select></td>";
                newRow += "<td><input type='number' class='form-control jumlah-input' name='jumlah[]' value='0' required></td>";
                newRow += "<td><input type='number' class='form-control harga-input' name='sisa_produk[]' readonly></td>";
                newRow += "<td><input type='number' class='form-control' name='harga[]' readonly></td>";
                newRow += "<td><input type='number' class='form-control sub-total' name='sub_total[]' readonly></td>";
                newRow += "</tr>";
                tbody.insertAdjacentHTML('beforeend', newRow);

                attachCategoryChangeListener();
                attachItemChangeListener();
                attachJumlahChangeListener();

            });
            //fungsi untuk menampilkan barang berdasarkan kategori ...

            function attachCategoryChangeListener() {
                const categorySelects = document.querySelectorAll('.category-select');
                categorySelects.forEach(select => {
                    select.addEventListener('change', function() {
                        const categoryId = this.value;
                        const itemSelect = this.closest('tr').querySelector('.item-select');

                        if (categoryId) {
                            fetch(`controller/get-product-dari-category.php?id_kategori=${categoryId}`)
                                .then(response => response.json())
                                .then(data => {
                                    itemSelect.innerHTML = "<option value=''>--Pilih Barang--</option>";
                                    data.forEach(item => {
                                        itemSelect.innerHTML += `<option value ='${item.id}'>${item.nama_barang}</option>`;
                                    });
                                });
                        } else {
                            itemSelect.innerHTML = "<option value=''>--Pilih Barang--</option>";
                        }

                    });
                });
            }
            //untuk menampilkan qty dan harga ...
            function attachItemChangeListener() {
                const itemSelects = document.querySelectorAll('.item-select');
                itemSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        const itemId = this.value;
                        const row = this.closest('tr');
                        const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]');
                        const hargaInput = row.querySelector('input[name="harga[]"]');

                        if (itemId) {
                            fetch('controller/get-barang-details.php?id_barang=' + itemId)
                                .then(response => response.json())
                                .then(data => {
                                    sisaProdukInput.value = data.qty;
                                    hargaInput.value = data.harga;
                                })
                        } else {
                            sisaProdukInput.value = '';
                            hargaInput.value = '';
                        }
                    });
                });
            }
            const totalHargaKeseluruhan = document.getElementById('total_harga_keseluruhan');
            const nominalBayarKeseluruhanInput = document.getElementById('nominal_bayar_keseluruhan');
            const kembalianKeseluruhanInput = document.getElementById('kembalian_keseluruhan_input');
            //fungsi untuk membuat alert jumlah > sisaProduk
            function attachJumlahChangeListener() {
                const jumlahInputs = document.querySelectorAll('.jumlah-input');
                jumlahInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        const row = this.closest('tr');
                        const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]');
                        const hargaInput = row.querySelector('input[name="harga[]"]');
                        const totalHargaInput = document.getElementById('total_harga_keseluruhan');
                        const nominalBayarInput = document.getElementById('nominal_bayar_keseluruhan');
                        const kembalianInput = document.getElementById('kembalian_keseluruhan');

                        const jumlah = parseInt(this.value) || 0;
                        const sisaProduk = parseInt(sisaProdukInput.value) || 0;
                        const harga = parseFloat(hargaInput.value) || 0;

                        if (jumlah > sisaProduk) {
                            alert("Jumlah tidak boleh melebihi sisa produk");
                            this.value = sisaProduk;
                            return;
                        }
                        updateTotalKeseluruhan();
                    });
                });

            }

            function updateTotalKeseluruhan() {
                let total = 0;
                let totalKeseluruhan = 0;
                const jumlahInput = document.querySelectorAll('.jumlah-input');
                jumlahInput.forEach(input => {
                    const row = input.closest('tr');
                    const hargaInput = row.querySelector('input[name="harga[]"]');
                    const harga = parseFloat(hargaInput.value) || 0;
                    const jumlah = parseInt(input.value) || 0;
                    const subTotal = row.querySelector('.sub-total');
                    total = jumlah * harga;
                    subTotal.value = total;
                });

                const subTotal = document.querySelectorAll('.sub-total');
                subTotal.forEach(totalItem => {
                    let subTotal = parseFloat(totalItem.value) || 0;
                    totalKeseluruhan += subTotal;

                });
                totalHargaKeseluruhan.value = totalKeseluruhan;
            }
            //untuk menghitung kembalian
            nominalBayarKeseluruhanInput.addEventListener('input', function() {
                const nominalBayar = parseFloat(this.value) || 0;
                const totalHarga = parseFloat(totalHargaKeseluruhan.value) || 0;

                if (nominalBayar >= totalHarga) {
                    let kembalian = nominalBayar - totalHarga;
                    kembalianKeseluruhanInput.value = kembalian;
                } else if (nominalBayar == 0) {

                    kembalianKeseluruhanInput.value = 0;
                }
            });
        });
    </script>
</body>

</html>