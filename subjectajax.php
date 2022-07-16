<?php
require_once('config.php');
session_start();
// echo var_dump($_POST);

if (!isset($_SESSION['username'])) {
    exit;
}

if (isset($_POST['newsubjects'])) {
    $newsubject = htmlspecialchars($_POST['newsubjects']);

    $sql = "INSERT INTO subjects VALUES(null,'$newsubject')";
    if ($conn->query($sql) == true) {
        // echo "insert thành công";
        // header("location: ds_class.php");
        $last_id = $conn->insert_id;
    } else {
        echo  var_dump($conn->error);
    }
    // subjects
    // $sql = "SELECT name FROM subjects where ID = $last_id  ";
    // $result = $conn->query($sql);
    // $arr_subjects = $result->fetch_assoc();

    // echo var_dump($arr_subjects['name']);
    $html  = '';


    // $html .= '<tr>' . '<td>' . $last_id . '</td>' . '<td>' . $arr_subjects['name'] . '</td>' . '</tr>';

    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        // echo "âấy thành công";
    } else {
        echo "lỗi";
    }
    $result_arr_subjects = [];
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $result_arr_subjects[]  = $row;
    }
    foreach ($result_arr_subjects as $values => $key) {
        $html .= '<tr>';
        $html .= '<td>' . $key['ID'] . '</td>';
        $html .= '<td>' . $key['name'] . '</td>';
        $html .= '</tr>';
    }
    $return = array(
        'status' => 'success',
        'message' => 'thêm mới thành công',
        'html' => $html
    );
    echo json_encode($return);
}
