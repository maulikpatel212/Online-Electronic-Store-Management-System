<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link href="login_style.css" rel="stylesheet" type="text/css">
	
	<style>
		a:link {
			color: green;
			background-color: transparent;
			text-decoration: none;
		}
		a:hover {
			color: red;
			background-color: transparent;
			text-decoration: underline;
		}
	</style>
</head>

<body>
	<div class="login">
		<h1>Login</h1>
		<form action="authenticate.php" method="post">
			<label for="email">
				<i class="fas fa-user"></i>
			</label>
			<input type="text" name="email" placeholder="Email" id="Email" required>
			<label for="password">
				<i class="fas fa-lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<input type="submit" value="Login">
			<!--<p style="text-align:right" style="color:red">Don't have an account? Create Now</p>-->
			<!--<p style="font-family:verdana" style="text-align:right">Don't have an account? Create Now</p>-->
			<p style="margin-left:5em">Don'have an account &nbsp;<a href="register.php" target="_blank">Create One</a></p>
		</form>
	</div>
</body>
</html>

<?php
if (!isset($_POST['email'],$_POST['password'])) {
	// Could not get the data that should have been sent.
	exit();
}
// $sql = "SELECT email, password from users";
// $query = mysqli_query($con,$sql);
// // while($res = mysqli_fetch_assoc($query)){
// if($_POST['email'] = email and $_POST['password'] = $res['password']){
// 	session_regenerate_id();
// 	$_SESSION['loggedin'] = TRUE;
// 	$_SESSION['email'] = $_POST['email'];
// 	$_SESSION['id'] = $customer_id;
// 	exit();
// }
// // }
// echo 'Incorrect username and/or password!';


//
if ($stmt = $con->prepare('SELECT customer_id, password FROM users WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database or not.
	$stmt->store_result();
	$stmt->close();
}
if ($stmt->num_rows > 0) {
	$stmt->bind_result($customer_id, $password);
	$stmt->fetch();

	// Account exists, now we verify the password.
	// Note: remember to use password_hash in your registration file to store the hashed passwords.

	if ($_POST['password'] === $password) {
		// Verification success! User has logged-in!
		// Create sessions, so we know the user is logged in.
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['id'] = $customer_id;
		//echo 'Welcome ' . $_SESSION['email'] . '!';
	} else {
		// Incorrect password
		echo 'Incorrect username and/or password!';
	}
} else {
	echo 'Incorrect username and/or password!';
}

?>