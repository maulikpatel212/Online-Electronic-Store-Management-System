<?php
require('top.inc.php');
$categories_id = '';
$name = '';
$mrp = '';
$price = '';
$qty = '';
$image = '';
$short_desc	= '';
$description	= '';
// $meta_title	='';
// $meta_description	='';
// $meta_keyword='';

$msg = '';
$image_required = 'required';
if (isset($_GET['id']) && $_GET['id'] != '') {
	$image_required = '';
	$id = get_safe_value($con, $_GET['id']);
	$res = mysqli_query($con, "select * from product where product_id='$id'");
	// var_dump($res);
	$check=mysqli_num_rows($res);
	if ($check > 0) {
		$row = mysqli_fetch_assoc($res);
		$categories_id = $row['category_id'];
		$name = $row['product_name'];
		$mrp = $row['product_MRP'];
		$price = $row['product_price'];
		$qty = $row['product_quantity'];
		$short_desc = $row['product_details'];
		$description = $row['product_details'];
		// $meta_title=$row['meta_title'];
		// $meta_desc=$row['meta_desc'];
		// $meta_keyword=$row['meta_keyword'];
	} else {
		header('location:product.php');
		die();
	}
}

if (isset($_POST['submit'])) {
	$categories_id = get_safe_value($con, $_POST['category_id']);
	$name = get_safe_value($con, $_POST['product_name']);
	$mrp = get_safe_value($con, $_POST['product_MRP']);
	$price = get_safe_value($con, $_POST['product_price']);
	$qty = get_safe_value($con, $_POST['product_quantity']);
	$short_desc = get_safe_value($con, $_POST['product_details']);
	$description = get_safe_value($con, $_POST['product_details']);
	// prx($row);
	// $meta_title=get_safe_value($con,$_POST['meta_title']);
	// $meta_desc=get_safe_value($con,$_POST['meta_desc']);
	// $meta_keyword=get_safe_value($con,$_POST['meta_keyword']);

	$res = mysqli_query($con, "select * from product where product_name='$name'");
	$check = mysqli_num_rows($res);
	if ($check > 0) {
		if (isset($_GET['id']) && $_GET['id'] != '') {
			$getData = mysqli_fetch_assoc($res);
			if ($id == $getData['product_id']) {
			} else {
				$msg = "Product already exist";
			}
		} else {
			$msg = "Product already exist";
		}
	}

	if ($_GET['id'] == 0) {
		if ($_FILES['product_image']['type'] != 'image/png' && $_FILES['product_image']['type'] != 'image/jpg' && $_FILES['product_image']['type'] != 'image/jpeg') {
			$msg = "Please select only png,jpg or jpeg image format";
		}
	} else {
		if ($_FILES['product_image']['type'] != '') {
			if ($_FILES['product_image']['type'] != 'image/png' && $_FILES['product_image']['type'] != 'image/jpg' && $_FILES['product_image']['type'] != 'image/jpeg') {
				$msg = "Please select only png,jpg or jpeg image format";
			}
		}
	}

	if ($msg == '') {
		if (isset($_GET['id']) && $_GET['id'] != '') {
			if ($_FILES['product_image']['product_name'] != '') {
				$image = rand(111111111, 999999999) . '_' . $_FILES['product_image']['product_name'];
				move_uploaded_file($_FILES['product_image']['tmp_name'], PRODUCT_IMAGE_SERVER_PATH . $image);
				$update_sql = "update product set category_id='$categories_id',product_name='$name',product_MRP='$mrp',product_price='$price',product_quantity='$qty',product_details='$description',product_image='$image' where product_id='$id'";
			} else {
				$update_sql = "update product set category_id='$categories_id',product_name='$name',product_MRP='$mrp',product_price='$price',product_quantity='$qty',product_details='$description' where product_id='$id'";
			}
			mysqli_query($con, $update_sql);
		} else {
			$image = rand(111111111, 999999999) . '_' . $_FILES['product_image']['product_name'];
			move_uploaded_file($_FILES['product_image']['tmp_name'], '../media/product/' . $image);
			mysqli_query($con, "insert into product(category_id,product_name,product_MRP,product_price,product_quantity,product_details,product_status,product_image) values('$categories_id','$name','$mrp','$price','$qty','$description',1,'$image')");
		}
		header('location:product.php');
		die();
	}
}
?>
<div class="content pb-0">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><strong>Product</strong><small> Form</small></div>
					<form method="post" enctype="multipart/form-data">
						<div class="card-body card-block">
							<div class="form-group">
								<label for="categories" class=" form-control-label">Categories</label>
								<select class="form-control" name="category_id">
									<option>Select Category</option>
									<?php
									$res = mysqli_query($con, "select category_id,category_name from category order by category_name asc");
									while ($row = mysqli_fetch_assoc($res)) {
										if ($row['category_id'] == $categories_id) {
											echo "<option selected value=" . $row['category_id'] . ">" . $row['category_name'] . "</option>";
										} else {
											echo "<option value=" . $row['category_id'] . ">" . $row['category_name'] . "</option>";
										}
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="categories" class=" form-control-label">Product Name</label>
								<input type="text" name="product_name" placeholder="Enter product name" class="form-control" required value="<?php echo $name ?>">
							</div>

							<div class="form-group">
								<label for="categories" class=" form-control-label">MRP</label>
								<input type="text" name="product_MRP" placeholder="Enter product mrp" class="form-control" required value="<?php echo $mrp ?>">
							</div>

							<div class="form-group">
								<label for="categories" class=" form-control-label">Price</label>
								<input type="text" name="product_price" placeholder="Enter product price" class="form-control" required value="<?php echo $price ?>">
							</div>

							<div class="form-group">
								<label for="categories" class=" form-control-label">Qty</label>
								<input type="text" name="product_quantity" placeholder="Enter qty" class="form-control" required value="<?php echo $qty ?>">
							</div>

							<div class="form-group">
								<label for="categories" class=" form-control-label">Image</label>
								<input type="file" name="product_image" class="form-control" <?php echo  $image_required ?>>
							</div>

							<div class="form-group">
								<label for="categories" class=" form-control-label">Short Description</label>
								<textarea name="product_details" placeholder="Enter product short description" class="form-control" required><?php echo $short_desc ?></textarea>
							</div>

							<div class="form-group">
								<label for="categories" class=" form-control-label">Description</label>
								<textarea name="product_details" placeholder="Enter product description" class="form-control" required><?php echo $description ?></textarea>
							</div>

							<!-- <div class="form-group">
									<label for="categories" class=" form-control-label">Meta Title</label>
									<textarea name="meta_title" placeholder="Enter product meta title" class="form-control"><?php echo $meta_title ?></textarea>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Meta Description</label>
									<textarea name="meta_desc" placeholder="Enter product meta description" class="form-control"><?php echo $meta_description ?></textarea>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Meta Keyword</label>
									<textarea name="meta_keyword" placeholder="Enter product meta keyword" class="form-control"><?php echo $meta_keyword ?></textarea>
								</div> -->


							<button id="payment-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
								<span id="payment-button-amount">Submit</span>
							</button>
							<div class="field_error"><?php echo $msg ?></div>
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