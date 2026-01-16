<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="get">
        <input type="text" name="text" id="">
        <button type="submit">but</button>
    </form>

</body>
</html>
<?php
if(isset($_GET['text'])){   
$massive_slov = explode(' ',$_GET['text']);
upFunc($massive_slov);
echo implode(' ',$massive_slov);
  
       }

function upFunc(&$massive_slov){
for($i=0; $i< count($massive_slov); $i++){
if(($i % 2)>0){
    $massive_slov [$i]=strtoupper($massive_slov[$i]);    

}
}
//var_dump($massive_slov);
}