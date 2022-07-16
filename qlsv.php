<?php
require_once('config.php');
session_start();
if (isset($_SESSION['username'])) {
} else {
    header('Location:index.php');
}

// quêry data ra bảng qlsv
$sql = "SELECT  students.*, class.name  as className,GROUP_CONCAT(subjects.name) as SubjectsName from students INNER JOIN class on students.classid = class.ID inner join studentsubject on students.studentid = studentsubject.studentID inner join subjects on studentsubject.SubjectID = subjects.ID group by students.studentid LIMIT 0, 25;";






$result = $conn->query($sql);
$arr_sv = $result->fetch_all(MYSQLI_ASSOC);

if (!empty($_POST) && isset($_POST['addsv'])) {
    $name = htmlspecialchars($_POST['name']);
    $class    = (int)htmlspecialchars($_POST['class']);
    $birthday = htmlspecialchars($_POST['birthday']);
    $gender = htmlspecialchars($_POST['gender']);
    $subject = $_POST['subject'];
    // if (is_array($subject)) {
    //     $subject = htmlspecialchars(implode(",", $subject));
    // }


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
            echo "Thêm mới thành công";
        } else {
            echo $conn->error;
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

    // header('location: qlsv.php');
}
if (isset($_GET['deletesv'])) {
    //var_dump("chay vao day");
    // $key = (int)$_GET['deletesv'];
    // unset($arr_sv[$key]);
    $id_sv = $_GET['idstudent'];
    $sql = sprintf("delete from studentsubject where studentid = %d", $id_sv);
    $conn->query($sql);
    $sql = sprintf("delete from students where id = %d", $id_sv);
    $conn->query($sql);
    header('location: qlsv.php');
    // var_dump($id_sv);
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('location index.php');
}
// handeling subjects
$sql = "select ID,name FROM Class ";
$result = $conn->query($sql);
$result_arr = [];
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $result_arr[]  = $row;
}


