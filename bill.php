<?php
require('top.inc.php');
$order_id = '';
$counter = 0;

if (isset($_GET['id']) && $_GET['id'] != '') {
	$id = get_safe_value($con, $_GET['order_id']);
	$res = mysqli_query($con, "select * from orders where order_id='$id'");
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
					<div class="card-header"><strong>Bill</strong></div>
					<form method="post" enctype="multipart/form-data">
						<div class="card-body card-block">
							<div class="form-group">
								<label for="Bill" class=" form-control-label">Please select the date</label>
								<select class="form-control" name="order_time">
									<option>Select Date</option>
									<?php
									$customer_id = $_SESSION['id'];
									$res = mysqli_query($con, "SELECT order_id, order_time FROM orders where customer_id='$customer_id' group by DATE_FORMAT(order_time, '%Y-%m-%d')");
									while ($row = mysqli_fetch_assoc($res)) {
										if ($row['category_id'] == $categories_id) {
											echo "<option selected value=" . date("Y-m-d", strtotime($row['order_time'])) . ">" . date("Y-m-d", strtotime($row['order_time'])) . "</option>";
										} else {
											echo "<option value=" . date("Y-m-d", strtotime($row['order_time'])) . ">" . date("Y-m-d", strtotime($row['order_time'])) . "</option>";
										}
									}

									$customer_details = mysqli_query($con, "SELECT * from USERS where customer_id = $customer_id");
									?>
								</select>
							</div>

							<div class="form-group">
								<label for="customer_id" class=" form-control-label">Customer_id</label>
								<input type="text" name="customer_id" class="form-control" required value="<?php echo $customer_id ?>" readonly>
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

if (isset($_POST['submit'])) {
	$order_time = $_POST['order_time'];
	$sql = "CALL bill('$customer_id','$order_time')";
	$bill_values = mysqli_fetch_assoc(mysqli_query($con, $sql));
	$customer_details1 = mysqli_fetch_assoc($customer_details);

?>

	<html>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0" style="width:100%">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<h2>Electronic Store Management System</h2>
								</td>

								<td>
									Invoice #: 123<br />
									Created: <?php echo (date("d-m-Y")); ?> <br />
									Due: 09-05-2021
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td>
									<b>Delivery Address</b>
									<hr>
									<?php echo $bill_values['apartment_name']; ?>, <?php echo $bill_values['house_no']; ?><br />
									Block: <?php echo $bill_values['block']; ?><br />
									<?php echo $bill_values['street']; ?>, <br />
									<?php echo $bill_values['city']; ?> - <?php echo $bill_values['pincode']; ?><br />
								</td>

								<td>
									<b>Customer Details</b>
									<hr>
									<b>Name: </b><?php echo $customer_details1['first_name']; ?>
									<?php echo $customer_details1['middle_name']; ?>
									<?php echo $customer_details1['last_name']; ?><br />
									<b>Contact Number:</b> <?php echo $customer_details1['contact']; ?><br />
									<b>Email:</b> <?php echo $customer_details1['email']; ?>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<hr class="rounded">


			<table>
				<tr class="active-row">
					<td>Payment Method</td>
					<td>COD</td>
				</tr>
			</table>
			<hr class="rounded">
			<table>
				<tr class="active-row">
					<td><b>Item</b></td>
					<td style="text-align:center"><b>Quantity</b></td>
					<td style="text-align:right"><b>Price</b></td>
				</tr>

				<tr class="-row">
					<td>DELL Inspiron Ryzen 3</td>
					<td style="text-align:center">1</td>
					<td style="text-align:right">36700</td>
				</tr>

				<tr class="active-row">
					<td>APPLE IPhone 11</td>
					<td style="text-align:center">1</td>
					<td style="text-align:right">55555</td>
				</tr>

				<tr class="active-row">
					<td>BoAt Rockerz 255F</td>
					<td style="text-align:center">1</td>
					<td style="text-align:right">999</td>
				</tr>
			</table>
			<table>
				<tr class="total">
					<td></td>

					<td>Total: 57153</td>
				</tr>

				<tr class="heading">
					<td>
						<p><b>Note:</b> This is a computer generated document and it does not require a signature.</p>
					</td>
				</tr>
			</table>
		<?php
	}
		?>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 40px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/* RTL */
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}

			hr.rounded {
				border-top: 8px solid #bbb;
				border-radius: 15px;
			}

			.styled-table {
				border-collapse: collapse;
				margin: 25px 0;
				font-size: 0.9em;
				font-family: sans-serif;
				min-width: 400px;
				box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
			}

			.styled-table thead tr {
				background-color: #009879;
				color: #ffffff;
				text-align: left;
			}

			.styled-table th,
			.styled-table td {
				padding: 12px 15px;
			}

			.styled-table tbody tr {
				border-bottom: 1px solid #dddddd;
			}

			.styled-table tbody tr:nth-of-type(even) {
				background-color: #f3f3f3;
			}

			.styled-table tbody tr:last-of-type {
				border-bottom: 2px solid #009879;
			}

			.styled-table tbody tr.active-row {
				font-weight: bold;
				color: #009879;
			}


			hr.rounded {
				border-top: 8px solid #bbb;
				border-radius: 15px;
			}

			.center {
  text-align: center;
  border: 3px solid green;
}
		</style>