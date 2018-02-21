<?php
// including the database connection file
require_once('classes/Crud.php');

$crud = new Crud();

// getting id of the data from url
$id = $_GET['id'];

// deleting the row from table
$sth = $crud->delete($_GET['id'], 'products');

if ($sth) {
    // redirecting to the display page (index.php in our case)
    header('Location:index.php');
    exit();
}
