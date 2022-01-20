<?php
require('top.php');
if (!isset($_SESSION['loggedin'])) {
?>
    <script>
        window.location.href = 'dashboard.php';
    </script>
<?php
}
$order_id = get_safe_value($con, $_GET['id']);
?>

<style>
    .GFG {
        background-color: white;
        border: 2px solid black;
        color: green;
        padding: 5px 10px;
        text-align: center;
        display: inline-block;
        font-size: 20px;
        margin: 10px 30px;
        cursor: pointer;
        text-decoration: none;
    }
</style>

<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="dashboard.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active">Thank You</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- cart-main-area start -->
<div class="wishlist-area ptb--100 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="wishlist-content">
                        <div class="wishlist-table table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail">Product Name</th>
                                        <th class="product-thumbnail">Product Image</th>
                                        <th class="product-name">Qty</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-price">Total Price</th>
                                        <th class="product-name">Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $uid = $_SESSION['id'];
                                    $res = mysqli_query($con, "select distinct(order_details.order_id), order_details.*, product.product_name, product.product_image,product.product_price from order_details,product,orders where order_details.order_id='$order_id' and order_details.order_id = orders.order_id and orders.customer_id='$uid' and order_details.product_id=product.product_id");
                                    $total_price = 0;
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $total_price = $total_price + ($row['qty'] * $row['product_price']);
                                    ?>
                                        <tr>
                                            <td class="product-name"><?php echo $row['product_name'] ?></td>
                                            <td class="product-name"> <img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $row['product_image'] ?>" /></td>
                                            <td class="product-name"><?php echo $row['qty'] ?></td>
                                            <td class="product-name"><?php echo $row['product_price'] ?></td>
                                            <td class="product-name"><?php echo $row['qty'] * $row['product_price'] ?></td>
                                            <td class="product_name">
                                                <form action="feedback.php" method="post">
                                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                                    <input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>" > 
                                                    <button type="submit" class="GFG">Feedback</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="product-name">Total Price</td>
                                        <td class="product-name"><?php echo $total_price ?></td>

                                    </tr>
                                </tbody>

                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require('footer.php') ?>