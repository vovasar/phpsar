<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="get">
        <input type="text" name="result" id="">
        <button type="submit">do</button>
</body>
</html>
<?php
$XVI="Иван Васильевич";
$XVIII="Петр Алексеевич";
$XIX="Николай Павлович";
if(isset($_GET['result'])){
$vek=$_GET['result'];
echo'B ' . $vek . ' веке царствовал ' . $$vek; 

}
?>