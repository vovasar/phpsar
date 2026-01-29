<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <form action="index.php" method="get">
        <label for="primer"></label>
        <input type="text" name="reshi" id="reshi">
    <button type="submit">сделать_пируэт</button>
    </form>
    </main>
</body>
</html>
<?php
if (isset ($_GET ['reshi']) && !empty($_GET['reshi'])){
$reshi = $_GET['reshi'];
echo "<b>$reshi</b>";
$arr = explode (' ', $reshi);
var_dump($arr); 
}