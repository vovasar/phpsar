<?php require ('header.php');?>

<form action="query.php" method="post">
        <div class="container">
            <h1>CREATE FRIEND</h1>
        <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Имя </label>
    <input name="name" type="text" class="form-control" id="first_name">
    <label for="exampleInputEmail1" class="form-label">Фамилия</label>
    <input name="first_name" type="text" class="form-control" id="last_name">
    <label for="exampleInputEmail1" class="form-label">Отчество </label>
    <input name="last_name" type="text" class="form-control" id="first_name">
    <label for="exampleInputEmail1" class="form-label">Номер_тел</label>
    <input name="phone" type="tel" class="form-control" id="phone">
    <label for="exampleInputEmail1" class="form-label">EMAIL</label>
    <input name="email" type="email" class="form-control" id="email">
    <label for="exampleInputEmail1" class="form-label">Адрес</label>
    <input name="adress" type="text" class="form-control" id="adress">
    <button type="submit" class="btn btn-primary">Готово</button>
</form>

<?php require ('footer.html');?>