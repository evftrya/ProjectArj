<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p class="time">Waktu Anda Tersisa: 08:04:25</p>
    <p >Segera Lakukan Pembayaran Sebelum Waktu Habis!</p>
    <form action="/payment" method="post" >
    @csrf
        <label for="">Id Transaksi</label>
        <select name="idTransaction" id="">
            <option value="0">Pilih Id Transaksi</option>
            @foreach($data as $d)
            <option value="{{{$d->id}}}">{{{$d->id}}}</option>
            @endforeach
        </select>
        <div>
            <p>Metode Pembayaran :</p>
            <p>BCA</p>
        </div>
        <div>
            <p>Detil Pembelian</p>
        </div>
        <input type="submit" value="Bayar">
        
    </form>
</body>

<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        p {
            margin: 10px 0;
            font-size: 16px;
            color: #333333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555555;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
        }

        div {
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .header {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #4caf50;
            font-weight: bold;
        }

        .time {
            font-size: 20px;
            color: #ff5722;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</html>