<?php
require_once('config.php');
session_start();

if (!isset($_SESSION['username'])) {
 
    exit;
}
if (!empty($_POST) && isset($_POST['addsv'])) {
   
    $name = htmlspecialchars($_POST['name']);
    $class    = (int)htmlspecialchars($_POST['class']);
    $birthday = htmlspecialchars($_POST['birthday']);
    $gender = htmlspecialchars($_POST['gender']);
    $subject = $_POST['subject'];
   


    try {
        $sql = sprintf("insert into students values(null,'%s', '%s' , '%s' , '%s' )", $name, $birthday, $gender, $class);
        //echo $sql;
        $conn->query($sql);
        $id_std = $conn->insert_id;

        foreach ($subject as $values => $subj) {

            $sql = sprintf("insert into studentsubject values (null, %d, %d)", (int)$id_std, (int)$subj);
            $result = $conn->query($sql);
        }
        if ($result === TRUE) {
            $sql = "SELECT  students.*, class.name  as className,GROUP_CONCAT(subjects.name) as SubjectsName from students INNER JOIN class on students.classid = class.ID inner join studentsubject on students.studentid = studentsubject.studentID inner join subjects on studentsubject.SubjectID = subjects.ID group by students.studentid LIMIT 0, 25;";
            $result = $conn->query($sql);
            $arr_sv = $result->fetch_all(MYSQLI_ASSOC);;

            $html = '';

            foreach ($arr_sv as  $key => $sv) {
                $html .=  '<tr>';
                $html .=  '<td>' . $sv["name"] . '</td>';
                $html .= '<td>' . $sv["className"] . '</td>';
                $html .= '<td>' . $sv["birthday"] . '</td>';
                $html .= '<td>' . $sv["gender"] . '</td>';
                $html .= '<td>' . $sv["SubjectsName"] . '</td>';
                $html .= '<td><a class="btn btn-warning" href="update.php?id=' . $sv['studentid'] . '">sua</a><button  class="btn btn-danger" onclick="xoasv(event,' . $sv["studentid"] . ')">Xoa</button></td>';
                $html .= '</tr>';
            }
            $return = array
            ('status' =>'success',
            'message' =>'thêm mới thành công',
            'html' => $html
        );
        echo json_encode($return);
        } else{
            echo 'error';
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
