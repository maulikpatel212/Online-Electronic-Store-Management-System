<?php
require('top.inc.php');

if(isset($_GET['type']) && $_GET['type']!=''){
	$type=get_safe_value($con,$_GET['type']);
	if($type=='status'){
		$operation=get_safe_value($con,$_GET['operation']);
		$id=get_safe_value($con,$_GET['id']);
		if($operation=='active'){
			$status='1';
		}else{
			$status='0';
		}
		$update_status_sql="update product set product_status='$status' where product_id='$id'";
		mysqli_query($con,$update_status_sql);
	}
	
	if($type=='delete'){
		$id=get_safe_value($con,$_GET['id']);
		$delete_sql="delete from product where product_id='$id'";
		mysqli_query($con,$delete_sql);
	}
}

$sql="select product.*,category.category_name from product,category where product.category_id=category.category_id order by product.product_id desc";
$res=mysqli_query($con,$sql);
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">Products </h4>
				   <h4 class="box-link"><a href="manage_product.php">Add Product</a> </h4>
				</div>
				<div class="card-body--">
				   <div class="table-stats order-table ov-h">
					  <table class="table ">
						 <thead>
							<tr>
							   <th class="serial">#</th>
							   <th>ID</th>
							   <th>Categories</th>
							   <th>Name</th>
							   <th>Image</th>
							   <th>MRP</th>
							   <th>Price</th>
							   <th>Qty</th>
							   <th></th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							$i=1;
							while($row=mysqli_fetch_assoc($res)){?>
							<tr>
							   <td class="serial"><?php echo $i?></td>
							   <td><?php echo $row['product_id']?></td>
							   <td><?php echo $row['category_name']?></td>
							   <td><?php echo $row['product_name']?></td>
							   <td><img src="<?php echo '../media/product/'.$row['product_image']?>"/></td>
							   <td><?php echo $row['product_MRP']?></td>
							   <td><?php echo $row['product_price']?></td>
							   <td><?php echo $row['product_quantity']?></td>
							   <td>
								<?php
								if($row['product_status']==1){
									echo "<span class='badge badge-complete'><a href='?type=status&operation=deactive&id=".$row['product_id']."'>Active</a></span>&nbsp;";
								}else{
									echo "<span class='badge badge-pending'><a href='?type=status&operation=active&id=".$row['product_id']."'>Deactive</a></span>&nbsp;";
								}
								//echo "<span class='badge badge-edit'><a href='manage_product.php?id=".$row['product_id']."'>Edit</a></span>&nbsp;";

								echo "<span class='badge badge-edit'><a href='manage_product.php?id=".$row['product_id']."'>Edit</a></span>&nbsp;";
								
								echo "<span class='badge badge-delete'><a href='?type=delete&id=".$row['product_id']."'>Delete</a></span>";
								
								?>
							   </td>
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