<?php
session_start();

if (isset($_GET['back'])) {
    session_unset();
    header('location: index.php');
}

if (!empty($_GET)) {

    $username = $_GET['username'];
    $password  = $_GET['password'];
    $fullname = $_GET['fullname'];
    $email = $_GET['email'];
    $phone = $_GET['phone'];
    if ($username != "" &&  $password != "" &&  $fullname != "" && $email != "" && $phone != "") {
        $query = 'insert into users(username,password,fullname,email,phone) values("' . $username . '","' . $password . '","' . $fullname . '","' . $email . '","' . $phone . '")';

        $conn = new mysqli("localhost","root" , '', 'std_management2');

        if ($conn->connect_error) {
            echo $conn->connect_error;
        }
       
        if( $conn->query($query)==true){
            echo "<script type='text/javascript'>alert('Bạn đã đăng kí thành công');</script>";
        }
        $conn->close();
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
<style>
    body {
        max-width: 100%;
        background-color: #e8ecef;
    }

    .container {

        margin: 0 auto;
        background-color: rgb(170 181 205 / 50%);
    }

    Form h3 {
        text-align: center;

    }

    Form {
        padding: 50px 0px;

        background-color: #ffffff;
        max-width: 500px;
        margin: 10px auto;

    }

    header {
        max-width: 600px;
        margin: 0px;
        display: flex;
        justify-content: space-between;
        text-align: center;

    }

    header ul {
        display: flex;
        flex-wrap: wrap;
        align-items: center;


    }

    .header_wrapper li {
        margin-right: 2px;
        list-style: none;
    }

    .header_wrapper li {
        color: black;
        text-transform: capitalize;
        padding: 10px;
        font-size: 1.2rem;
        transition: all 0.4s;

    }

    .header_wrapper li:hover {
        color: #e7a9a9;
        transform: scale(1.1);
    }

    .input-wrap {
        display: flex;
        width: 100%;
        margin: 0 auto;
        margin-bottom: 10px;
    }

    .input-wrap label {
        width: 45%;
        text-align: right;
        margin-right: 2px;
    }

    header h3 {
        font-size: 1.8rem;
        color: hsl(208deg 71% 58% / 76%);
        text-transform: capitalize;
    }

    footer {

        max-width: 600px;
        margin: 0 auto;
    }

    footer p {
        background-color: #e7a9a9;
        padding: 15px 20px;
    }

    .input-register {
        text-align: center;
        text-transform: uppercase;
    }

    .input-register button {
        width: 70%;
        border: unset;
        background-color: #e7a9a9;
        padding: 10px;
        transition: all 0.6s;
        border-radius: 5px;
    }

    .input-register button:hover {
        background-color: #ac4444;
    }

    .input-register a {
        text-decoration: none;
        background-color: #e7a9a9;
        color: black;
        padding: 7px;
        border-radius: 2px;
    }
</style>

<body>
    <Header class="container">
        <h3>web888.Vn</h3>
        <div>
            <ul class="header_wrapper">
                <li>Home</li>
                <li>About</li>
                <li>Contact</li>
                <li>Telephone</li>

            </ul>
        </div>
    </Header>
    <Form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h3>Login Form</h3>
        <div class="input-wrap">
            <label>UserName</label>
            <input type="text" name="username">

        </div>
        <?php
        if (isset($errors['username'])) {
            echo '<p style="color:red">' . $errors['username'] . '</p>';
        }
        if (isset($errors['username_length'])) {
            echo '<p style="color:red">' . $errors['username_length'] . '</p>';
        }
        if (isset($errors['username_reg'])) {
            echo '<p style="color:red">' . $errors['username_reg'] . '</p>';
        }
        ?>
        <div class="input-wrap">
            <label>Password</label>
            <input type="password" name="password" required>

        </div>
        <div class="input-wrap">
            <label for="">FullName:</label>
            <input type="text" name="fullname" required>
        </div>
        <div class="input-wrap">
            <label for="">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-wrap">
            <label>Phone:</label>
            <input type="number" name="phone" required>
        </div>
        <?php
        if (isset($errors['password'])) {
            echo '<p style="color:red">' . $errors['password'] . '</p>';
        }
        if (isset($errors['password_length'])) {
            echo '<p style="color:red">' . $errors['password_reg'] . '</p>';
        }
        ?>


        <div class="input-register">
            <button>register</button>
            <a href="?back">back</a>
        </div>
    </Form>
    <footer>
        <p>@copyright web888.vn</p>
    </footer>
</body>


</html>