<?php
require('top.php');
if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($con, $_GET['id']);
    if ($product_id > 0) {
        $get_product = get_product($con, '', '', $product_id);
    } else {
?>
        <script>
            window.location.href = 'dashboard.php';
        </script>
    <?php
    }
} else {
    ?>
    <script>
        window.location.href = 'dashboard.php';
    </script>
<?php
}
?>

<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="dashboard.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <a class="breadcrumb-item" href="categories.php?id=<?php echo $get_product['0']['category_id'] ?>"><?php echo $get_product['0']['category_name'] ?></a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active"><?php echo $get_product['0']['product_name'] ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- Start Product Details Area -->
<section class="htc__product__details bg__white ptb--100">
    <!-- Start Product Details Top -->
    <div class="htc__product__details__top">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                    <div class="htc__product__details__tab__content">
                        <!-- Start Product Big Images -->
                        <div class="product__big__images">
                            <div class="portfolio-full-image tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="img-tab-1">
                                    <img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $get_product['0']['product_image'] ?>">
                                </div>
                            </div>
                        </div>
                        <!-- End Product Big Images -->

                    </div>
                </div>
                <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 smt-40 xmt-40">
                    <div class="ht__product__dtl">
                        <h2><?php echo $get_product['0']['product_name'] ?></h2>
                        <ul class="pro__prize">
                            <li class="old__prize"><strike><?php echo $get_product['0']['product_MRP'] ?></strike></li>
                            <li><?php echo $get_product['0']['product_price'] ?></li>
                        </ul>
                        <p class="pro__info"><?php echo $get_product['0']['product_details'] ?></p>
                        <div class="ht__pro__desc">
                            <div class="sin__desc">
                                <?php
                                    $sql = "SELECT product_quantity from product where product_id='$product_id'";
                                    $result = $con->query($sql);

	                                // Associative array
	                                $row = $result->fetch_assoc();
                                    $product_quantity = $row["product_quantity"];
                                    
                                ?>
                                <p><span>Availability: </span><?php echo $product_quantity ?></p>
                            </div>
                            <div class="sin__desc">
                                <p><span>Select Qty:</span>
                                    <select id="qty">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>9</option>
                                        <option>10</option>
                                        <option>11</option>
                                        <option>12</option>
                                        <option>13</option>
                                        <option>14</option>
                                        <option>15</option>
                                        <option>16</option>
                                        <option>17</option>
                                        <option>18</option>
                                        <option>19</option>
                                        <option>20</option>
                                        <option>21</option>
                                        <option>22</option>
                                        <option>23</option>
                                        <option>24</option>
                                        <option>25</option>
                                        
                                    </select>
                                        
                                </p>
                            </div>
                            <div class="sin__desc align--left">
                                <p><span>Categories:</span></p>
                                <ul class="pro__cat__list">
                                    <li><a href=""><?php echo $get_product['0']['category_name'] ?></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <a class="fr__btn" href="javascript:void(0)" onclick="manage_cart('<?php echo $get_product['0']['product_id'] ?>','add')">Add to cart</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End Product Details Top -->
</section>
<!-- End Product Details Area 
		<!-- Start Product Description -->
<section class="htc__produc__decription bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Start List And Grid View -->
                <ul class="pro__details__tab" role="tablist">
                    <li role="presentation" class="description active"><a href="#description" role="tab" data-toggle="tab">description</a></li>
                </ul>
                <!-- End List And Grid View -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="ht__pro__details__content">
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="description" class="pro__single__content tab-pane fade in active">
                        <div class="pro__tab__content__inner">
                            <?php echo $get_product['0']['product_details'] ?>
                        </div>
                    </div>
                    <!-- End Single Content -->

                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Description -->


<?php require('footer.php') ?>