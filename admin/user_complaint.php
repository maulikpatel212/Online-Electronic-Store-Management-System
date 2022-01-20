<?php
require('top.inc.php');
$admin_reply = '';

if ($_SESSION['loggedin']) {
	$customer_id = $_SESSION['id'];
} else {
	echo "<script> location.href='index.php'; </script>";
}

$sql = "SELECT * FROM complaints where customer_id = '$customer_id' and admin_reply='$admin_reply'";
$result = $con->query($sql);

$row = $result->fetch_assoc();
$complaints_id = $row["complaints_id"];
$complaint = $row["complaint"];
$order_id = $row["order_id"];
$complaint_date = $row["complaint_date"];
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
								<label for="order_id" class=" form-control-label">order_id</label>
								<input type="text" name="order_id" class="form-control" required value="<?php echo $order_id ?>" readonly>
							</div>

                            <div class="form-group">
								<label for="complaint" class=" form-control-label">complaint</label>
								<input type="text" name="complaint" class="form-control" required value="<?php echo $complaint ?>" readonly>
							</div>

							<div class="single-contact-form">
								<div class="contact-box message">	
								<label for="admin_reply" class=" form-control-label">Admin Reply</label>
								<textarea type="text" name="admin_reply" class="form-control" placeholder="Answer This Query "required value="<?php echo $admin_reply ?>"></textarea>
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
$admin_reply = $_POST['admin_reply'];
//var_dump($complaint);
//$sql = "INSERT into complaints(customer_id,order_id,complaint,complaint_date) values('$customer_id','$order_id','$complaint','$complaint_date')";
$sql = "UPDATE complaints set admin_reply='$admin_reply' where customer_id ='$customer_id' and complaints_id = '$complaints_id' and order_id='$order_id'";
$output = mysqli_query($con,$sql);
echo 'Query Solved Successfully!!';
}
?>