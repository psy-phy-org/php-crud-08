<?php session_start();
// including the database dbh file
require_once('classes/Crud.php');

$crud = new Crud();

if ($_SESSION) {
    $_SESSION = [];
    if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    session_destroy();
}

// fetching data in descending order (lastest entry first)
$sql = 'SELECT * FROM products ORDER BY id DESC';
$rows = $crud->getData($sql);
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
    <h1>Product list</h1>
    <div><a href="add.php">Add product</a></div>
    <table>
      <thead>
        <tr>
          <th>Product name</th>
          <th>Price</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
<?php
if ($rows) :
    foreach ($rows as $res) :
?>
        <tr>
          <td><?= $crud->h($res['name']) ?></td>
          <td><?= $crud->h($res['price']) ?></td>
          <td><?= $crud->h($res['description']) ?></td>
          <td><a href="edit.php?id=<?= $crud->h($res[id]) ?>">Edit</a> | <a href="delete.php?id=<?= $crud->h($res[id]) ?>"
              onClick="return confirm('Are you sure you want to delete?')">Delete</a></td>
        </tr>
<?php
    endforeach;
endif;
?>
      <tbody>
    </table>
  </main>
</body>

</html>
