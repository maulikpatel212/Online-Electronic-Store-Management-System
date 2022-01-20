<?php
require("top.inc.php");
?>

<form action="" method="post">
    <button id="payment-button" name="show" type="submit" class="btn btn-lg btn-info btn-block">
        <span id="payment-button-amount">Top 3 Customers</span>
    </button>
</form>

<?php
if (isset($_POST['show'])) {
    mysqli_query($con, "call top3");
    $sql = "SELECT top3.*, users.first_name, users.email, users.contact from  top3, users where top3.customer_id=users.customer_id";
    $query = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
?>

        <table style="width:100%" class="styled-table">
            <thead>
                    <tr class="active-row">
                    <td>customer id</td>
                    <td>customer name</td>
                    <td>customer email</td>
                    <td>Total bought</td>
                    <td>Total saved</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?php echo $row['customer_id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['Total_bought']; ?></td>
                    <td><?php echo $row['Total_saved']; ?></td>
                </tr>
            </tbody>
        </table>

<?php
    }
}
?>

<hr class="rounded">
<!-- PART 2 for product -->

<form action="" method="post">
    <button id="payment-button" name="display" type="submit" class="btn btn-lg btn-info btn-block">
        <span id="payment-button-amount">Most Sold Products</span>
    </button>
</form>

<?php
if (isset($_POST['display'])) {
    $sql = "CALL most_sold_product() ";
    $answer = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($answer)) {
?>

        <table style="width:100%" class="styled-table">
            <thead>
                <tr>
                    <tr class="active-row">
                    <td>Product id</td>
                    <td>Product Name</td>
                    <td>Product Price</td>
                    <td>Total Quantity sold</td>
                    <td>Available Stock</td>
                </tr>
            </thead>

            <tbody>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['product_price']; ?></td>
                <td><?php echo $row['total_qty_sold']; ?></td>
                <td><?php echo $row['available_stock']; ?></td>
            </tr>
            </tbody>
        </table>
<?php
    }
}
//require('footer.inc.php') 
?>



<style>
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
</style>