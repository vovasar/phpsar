<?php require ('header.php');?>
<?php require ('BD.php');?>
<?php
$sql='SELECT * FROM `user`';
$result = mysqli_query($mysqli,$sql);
if (mysqli_errno($mysqli)) echo mysqli_error();
?>

<div>
        <table>
<thead>
<tr>
    <th>id</th>
    <th>user_name</th>
    <th>email</th>
    <th>role</th>
    <th>присвоить роль читателя<th>



</tr>


    </thead>
<?php while ($row= mysqli_fetch_assoc($result)):
    ?>
<tr>
    <th><?=$row['id'];?></th>
    <th><?=$row['user_name'];?></th>
    <th><?=$row['email']; ?></th>
    <th><?=$row['role'];?></th>
    <th><a href="query.php?role=<?=$row['id'];?>">OK</a></th>
</tr>
<?php endwhile ?>

</table>
</div>
<?php require ('footer.html');

?>