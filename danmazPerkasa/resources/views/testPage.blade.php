<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Links with Descriptions and Access Levels</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* CSS for clicked row */
        .clicked {
            background-color: #d3ffd3; /* Light green background for clicked row */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Links with Descriptions and Access Levels</h1>
        <table>
            <thead>
                <tr>
                    <th>LINK</th>
                    <th>KETERANGAN</th>
                    <th>AKSES</th>
                </tr>
            </thead>
            <tbody>
            <tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/isNew/1">/isNew/1</a></td>
    <td>Tandai produk sebagai baru</td>
    <td>NOT</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/deleteTempCheckout">/deleteTempCheckout</a></td>
    <td>Hapus checkout sementara</td>
    <td>NOT</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/UpdateStatus/1/1">/UpdateStatus/1/1</a></td>
    <td>Update status item</td>
    <td>NOT</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/PaymentStatus/1">/PaymentStatus/1</a></td>
    <td>Cek status pembayaran</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/getCity/1">/getCity/1</a></td>
    <td>Get data kota by province</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/OrderDone">/OrderDone</a></td>
    <td>Tampilan proses payment</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Payment/1">/Payment/1</a></td>
    <td>Tampilan halaman pembayaran</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/RedirectNewestTransaction">/RedirectNewestTransaction</a></td>
    <td>Redirect ke transaksi terbaru</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Profile/1">/Profile/1</a></td>
    <td>Tampilan info profil</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/UpdateStatus/1/1">/UpdateStatus/1/1</a></td>
    <td>Update status item</td>
    <td>NOT</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/PaymentStatus/1">/PaymentStatus/1</a></td>
    <td>Cek status pembayaran</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/getCity/1">/getCity/1</a></td>
    <td>Get data kota by province</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/OrderDone">/OrderDone</a></td>
    <td>Tampilan proses payment</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Payment/1">/Payment/1</a></td>
    <td>Tampilan halaman pembayaran</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/RedirectNewestTransaction">/RedirectNewestTransaction</a></td>
    <td>Redirect ke transaksi terbaru</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Profile/1">/Profile/1</a></td>
    <td>Tampilan info profil</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Custom">/Custom</a></td>
    <td>Custom order</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/OnContent/1">/OnContent/1</a></td>
    <td>Aktifkan konten produk</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/OffContent/1">/OffContent/1</a></td>
    <td>Nonaktifkan konten produk</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/History">/History</a></td>
    <td>Lihat histori transaksi</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/OrderDone/1">/OrderDone/1</a></td>
    <td>Menyelesaikan order</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/OrderDoneCustom/1">/OrderDoneCustom/1</a></td>
    <td>Order custom selesai</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Checkout/1/1">/Checkout/1/1</a></td>
    <td>Checkout dari cart</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Checkout-view-direct/1">/Checkout-view-direct/1</a></td>
    <td>Checkout langsung produk</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/CheckoutCustom">/CheckoutCustom</a></td>
    <td>Checkout custom</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Transaction/1">/Transaction/1</a></td>
    <td>Lihat transaksi</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Transaction">/Transaction</a></td>
    <td>Halaman transaksi (dengan redirect login jika belum login)</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Transaction/Cancel/1">/Transaction/Cancel/1</a></td>
    <td>Batalkan transaksi</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/ViewTransaction/1">/ViewTransaction/1</a></td>
    <td>Lihat detail transaksi</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Profile">/Profile</a></td>
    <td>Redirect ke /Profile/Info jika login</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Profile/Address-Update">/Profile/Address-Update</a></td>
    <td>Update profile</td>
    <td>LOGIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Logout">/Logout</a></td>
    <td>Logout user</td>
    <td>CUSTOMER</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/">/</a></td>
    <td>Redirect ke /Index</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Index">/Index</a></td>
    <td>Landing Page produk</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/RegistrationAccount">/RegistrationAccount</a></td>
    <td>Proses registrasi</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/cekLogin/1">/cekLogin/1</a></td>
    <td>Cek login (Ajax / API)</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Search/1">/Search/1</a></td>
    <td>Search produk</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Product">/Product</a></td>
    <td>Redirect ke /Product/Info</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Product/1">/Product/1</a></td>
    <td>Halaman produk</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Detil-Product/1">/Detil-Product/1</a></td>
    <td>Detil produk</td>
    <td>ALL</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/AddToCart/1">/AddToCart/1</a></td>
    <td>Tambah ke keranjang</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/DeleteCart/1">/DeleteCart/1</a></td>
    <td>Hapus dari keranjang</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Cart">/Cart</a></td>
    <td>Lihat keranjang</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/UpdateCart/1">/UpdateCart/1</a></td>
    <td>Update jumlah produk</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Manage/Transaction">/Manage/Transaction</a></td>
    <td>Manajemen transaksi</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Transaction/AcceptOrder/1">/Transaction/AcceptOrder/1</a></td>
    <td>Terima order</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Transaction/RejectOrder/1">/Transaction/RejectOrder/1</a></td>
    <td>Tolak order</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Manage/User">/Manage/User</a></td>
    <td>Manajemen user</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/db">/db</a></td>
    <td>Dashboard admin</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/livewire">/livewire</a></td>
    <td>Halaman testing Livewire</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/viewUser/1">/viewUser/1</a></td>
    <td>Lihat detail user</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/DeactiveAccount/1">/DeactiveAccount/1</a></td>
    <td>Nonaktifkan akun</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/DeleteAccount/1">/DeleteAccount/1</a></td>
    <td>Hapus akun</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Manage/Product/1">/Manage/Product/1</a></td>
    <td>Manajemen produk</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/viewProduct/1">/viewProduct/1</a></td>
    <td>Lihat produk admin</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/getDataProduct/1">/getDataProduct/1</a></td>
    <td>Ambil data produk</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/editProduct/1">/editProduct/1</a></td>
    <td>Edit produk</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/deleteProduct/1">/deleteProduct/1</a></td>
    <td>Hapus produk</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/Part-Manage/Info">/Part-Manage/Info</a></td>
    <td>Manajemen part</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/add-product/1">/add-product/1</a></td>
    <td>Tambah produk</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/editPart/1">/editPart/1</a></td>
    <td>Edit part</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/deletePart/1">/deletePart/1</a></td>
    <td>Hapus part</td>
    <td>ADMIN</td>
</tr>
<tr onclick="Go(this)">
    <td><a href="http://127.0.0.1:8000/getDataPart/1">/getDataPart/1</a></td>
    <td>Get data part produk</td>
    <td>ADMIN</td>
</tr>

<!-- Continue with other entries similarly -->
            </tbody>
        </table>
    </div>

    <script>
        function Go(elemen) {
            // Remove the 'clicked' class from all rows
            var rows = document.querySelectorAll("tr");
            rows.forEach(function(row) {
                row.classList.remove("clicked");
            });

            // Add 'clicked' class to the clicked row
            elemen.classList.add("clicked");

            // Redirect to the link
            window.location.href = elemen.querySelector('a').getAttribute('href');
        }
    </script>
</body>
</html>
