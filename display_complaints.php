<?php
require('top.inc.php');
$admin_reply = '';

if ($_SESSION['loggedin']) {
	$customer_id = $_SESSION['id'];
} else {
	echo "<script> location.href='index.php'; </script>";
}

$sql = "SELECT * FROM complaints where customer_id = '$customer_id'order by order_id";
$result = $con->query($sql);

$row = $result->fetch_assoc();
$complaint = $row["complaint"];
$order_id = $row["order_id"];
$complaint_date = $row["complaint_date"];
$admin_reply = $row["admin_reply"];
//echo $admin_reply;


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
					<div class="card-header" style="text-align:center"><strong>Previous Complaints</strong></div>
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

                            <div class="form-group">
								<label for="admin_reply" class=" form-control-label">admin_reply</label>
								<input type="text" name="admin_reply" class="form-control" required placeholder="You will show your Reply here When the Admin will get back to you!! "required value="<?php echo $admin_reply ?>" readonly>
							</div>

							<div class="form-group">
								<label for="contact" class=" form-control-label">complaint_date</label>
								<input type="text" name="complaint_date" class="form-control" required value="<?php echo $complaint_date ?>" readonly>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>