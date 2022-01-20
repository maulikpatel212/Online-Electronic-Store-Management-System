<?php
require('top.inc.php');
$order_id=get_safe_value($con,$_GET['id']);
if(isset($_POST['update_order_status'])){
	$update_order_status=$_POST['update_order_status'];
	if($update_order_status=='5'){
		mysqli_query($con,"update orders set status='$update_order_status' where order_id='$order_id'");
	}else{
		mysqli_query($con,"update orders set status='$update_order_status' where order_id='$order_id'");
	}
	
}
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">Order Detail </h4>
				</div>
				<div class="card-body--">
				   <div class="table-stats order-table ov-h">
					  <table class="table">
								<thead>
									<tr>
										<th class="product-thumbnail">Product Name</th>
										<th class="product-thumbnail">Product Image</th>
										<th class="product-name">Qty</th>
										<th class="product-price">Price</th>
										<th class="product-price">Total Price</th>
									</tr>
								</thead>
								<tbody>
									<?php
									
									$res=mysqli_query($con,"select delivery_address.*,order_details.order_id, order_details.qty, product.product_name,product.product_image, product.product_price from order_details, delivery_address, product ,orders where order_details.order_id='$order_id' and order_details.product_id=product.product_id and delivery_address.delivery_address_id=orders.delivery_address_id and orders.order_id=order_details.order_id;");

									$total_price=0;
									
									$userInfo=mysqli_fetch_assoc(mysqli_query($con,"select * from orders,delivery_address where order_id='$order_id' and delivery_address.delivery_address_id = orders.delivery_address_id"));
									
									$block=$userInfo['block'];
									$house_no=$userInfo['house_no'];
									$apartment_name=$userInfo['apartment_name'];
									$street=$userInfo['street'];
									$city=$userInfo['city'];
									$pincode=$userInfo['pincode'];
									
									while($row=mysqli_fetch_assoc($res)){
									$total_price=$total_price+($row['qty']*$row['product_price']);
									?>
									<tr>
										<td class="product-name"><?php echo $row['product_name']?></td>
										<td class="product-name"> <img src="<?php echo PRODUCT_IMAGE_SITE_PATH.$row['product_image']?>"></td>
										<td class="product-name"><?php echo $row['qty']?></td>
										<td class="product-name"><?php echo $row['product_price']?></td>
										<td class="product-name"><?php echo $row['qty']*$row['product_price']?></td>
										
									</tr>
									<?php } ?>
									<tr>
										<td colspan="3"></td>
										<td class="product-name">Total Price</td>
										<td class="product-name"><?php echo $total_price?></td>
										
									</tr>
								</tbody>
							
						</table>
						<div id="address_details">
							<strong>Delivery Address</strong>
							- <?php echo $block?>/<?php echo $house_no?>, <?php echo $apartment_name?>, <?php echo $street?>, <?php echo $city?>-<?php echo $pincode?><br/><br/>
							<strong>Order Status</strong>
							- <?php 
							$order_status_arr=mysqli_fetch_assoc(mysqli_query($con,"select order_status.status from order_status,orders where orders.order_id='$order_id' and orders.status=order_status.status_id"));
							echo $order_status_arr['status'];
							?>
							
							<div>
								<form method="post">
									<select class="form-control" name="update_order_status" required>
										<option value="">Select Status</option>
										<?php
										$res=mysqli_query($con,"select * from order_status");
										while($row=mysqli_fetch_assoc($res)){
											if($row['status_id']==$categories_id){
												echo "<option selected value=".$row['status_id'].">".$row['status']."</option>";
											}else{
												echo "<option value=".$row['status_id'].">".$row['status']."</option>";
											}
										}
										?>
									</select>
									<input type="submit" class="form-control"/>
								</form>
							</div>
						</div>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
<?php
require('footer.inc.php');
?>