// handeling subjects
$sql = "select ID,name FROM Subjects";
$result = $conn->query($sql);
$result_arr_subjects = [];
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $result_arr_subjects[]  = $row;
}
// thêm sv


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
<!--jquery-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
    .form-wrap {
        max-width: 700px;
        margin: auto;
        background-color: #f1f1f1;
        box-sizing: border-box;
        border-radius: 20px;
        padding: 10px
    }

    .flex-wrap {
        display: flex;
        flex-wrap: wrap;
        padding: 5px;

    }



    .flex-wrap label {
        width: 20%;
    }

    .input-wrap {
        width: 80%;
    }

    table td,
    table th {
        border: 1px solid #5f4362;


    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    .logout {
        text-decoration: none;
        color: white;
        background-color: #6262fe;
        padding: 10px;
        border-radius: 19px;
        text-transform: capitalize;
        transition: all 0.3s;

    }

    .logout:hover {
        background-color: #8650a9;
    }
</style>

<body>


    <!-- Modal -->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm Sinh Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <form onsubmit="addsvajax(event)" method="POST">
                        <h3 class="text-center">
                            Quản Lí SV
                        </h3>
                        <div class="flex-wrap ">
                            <label for="name">Tên SV:</label>
                            <div class="input-wrap">
                                <input type="text" name="name" id="" required>
                            </div>
                        </div>
                        <div class="flex-wrap">
                            <label for="namsinh">Năm sinh</label>
                            <div class="input-wrap">
                                <input type="date" name="birthday" id="" required>
                            </div>
                        </div>
                        <div class="flex-wrap">
                            <label for="gioitinh">Giới tính</label>
                            <div class="input-wrap">
                                <input type="radio" name="gender" id="" value="male"> <label>Male</label>
                                <input type="radio" name="gender" id="" value="female"> <label>Female</label>
                            </div>
                        </div>
                        <div class="flex-wrap">
                            <label for="subject">Môn Học:</label>
                            <div class="input-wrap">
                                <div id="checkbox_wrap">
                                    <?php foreach ($result_arr_subjects as $values => $key) : ?>
                                        <input type="checkbox" name="subject[]" value="<?php echo $key["ID"]; ?>;"> <label><?php echo $key["name"]; ?></label>

                                    <?php endforeach ?>

                                </div>

                            </div>
                        </div>
                        <div class="flex-wrap">
                            <label>Lớp</label>
                            <div class="input-wrap">
                                <select name="class" id="select-wrap">

                                    <?php foreach ($result_arr as $values => $key) : ?>
                                        <option value="<?php echo $key["ID"] ?>"><?php echo $key["name"]; ?> </option>
                                    <?php endforeach ?>
                                </select>
                                <button name="class_add" onclick="location.href='./class/index.php'" type="button" class="btn btn-primary">Thêm Lớp</button>
                            </div>
                        </div>
                        <div class="flex-wrap">
                            <input class="btn btn-primary" type="submit" name="addsv" value="Thêm Sinh Viên">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->


    <!-- Modal subject-->
    <div class="modal fade" id="exampleModalsubject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="ajaxsubjects(event)" class="form" method="POST">
                        <h3 class="text-center">Thêm Môn</h3>
                        <div class="row ">
                            <div class="col-sm-12">
                                <label>Môn Học</label>
                                <input name="newsubjects" class=" form-control" type="text">
                                <button type="submit" name="addsubjects" class="btn btn-primary col-12 ">Thêm Môn</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL UPDATE-->



    <!-- Modal  -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Sửa<h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-wrap">

                        <form method="POST" onsubmit="ajaxupdate(event)">
                            <input type="number" readonly value="<?php echo $arr_update['studentid'] ?>" name="sv_id" hidden />
                            <h3 class="text-center">
                                Quản Lí SV
                            </h3>
                            <div class="flex-wrap ">
                                <label for="name">Tên SV:</label>
                                <div class="input-wrap">
                                    <input type="text" id="name" name="name" value="<?php echo $arr_update["name"]; ?>" required>
                                </div>
                            </div>
                            <div class="flex-wrap">
                                <label for="namsinh">Năm sinh</label>
                                <div class="input-wrap">
                                    <input id="birthday" type="date" value="<?php echo $formatDate; ?>" name="birthday" required>
                                </div>
                            </div>
                            <div class="flex-wrap">
                                <label for="gioitinh">Giới tính</label>
                                <div class="input-wrap">
                                    <input type="radio" name="gender" value="male"> <label>Male</label>
                                    <input type="radio" name="gender" value="female"> <label>Female</label>
                                </div>
                            </div>
                            <div class="flex-wrap">
                                <label for="subject">Môn Học :</label>
                                <div class="input-wrap">
                                    <div id="checkbox_wrap">
                                        <?php foreach ($result_arr_subjects as $values => $key) : ?>
                                            <?php
                                            $checked = '';
                                            // foreach ($subject_select as $values2 => $key2) {
                                            //     if ($key['ID'] == $key2['SubjectID']) {
                                            //         $checked = 'checked';
                                            //     }
                                            // }
                                            ?>
                                            <input type="checkbox" <?php echo $checked ?> name="subject[]" value="<?php echo $key["ID"]; ?>;"> <label><?php echo $key["name"]; ?></label>

                                        <?php endforeach ?>

                                    </div>

                                </div>
                            </div>
                            <div class="flex-wrap">
                                <label>Lớp</label>
                                <div class="input-wrap">
                                    <select name="class" id="select-wrap">

                                        <?php foreach ($result_arr as $values => $key) : ?>
                                            <!-- <?php $selected = ($key['ID'] == $arr_update['classid']) ? 'selected' : ''; ?> -->

                                            <!-- <option value="<?php echo $key["ID"] ?>" <?php echo $selected; ?>><?php echo $key["name"]; ?> </option> -->
                                            <option value="<?php echo $key["ID"] ?>"><?php echo $key["name"]; ?> </option>

                                        <?php endforeach ?>
                                    </select>

                                </div>
                            </div>
                            <div class="flex-wrap">
                                <input class="btn btn-primary" type="submit" name="suasv" value="Sửa Sinh Viên">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="subjects">

        <div class="row">
            <div class="col-sm-12">
                <h3>QLSV</h3>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Thêm Sinh Viên
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalsubject">
                    Thêm Môn
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Update sv
                </button>
                <a class="btn btn-primary" href="?logout">Logout</a>
                <form style="display: none;" name="formdelete">
                    <input type=text name="deletesv" />
                    <input type="number" name="idstudent" />
                </form>

                <table class="table table-primary  table-hover">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Lớp </th>
                            <th>Năm Sinh</th>
                            <th>Giới tính</th>
                            <th>Môn Học</th>
                            <th>Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($arr_sv as  $key => $sv) {
                            // echo var_dump($sv);
                            echo '<tr id=' . $sv['studentid'] . '>';
                            echo '<td data-target="name">' . $sv["name"] . '</td>';
                            echo '<td data-target="className">' . $sv["className"] . '</td>';
                            echo '<td data-target="birthday">' . $sv["birthday"] . '</td>';
                            echo '<td data-target="gender">' . $sv["gender"] . '</td>';
                            echo '<td data-target="SubjectsName">' . $sv["SubjectsName"] . '</td>';
                            echo '<td><a class="btn btn-warning" onclick="suasv(event,' . $sv["studentid"] . ')"  >sua</a><button  class="btn btn-danger" onclick="xoasv(event,' . $sv["studentid"] . ')">Xoa</button></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--subjects table-->
        <!-- <h5 id='subjects' class="text-center">Danh Sach Môn Học</h5>
        <table class="table table-hover table-primary text-center">
            <thead>
                <th>ID</th>
                <TH>name</TH>
            </thead>
            <tbody>
                <?php foreach ($result_arr_subjects as $values => $key) : ?>
                    <tr>
                        <td><?php echo $key['ID']; ?></td>
                        <td> <?php echo $key['name'] ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>

        </table> -->


    </div>
</body>
<script>
    function xoasv(event, key) {
        event.preventDefault();
        // console.log();
        let cf = confirm("Ban chac chan muon xoa khong");
        console.log(cf);
        if (cf == true) {
            document.querySelector('[name="idstudent"]').value = key;
            let form = document.querySelector('[name="formdelete"]');
            console.log(form)
            form.submit();
        }
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }



    function suasv(event, key) {
        event.preventDefault();
        // console.log(key);
        var id = key;

        var name = $('#' + id).children('td[data-target=name]').text();
        var className = $('#' + id).children('td[data-target=className]').text();
        var birthday = $('#' + id).children('td[data-target=birthday]').text();
        var gender = $('#' + id).children('td[data-target=gender]').text();
        var SubjectsName = $('#' + id).children('td[data-target=SubjectsName]').text();
        $('#name').val(name);

        birthday = formatDate(birthday);
        $('#birthday').val(birthday);
        // gender
        const length_gender = document.querySelectorAll('input[name=gender]').length;

        for (let i = 0; i < length_gender; i++) {
            if (document.querySelectorAll('input[name=gender]')[i].value == gender) {
                // console.log(document.querySelectorAll('input[name=gender]')[i].value);
                document.querySelectorAll('input[name=gender]')[i].checked = true;
                document.querySelectorAll('input[name=gender]')[i].checked;
            }
        }

        const leng_subjects = document.querySelectorAll('input[name=subject]').length;
        // console.log(leng_subjects);
        console.log(SubjectsName);

        // for (let i = 0; i < leng_subjects; i++) {
        //     if (document.querySelectorAll('input[name=subject]')[i].value == SubjectsName) {


        //         document.querySelectorAll('input[name=subject]')[i].checked =true;
        //     }
        // }
        

        $('#staticBackdrop').modal('toggle');

    }

    function addsvajax(event) {
        event.preventDefault();
        let form = event.target;
        let formdata = new FormData(form);
        formdata.append('addsv', true);
        $.ajax({
                url: 'addajax.php',
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        alert('data.massage');
                    }
                    $('table tbody ').html(data.html);
                    var myModal = $('#exampleModal');
                    document.querySelector('.btn-close').click();

                },
                error: function(data) {
                    console.log(data);
                }
            }

        )
    }

    function ajaxsubjects(event) {
        event.preventDefault();
        let form = event.target;
        let formdata = new FormData(form);
        formdata.append('addsubjects', true);
        $.ajax({
            url: 'subjectajax.php',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data) {
                data = JSON.parse(data);

                if (data.status == 'success') {
                    alert('data.massage');
                }
                $('#subjects>table>tbody ').html(data.html);
                document.querySelector('#exampleModalLabel > .btn-close').click();
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
</script>

</html>