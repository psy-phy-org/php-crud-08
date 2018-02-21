<?php
// including the database connection file
require_once('classes/Crud.php');
require_once('classes/Validation.php');

$crud = new Crud();
$validation = new Validation();

if (isset($_POST['add'])) {
    $msg = $validation->checkEmpty($_POST, array('name', 'price', 'description'));
    $check_price = $validation->isNumericValid($_POST['price']);
    $check_description = $validation->isMaxlengthValid($_POST['description']);
    // checking empty fields
    if ($msg != null) {
        $status = $msg;
    } elseif (! $check_price) {
        $status = 'Please provide proper price.';
    } elseif (! $check_description) {
        $status = 'Please provide proper description.';
    } else {
        // if all the fields are filled (not empty)
        // insert data to database
        $sql = 'INSERT INTO products VALUES(?,?,?,?)';
        $array = array($_POST['id'],$_POST['name'],$_POST['price'],$_POST['description']);
        // parent::executeSQL($sql, $array);
        $crud->executeSQL($sql, $array);
        // redirectig to the display page. In our case, it is index.php
        header('location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>PHP OOP CRUD</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <main class="container">
    <h1>Add product</h1>
    <div><a href="index.php">Products list</a></div>
    <div style="color:red"><?= $status ?></div>
    <form action="add.php" method="post" name="form1">
      <fieldset>
        <div>
          <label for="name">Product name: </label><br>
          <input type="text" id="name" name="name" value="<?= $crud->h($_POST['name']) ?>"
              placeholder="Enter name" required>
        </div>
        <div>
          <label for="price">Price: </label><br>
          <input type="text" id="price" name="price" value="<?= $crud->h($_POST['price']) ?>"
              placeholder="Enter price" required>
        </div>
        <div>
          <label for="description">Description: </label><br>
          <textarea name="description" rows="2"
              placeholder="Enter description" required><?= $crud->h($_POST['description']) ?></textarea>
        </div>
        <div><input type="submit" name="add" value="Add"></div>
      </fieldset>
    </form>
  </main>
</body>

</html>
