<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
    <form action="/cekLogin/Login" method="POST">
    @csrf
    <input type="checkbox">
    <!-- <input type="text" name="qty" value="8">

    <input type="submit"> -->

    <input type="email" name="el" placeholder="" id="inputField">
    <input type="password" name="pu" id="ThePassword" placeholder="">
    <input type="submit">
    </form>

    <a href="/UpdateStatus/4/1">tes</a>
    </body>
</html>