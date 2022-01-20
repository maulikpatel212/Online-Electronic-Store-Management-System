<?php
require('top.php');
$discount = 0;
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
?>
	<script>
		window.location.href = 'dashboard.php';
	</script>
<?php
}

if (isset($_POST['submit'])) {
	
	$delivery_address_id = "";
	$block = get_safe_value($con,$_POST['block']);
	$house_no = get_safe_value($con,$_POST['house_no']);
	$apartment_name = get_safe_value($con,$_POST['apartment_name']);
	$street = get_safe_value($con,$_POST['street']);
	//$address = get_safe_value($con, $_POST['address']);
	$city = get_safe_value($con, $_POST['city']);
	$pincode = get_safe_value($con, $_POST['pincode']);
	$customer_id = $_SESSION['id'];
	$order_status = 1;
	$order_time = date('Y-m-d h:i:s');
	$order_id = "";
	// echo 'Inserted Successfully';
	if (!isset($_POST['block'],$_POST['house_no'],$_POST['apartment_name'],$_POST['street'],$_POST['city'], $_POST['pincode'])) {
		// Could not get the data that should have been sent.
		exit('Please fill both the email and password fields!');
	}

	if (empty($_POST['block']) || empty($_POST['house_no']) || empty($_POST['apartment_name']) || empty($_POST['street']) || empty($_POST['city']) || empty($_POST['pincode'])) {
		// One or more values are empty.
		exit('Please complete the details');
	}

	$res = "CALL delivery_address_insert('$block','$house_no','$apartment_name','$street','$city','$pincode')";
	mysqli_query($con,$res);
	echo '<script>alert("Your Order Has Been Placed Successfully!!")</script>';
	// header("Location: my_o
	
	// <a href="my_order.php">Visit</a>;

	//echo "<p align='center'> <font color=DarkGreen size='6pt'>$delivery_address_id</font> </p>";
	// }
	//Only for getting delivery_address_id from the table


	$sql = "SELECT delivery_address_id FROM delivery_address where city='$city' and pincode='$pincode' and block='$block' and house_no='$house_no' and apartment_name='$apartment_name' and street='$street'";
	$result = $con->query($sql);

	// Associative array
	$row = $result->fetch_assoc();
	$delivery_address_id = $row["delivery_address_id"];
	//echo $delivery_address_id;

	mysqli_query($con,"INSERT into orders(customer_id,delivery_address_id,order_time,status) values('$customer_id','$delivery_address_id','$order_time','$order_status')");
	
	$order_id=mysqli_insert_id($con);
	
	foreach($_SESSION['cart'] as $key=>$val){
		$productArr=get_product($con,'','',$key);
		$price=$productArr[0]['product_price'];
		$qty=$val['qty'];
		
		mysqli_query($con,"insert into order_details(order_id,product_id,qty) values('$order_id','$key','$qty')");
	}
	$temp = "call offer('$order_id')";
	mysqli_query($con, $temp);
}
?>

<style>
.accordion .accordion__title{
    background: #f4f4f4;
    height: 65px;
    line-height: 65px;
    display: flex;
    align-items: center;
    padding: 0 30px;
    position: relative;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    
    font-family: "Poppins";
    cursor: pointer;
    margin-top:30px;
	margin-bottom:5px;
}

