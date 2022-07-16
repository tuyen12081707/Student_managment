<?php
require_once('config.php');

session_start();
if (!isset($_SESSION['username'])) {
    exit;
}
if (!empty($_POST) && isset($_POST['suasv'])) {
    $studentid = (int)$_POST['sv_id'];
    $name = htmlspecialchars($_POST['name']);
    $classid   = (int)htmlspecialchars($_POST['class']);
    $birthday = htmlspecialchars($_POST['birthday']);
    $gender = htmlspecialchars($_POST['gender']);
    $subject = $_POST['subject'];


    $sql = sprintf("update students set name = '%s', birthday = '%s', classid = %d, gender='%s' where studentid = %d", $name, $birthday, $classid, $gender, $studentid);
    $conn->query($sql);
    $sql = sprintf("delete from studentsubject where studentid = %d", $studentid);
    $conn->query($sql);
    foreach ($subject as $key => $subj) {
        $sql = sprintf("insert into studentsubject values (null, %d, %d)", $studentid, $subj);
        $conn->query($sql);
    }
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
    $return = array(
        'status' => 'success',
        'message' => 'cập nhật  thành công',
        'html' => $html
    );
    echo json_encode($return);
}
