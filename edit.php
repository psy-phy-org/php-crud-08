<?php session_start();
// including the database connection file
require_once('classes/Crud.php');
require_once('classes/Validation.php');

$crud = new Crud();
$validation = new Validation();
if (!$_SESSION) {
    // getting id from url
    $id = $_GET['id'];
    // selecting data associated with this particular id
    $rows = $crud->getData("SELECT * FROM products WHERE id=$id");
    foreach ($rows as $res) {
        $_SESSION['product'] = $res;
    }
}

if (isset($_POST['update'])) {
    $msg = $validation->checkEmpty($_POST, array('name', 'price', 'description'));
    $check_price = $validation->IsNumericValid($_POST['price']);
    $check_description = $validation->isMaxlengthValid($_POST['description']);
    // checking empty fields
    if ($msg != null) {
        $status = $msg;
        $_SESSION['product'] = $_POST;
    } elseif (! $check_price) {
        $status = 'Please provide proper price.';
        $_SESSION['product'] = $_POST;
    } elseif (! $check_description) {
        $status = 'Please provide proper description.';
        $_SESSION['product'] = $_POST;
    } else {
        // updating the table
        $sql = 'UPDATE products SET name=?, price=?, description=? WHERE id=?';
        $array = [
            $_POST['name'],
            $_POST['price'],
            $_POST['description'],
            $_SESSION['product']['id']
        ];
        $crud->executeSQL($sql, $array);

        $_SESSION = [];
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }
        session_destroy();
        // redirectig to the display page. In our case, it is index.php
        header('Location: index.php');
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
    <h1>Edit record</h1>
    <div><a href="index.php">Products list</a></div>
    <div style="color:red"><?= $status ?></div>
    <form action="edit.php" method="post" name="form1">
      <fieldset>
        <div>
          <label for="name">Product name: </label>
          <input type="text" id="name" name="name" value="<?= $crud->h($_SESSION['product']['name']) ?>"
              placeholder="Enter name" required>
        </div>
        <div>
          <label for="price">Price: </label>
          <input type="text" id="price" name="price" value="<?= $crud->h($_SESSION['product']['price']) ?>"
              placeholder="Enter price" required>
        </div>
        <div>
          <label for="description">Description: </label>
          <textarea name="description" rows="2"
              placeholder="Enter description" required><?= $crud->h($_SESSION['product']['description']) ?></textarea>
        </div>
        <div>
          <input type="hidden" name="id" value="<?= $crud->h($_GET['id']) ?>">
          <input type="submit" name="update" value="Update">
        </div>
      </fieldset>
    </form>
  </main>
</body>

</html>
