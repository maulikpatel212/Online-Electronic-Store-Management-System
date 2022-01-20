<?php
require('top.inc.php');
$msg = "";

if ($_SESSION['loggedin']) {
	$customer_id = $_SESSION['id'];
} else {
	echo "<script> location.href='index.php'; </script>";
}
$result = mysqli_query($con, "SELECT customer_id,email,password from users where customer_id = '$customer_id'");
$check = mysqli_num_rows($result);

if ($check > 0) {
	$row = $result->fetch_assoc();
    $email = $row['email'];
	$password = $row['password'];
} else {
	header('location:index.php');
	die();
}

if (isset($_POST['update'])) {
	if ($_POST['email'] == "" || $_POST['password'] == "") {
		$passmsg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fileds </div>';
	} else {
		$password = $_POST['password'];
		$sql = "UPDATE users set password = '$password' where customer_id = '$customer_id'";

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
					<div class="card-header" style="text-align:center"><strong>Change Password</strong></div>
					<form method="post" enctype="multipart/form-data">
						<div class="card-body card-block">

							<div class="form-group">
								<label for="email" class=" form-control-label">Email</label>
								<input type="text" name="email" class="form-control" required value="<?php echo $email ?>" readonly>
							</div>

							<div class="form-group">
								<label for="password" class=" form-control-label">Password</label>
								<input type="text" name="password" class="form-control" required value="<?php echo $password ?>">
							</div>

							<button id="payment-button" name="update" type="submit" class="btn btn-lg btn-info btn-block">
								<span id="payment-button-amount">Change</span>
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