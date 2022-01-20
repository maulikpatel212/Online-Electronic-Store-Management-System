<?php
require('top.inc.php');
$order_id = '';
$complaint_date = date('Y/m/d');
$complaint = '';
$admin_reply = '';

if ($_SESSION['loggedin']) {
	$customer_id = $_SESSION['id'];
} else {
	echo "<script> location.href='index.php'; </script>";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
	$id = get_safe_value($con, $_GET['order_id']);
	$res = mysqli_query($con, "select * from orders where order_id='$id' and customer_id='$customer_id'");
	// var_dump($res);
	$check = mysqli_num_rows($res);
	if ($check > 0) {
		$row = mysqli_fetch_assoc($res);
		$order_id = $row['order_id'];
	}
}

?>

<div class="content pb-0">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header" style="text-align:center"><strong>User Complaint</strong></div>
					<form method="post" enctype="multipart/form-data">
						<div class="card-body card-block">
							<div class="form-group">
								<label for="customer_id" class=" form-control-label">customer_id</label>
								<input type="text" name="customer_id" class="form-control" required value="<?php echo $customer_id ?>" readonly>
							</div>

							<div class="form-group">
								<label for="Bill" class=" form-control-label">Please select the order</label>
								<select class="form-control" name="order_id">
									<option>Select order_id</option>
									<?php
									$customer_id = $_SESSION['id'];
									$res = mysqli_query($con, "SELECT order_id FROM orders where customer_id='$customer_id'");
									while ($row = mysqli_fetch_assoc($res)) {
										    echo "<option value=" . $row['order_id'] . ">" .  $row['order_id'] . "</option>";										
									}
									?>
								</select>
							</div>

							<div class="single-contact-form">
								<div class="contact-box message">	
								<label for="complaint" class=" form-control-label">complaint ( Give us a brief description )</label>
								<textarea type="text" name="complaint" class="form-control" required></textarea>
							</div>
							</div>

							<div class="single-contact-form">
								<div class="contact-box message">	
								<label for="admin_reply" class=" form-control-label">Admin Reply</label>
								<textarea type="text" name="admin_reply" class="form-control" placeholder="You will show your Reply here When the Admin will get back to you!! "required value="<?php echo $admin_reply ?>" readonly></textarea>
							</div>
							</div>

							<div class="form-group">
								<label for="contact" class=" form-control-label">complaint_date</label>
								<input type="text" name="complaint_date" class="form-control" required value="<?php echo $complaint_date ?>" readonly>
							</div>

							<button id="payment-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
								<span id="payment-button-amount">Submit</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

if(isset($_POST['submit'])){
$order_id =  $_POST['order_id'];
$complaint = $_POST['complaint'];
$customer_id = $_SESSION['id'];
//var_dump($complaint);

$sql = "INSERT into complaints(customer_id,order_id,complaint,complaint_date) values('$customer_id','$order_id','$complaint','$complaint_date')";
$output = mysqli_query($con,$sql);
echo 'Complaint Sent Successfully';
}
?>