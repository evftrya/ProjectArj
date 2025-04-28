<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <form action="/RedAllNotif" method="POST">
            @csrf
            <input type="text" value="/Transaction/2">
            <input type="text" name="id" value="q@Q">
            <input type="password" name="pu" value="123">
            <INPut type="submit">SAVE</INPut>
        </form>
    </body>

    <script>
    </script>
</html>