<?php
require('top.inc.php');
$msg = "";

if ($_SESSION['loggedin']) {
	$customer_id = $_SESSION['id'];
} else {
	echo "<script> location.href='index.php'; </script>";
}
$result = mysqli_query($con, "SELECT customer_id,first_name,middle_name,last_name,contact,email,password from users where customer_id = '$customer_id'");
$check = mysqli_num_rows($result);

if ($check > 0) {
	$row = $result->fetch_assoc();
	$first_name = $row['first_name'];
	$middle_name = $row['middle_name'];
	$last_name = $row['last_name'];
	$contact = $row['contact'];
	$email = $row['email'];
	$password = $row['password'];
} else {
	header('location:index.php');
	die();
}

if (isset($_POST['update'])) {
	if ($_POST['first_name'] == "" || $_POST['middle_name'] == "" || $_POST['last_name'] == "" || $_POST['contact'] == "" || $_POST['email'] == "" || $_POST['password'] == "") {
		$passmsg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fileds </div>';
	} else {
		$first_name = $_POST['first_name'];
		$middle_name = $_POST['middle_name'];
		$last_name = $_POST['last_name'];
		$contact = $_POST['contact'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		// $sql = "UPDATE users set first_name = '$first_name',middle_name='$middle_name',last_name='$last_name',contact='$contact',email='$email',`password`='$password' where customer_id = '$customer_id'";
		$sql = "CALL update_userprofile('$first_name','$middle_name', '$last_name', '$contact', '$email', '$password', '$customer_id' )";
		if ($con->query($sql) == TRUE) {
			$passmsg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
		} else {
			$passmsg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
		}
	}
}
?>
<div class="content pb-0">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header" style="text-align:center"><strong>User Profile</strong></div>
					<form method="post" enctype="multipart/form-data">
						<div class="card-body card-block">
							<div class="form-group">
								<label for="first_name" class=" form-control-label">First Name</label>
								<input type="text" name="first_name" class="form-control" required value="<?php echo $first_name ?>">
							</div>

							<div class="form-group">
								<label for="middle_name" class=" form-control-label">Middle Name</label>
								<input type="text" name="middle_name" class="form-control" required value="<?php echo $middle_name ?>">
							</div>

							<div class="form-group">
								<label for="last_name" class=" form-control-label">Last Name</label>
								<input type="text" name="last_name" class="form-control" required value="<?php echo $last_name ?>">
							</div>

							<div class="form-group">
								<label for="contact" class=" form-control-label">Contact</label>
								<input type="text" name="contact" class="form-control" required value="<?php echo $contact ?>">
							</div>

							<div class="form-group">
								<label for="email" class=" form-control-label">Email</label>
								<input type="text" name="email" class="form-control" required value="<?php echo $email ?>" readonly>
							</div>

							<div class="form-group">
								<label for="password" class=" form-control-label">Password</label>
								<input type="text" name="password" class="form-control" required value="<?php echo $password ?>">
							</div>

							<button id="payment-button" name="update" type="submit" class="btn btn-lg btn-info btn-block">
								<span id="payment-button-amount">update</span>
							</button>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
require('footer.inc.php');
?>