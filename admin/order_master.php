<?php
require('top.inc.php');

$sql="select * from users order by id desc";
$res=mysqli_query($con,$sql);
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">Order Master </h4>
				</div>
				<div class="card-body--">
				   <div class="table-stats order-table ov-h">
					  <table class="table">
							<thead>
								<tr>
									<th class="product-thumbnail">Order ID</th>
									<th class="product-name"><span class="nobr">Order Date and Time</span></th>
									<th class="product-price"><span class="nobr"> Address </span></th>
									<th class="product-stock-stauts"><span class="nobr"> Payment Type </span></th>
									<!-- <th class="product-stock-stauts"><span class="nobr"> Payment Status </span></th> -->
									<th class="product-stock-stauts"><span class="nobr"> Order Status </span></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "call my_orders('')";
								$res=mysqli_query($con,$sql);
								while($row=mysqli_fetch_assoc($res)){
								?>
								<tr>
									<td class="product-add-to-cart"><a href="order_master_detail.php?id=<?php echo $row['order_id']?>"> <?php echo $row['order_id']?></a></td>
									<td class="product-name"><?php echo $row['order_time']?></td>
									<td class="product-name">
									<?php echo $row['block']?>
                                    <?php echo '/'?>
                                    <?php echo $row['house_no']?>
                                    <?php echo ','?>
                                    <?php echo $row['apartment_name']?>
                                    <?php echo ','?>
                                    <?php echo $row['street']?>
                                    <?php echo ','?>
                                    <?php echo $row['city']?>
                                    <?php echo '-'?>
                                    <?php echo $row['pincode']?>
									</td>
									<td class="product-name"><?php echo "COD"?></td>
									<!-- <td class="product-name"><?php echo $row['payment_status']?></td> -->
									<td class="product-name"><?php echo $row['order_status_str']?></td>
									
								</tr>
								<?php } ?>
							</tbody>
							
						</table>
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