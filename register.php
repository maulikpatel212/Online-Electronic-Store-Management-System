<?php
//require('connection.inc.php');
require('functions.inc.php');
session_start();
$error ='';

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'previous_database';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
?>

<?php
if(isset($_POST['submit']))
{
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $count = mysqli_num_rows(mysqli_query($con,"SELECT EMAIL FROM USERS WHERE EMAIL='$email'"));
    if($count>0){
        echo '<script>alert("User Exist")</script>';
        exit();
    }
    else{
        $sql = "INSERT into users(first_name,middle_name,last_name,contact,email,password) values('$first_name','$middle_name','$last_name','$contact','$email','$password')"; 
        $res = mysqli_query($con,$sql);

        if (mysqli_query($con,$sql)){
            echo '<script>alert("Registration Successful!! You Can Login Now")</script>';
            header("Refresh:0; url=index.php");
        }
        else{
            $error = mysqli_error($con);
            // If there is an error with the connection, stop the script and display the error.
        }
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="register_style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="register">
        <h1>Register</h1>
        <form  action=""  method="post" autocomplete="off">
            <label for="first_name">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="first_name" id="first_name" placeholder="First Name" required>

            <label for="middle_name">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name" required>

            <label for="last_name">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="last_name" id="last_name" placeholder="Last Name" required>

            <label for="contact">
                <i class="fas fa-phone"></i>
            </label>
            <input type="text" name="contact" id="contact" placeholder="Mobile" required>

            <label for="email">
                <i class="fas fa-envelope"></i>
            </label>
            <input type="text" name="email" placeholder="Email" id="Email" required>


            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" required>

            <input type="submit" value="Register", name="submit">

            <?php echo $error?>
    
        </form>
    </div>
</body>
</html>