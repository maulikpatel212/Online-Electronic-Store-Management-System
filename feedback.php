<?php
require('top.php');
$feedback = '';
//$sql = "select orders.customer_id, order_details.order_id, order_details.product_id from order_details, orders where order_details.order_id=orders.order_id and orders.customer_id=9";
//mysqli_query($con, $sql);
?>


<div class="col-xs-12">
    <form id="contact-form" action="#" method="post">

        <div>
            <h1 style="text-align:center">FEEDBACK</h1>
            <br><br>
        </div>
        <div class="single-contact-form">
            <div class="contact-box name">
                <input type="text" id="order_id" name="order_id" placeholder="order_id" value="<?php echo $_POST['order_id']; ?>" readonly>
                <input type="text" id="product_id" name="product_id" placeholder="product_id" value="<?php echo $_POST['product_id']; ?>" readonly>
            </div>
        </div>

        <div class="single-contact-form">
            <div class="contact-box message">
               <input type="text" name="feedback" id="feedback" placeholder="Your Feedback">
            </div>
        </div>

        <div>
        <br>
        </div>
        <button id="payment-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
								<span id="payment-button-amount">Submit</span>
							</button>
    </form>
    <div class="form-output">
        <p class="form-messege"></p>
    </div>
</div>

<?php
if(isset($_POST['submit'])){
$order_id = get_safe_value($con, $_POST['order_id']);
$product_id = get_safe_value($con, $_POST['product_id']);
$feedback = $_POST['feedback'];
//mysqli_query($con, "INSERT into feedback(order_id,product_id,feedback) values('$order_id','$product_id','$feedback')");
//mysqli_query($con,"call feedback('$order_id','$product_id','$feedback')");

$sql = mysqli_query($con,"SELECT feedback('$order_id','$product_id','$feedback')");

if($sql = 'Thank you for your response'){
    echo 'Thank you for your response';
}
else{
    echo 'You have already responded';
}
}
?>