</style>
<!-- cart-main-area start -->
<div class="checkout-wrap ptb--100">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="checkout__inner">
					<div class="accordion-list">
						<div class="accordion">

							<div class="accordion__body">

							</div>
							<div class="accordion__title">
								Address Information
							</div>
							<form method="post">

								<div class="bilinfo">
									<div class="row">


									<div class="col-md-3">
											<div class="single-input">
												<input type="text" name="block" placeholder="Block" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="single-input">
												<input type="text" name="house_no" placeholder="House No." required>
											</div>
										</div>

										
										<div class="col-md-12">
											<div class="single-input">
												<input type="text" name="apartment_name" placeholder="Apartment Name" required>
											</div>
										</div>

										<div class="col-md-12">
											<div class="single-input">
												<input type="text" name="street" placeholder="Street" required>
											</div>
										</div>

										<div class="col-md-6">
											<div class="single-input">
												<input type="text" name="city" placeholder="City" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="single-input">
												<input type="text" name="pincode" placeholder="Post code/ zip" required>
											</div>
										</div>



									</div>
								</div>

								<div class="accordion__title">
									payment information
								</div>
								<div class="bilinfo">
								<form action="/action_page.php">
  								<input type="checkbox" id="vehicle1" name="COD" value="Bike" required>
  								<label for="vehicle1">COD</label><br>
								<!-- </form> -->
								</div>

								<div class="container-contact100-form-btn">
									<div class="wrap-contact100-form-btn">
										<input type="submit" name="submit">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="order-details">
					<h5 class="order-details__title">Your Order</h5>
					<div class="order-details__item">

						<?php
						$cart_total = 0;
						
						foreach ($_SESSION['cart'] as $key => $val) {
							$productArr = get_product($con, '', '', $key);
							$pname = $productArr[0]['product_name'];
							$mrp = $productArr[0]['product_MRP'];
							$price = $productArr[0]['product_price'];
							$image = $productArr[0]['product_image'];
							$qty = $val['qty'];
							$cart_total = $cart_total + ($price * $qty);
						?>

							<div class="single-item">
								<div class="single-item__thumb">
									<img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $image ?>" />
								</div>
								<div class="single-item__content">
									<a href="#"><?php echo $pname ?></a>
									<span class="price"><?php echo $price * $qty ?></span>
								</div>
								<div class="single-item__remove">
									<a href="javascript:void(0)" onclick="manage_cart('<?php echo $key ?>','remove')"><i class="icon-trash icons"></i></a>
								</div>
							</div>

						<?php } ?>
					</div>

					<?php 
					//print_r($discount);
					$today = date("Y-m-d H:i:s");
					$timestamp = strtotime($today);

					$day = date('D', $timestamp);
					// var_dump($day);
					// $day = date("D");
					if (($day="Sat" or $day="Sun") and $cart_total>2000){
						$discount=$cart_total/10;
						if ($discount>=1000){
							$discount = 1000;
						}
					}
					$diff=($cart_total-$discount);
					?>
					<div class="ordre-details__total">
						<h5 style="margin-left:35px;">Cart total</h5>
						<span class="price"><?php echo $cart_total ?></span>
					</div>
					
					<div class="ordre-details__total">
					<font size="26">-</font><h5>Total Discount</h5>
						
						<span class="price"><?php echo 0 ?></span>
						
					</div>
					<hr style="height:2px;border-width:0;color:gray;background-color:gray">
					<div class="ordre-details__total">
						<h5>To be Paid </h5>
						
						<span class="price"><?php echo $cart_total ?></span>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- cart-main-area end -->







<!-- post_checkout procedure -->

<!-- BEGIN
	DECLARE c_addressid CURSOR FOR SELECT delivery_address_id FROM delivery_address where city='city' and pincode='pincode' and block='block' and house_no='house_no' and apartment_name='apartment_name' and street='street';
	DECLARE r_addressid = c_addressid%rowtype;
    DECLARE address_id int;
    DECLARE c_is_address CURSOR FOR SELECT * FROM delivery_address;
    DECLARE r_is_address = c_is_address%rowtype;

    FOR r_is_address in c_is_address LOOP
    	IF (r_is_address.block!=block OR r_is_address.house_no!=house_no OR r_is_address.apartment_name!=apartment_name OR r_is_address.street!=street) 
        THEN
			INSERT into delivery_address(delivery_address_id,block,house_no,apartment_name,street,city,pincode) values('delivery_address_id','block','house_no','apartment_name','street','city','pincode');
    address_id = r_addressid.delivery_address_id;
    	END IF
    END FOR
    address_id:=r_addressid.delivery_address_id;
    INSERT into orders(customer_id,delivery_address_id,order_time,status) values('customer_id','address_id','order_time','order_status');
END -->

<!-- mysqli_query($con, "INSERT into delivery_address(delivery_address_id,block,house_no,apartment_name,street,city,pincode) values('$delivery_address_id','$block','$house_no','$apartment_name','$street','$city','$pincode')"); -->
	<!-- //echo "<p align='center'> <font color=DarkGreen size='6pt'>$delivery_address_id</font> </p>";

	//Only for getting delivery_address_id from the table
	$sql = "SELECT delivery_address_id FROM delivery_address where city='$city' and pincode='$pincode'";
	$result = $con->query($sql);

	// Associative array
	$row = $result->fetch_assoc();
	$delivery_address_id = $row["delivery_address_id"];
	//echo $delivery_address_id;

	mysqli_query($con,"INSERT into orders(customer_id,delivery_address_id,order_time,status) values('$customer_id','$delivery_address_id','$order_time','$order_status')");
	
	$order_id=mysqli_insert_id($con);
	
	foreach($_SESSION['cart'] as $key=>$val){
		$productArr=get_product($con,'','',$key);
		$price=$productArr[0]['product_price'];
		$qty=$val['qty'];
		
		mysqli_query($con,"insert into order_details(order_id,product_id,qty) values('$order_id','$key','$qty')");
	}



 -->
