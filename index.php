<?php
// todo
session_start();
// connect sql

require_once('config.php');
if (isset($_SESSION['username'])) {
   header('location: qlsv.php');
}
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
   if (isset($_POST['username']) && isset($_POST['password'])) {
      $username = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $_SESSION['username'] =  $username;
      $_SESSION['password'] = $password;
      if (empty($username)) {
         $errors['username'] = 'Username is required';
      }
      if (strlen($username) < 4) {
         $errors['username_length'] = 'Username must be at least 4 characters';
      }
      if (empty($password)) {
         $errors['password'] = 'Password is required';
      }
      // if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
      //    $errors['username_reg'] = "Only letters and white space allowed";
      // }

      if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,}$/", $password)) {
         $erros['password_reg'] = 'only digital and at least 4 characters';
      }
      // xu li 
      if (count($errors) == 0) {
         $escape_string_user = mysqli_escape_string($conn, $username);
         $escape_string_pass = mysqli_escape_string($conn, $password);

         //  echo var_dump($escape_string_user);
         $password_hash = sha1($escape_string_pass);
         $sql = sprintf("select * from users where username = '%s' and password = '%s'", $escape_string_user, $escape_string_pass);
         $result = $conn->query($sql);
         // echo '<pre>';
         // var_dump($result);
         // echo '</pre>';

         if ($result->num_rows > 0) {
            $_SESSION['username'] = $username;
            header('location:qlsv.php');
         } else {
            $errors['login_fail'] = 'Wrong username or password';
         }
      }

  
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
   <Form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
         <input type="password" name="password">

      </div>
      <?php
      if (isset($errors['password'])) {
         echo '<p style="color:red">' . $errors['password'] . '</p>';
      }
      if (isset($errors['password_length'])) {
         echo '<p style="color:red">' . $errors['password_reg'] . '</p>';
      }
      ?>

      <div class="input-wrap">
         <label></label>
         <input type="submit" name="submit">
         <input type="reset" value="Reset" />
      </div>
      <div class="input-register">
         <button type="button" onclick="register(event)">register</button>
      </div>
      <?php if (isset($errors['login_fail'])) : echo '<p style="color: red;">' . $errors['login_fail'] . '</p>';
      endif; ?>
   </Form>
   <footer>
      <p>@copyright tuyen.vn</p>
   </footer>
</body>

<script>
   function register(e) {
      e.preventDefault();
      window.location.href = "register.php";
   }
</script>

</html>