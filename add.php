<?php
// including the database connection file
require_once('classes/Crud.php');
require_once('classes/Validation.php');

$crud = new Crud();

if (isset($_POST['add'])) {
    // 連想配列で値を渡す
    $name = $_POST;

    // 評価内容を定義
    $conditions = [
        "name" => [
            "required" => true, // true->必須
            "max" => 20, // 最大文字数
            "min" => null, // 最小文字数
            "type" => null, // 許容形式（null / alphabet / numeric / alphabet&numeric / alphabet&numeric&symbols / email / url / tel）
            "disallowWhitespace" => true, // 空白文字を禁止するか
            "disallowZenkaku" => null, // 全角文字を禁止するか
        ],
        "price" => [
            "required" => true,
            "max" => 5,
            "min" => 2,
            "type" => "numeric",
            "disallowWhitespace" => null,
            "disallowZenkaku" => null,
        ],
        "description" => [
          "required" => true,
          "max" => 50,
          "min" => null,
          "type" => null,
          "disallowWhitespace" => null,
          "disallowZenkaku" => null,
        ],
    ];

    $validation = new Validation();
    $errors = $validation->check($name, $conditions);

    $name_error = $errors['name'];
    $price_error = $errors['price'];
    $description_error = $errors['description'];

    // var_dump($errors);
    // exit();

    // checking empty fields
    if (! $errors) {
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
              placeholder="Enter name">
        </div>
<?php
if ($name_error) {
    foreach ($name_error as $msg) {
        echo $msg."<br>";
    }
}
?>
        <div>
          <label for="price">Price: </label><br>
          <input type="text" id="price" name="price" value="<?= $crud->h($_POST['price']) ?>"
              placeholder="Enter price">
        </div>
<?php
if ($price_error) {
    foreach ($price_error as $msg) {
        echo $msg."<br>";
    }
}
?>
        <div>
          <label for="description">Description: </label><br>
          <textarea name="description" rows="2"
              placeholder="Enter description"><?= $crud->h($_POST['description']) ?></textarea>
        </div>
<?php
if ($description_error) {
    foreach ($description_error as $msg) {
        echo $msg."<br>";
    }
}
?>
        <div><input type="submit" name="add" value="Add"></div>
      </fieldset>
    </form>
  </main>
</body>

</html>
