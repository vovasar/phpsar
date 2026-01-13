<?php require ('header.php');?>
<?php require ('BD.php');?>
<?php
$sql='SELECT * FROM `friends`';
$result = mysqli_query($mysqli,$sql);
if (mysqli_errno($mysqli)) echo mysqli_error();
?>

<div>
        <table>
<thead>
        <tr>
    <th>id</th>
    <th>first_name</th>
    <th>last_name</th>
    <th>name</th>
    <th>phone</th>
    <th>email</th>
    <th>adress</th>
    <th>user_id</th>
        </tr>

    </thead>
<?php while ($row= mysqli_fetch_assoc($result)):
    ?>
<tr>
    <th><?=$row['Id'];?></th>
    <th><?=$row['first_name'];?></th>
    <th><?=$row['last_name']; ?></th>
    <th><?=$row['name'];?></th>
    <th><?=$row['phone'];?></th>
    <th><?=$row ['email'];?></th>
    <th><?=$row ['adress'];?></th>
    <th><?=$row ['user_id'];?></th>
</tr>
<?php endwhile ?>

</table>
</div>
<?php require ('footer.html');?>
