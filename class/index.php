<?php
require_once('../config.php');
session_start();
if (isset($_SESSION['username'])) {
} else {
    header('Location:../index.php');
}

if (isset($_POST['addclass'])) {
    $newclass = htmlspecialchars($_POST['addclass']);
    echo var_dump($newclass);
    $sql = "INSERT INTO class VALUES(null,'$newclass')";
    if ($conn->query($sql) == true) {
        echo "insert thành công";
        header("location: ds_class.php");
    } else {
        echo  var_dump($conn->error);
    }
    //
    $sql = "SELECT ID,name FROM subjects";
    $result = $conn->query($sql);
 
    $result_arr_subjects = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $result_arr_subjects[]  = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<style>

</style>

<body class="container">
    <h3 class="text-center">Thêm Lớp</h3>
    <form class="form" method="POST">
        <div class="row mb-4">
            <label class="col-2 ">Lớp Học</label>
            <input name="addclass" class=" col-10 form-control" type="text">
        </div>
        <button class="btn btn-primary col-12 ">Thêm Lớp</button>
    </form>
</body>

</html>