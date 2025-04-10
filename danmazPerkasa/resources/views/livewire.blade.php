<!DOCTYPE html>
<html lang="en">
<head>
    <title>Halaman Awal</title>
</head>
<body>
<button onclick="loadContent()">Tampilkan Konten dari /</button>

    <div id="dynamic-content">
        <form action="/AddToCart/1" method="POST">
            @csrf
            <input type="text" name="qty">
            <input type="submit" >
        </form>
    </div>
    <script>
    </script>
</body>
</html>
