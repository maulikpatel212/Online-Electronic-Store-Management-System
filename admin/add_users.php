<?php
require('top.inc.php');
?>
 <div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong>Add User</strong></div>
                        <form method="POST">
                        <div class="card-body card-block">
                           <div class="form-group"><label for="first_name" class=" form-control-label">First Name</label><input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control" required></div>
                           <div class="form-group"><label for="middle_name" class=" form-control-label">Middle Name</label> <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name" class="form-control" required></div>
                           <div class="form-group"><label for="last_name" class=" form-control-label">Last Name</label> <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control" required></div>
                           <div class="form-group"><label for="contact" class=" form-control-label">Contact</label> <input type="text" name="contact" id="contact" placeholder="Mobile" class="form-control" required></div>
                           <div class="form-group"><label for="email" class=" form-control-label">Email</label><input type="email" name="email" placeholder="Email" id="Email" class="form-control"></div>
                           <div class="form-group"><label for="password" class=" form-control-label">Password</label><input type="password" name="password" placeholder="Password" id="password" class="form-control"></div>
                           <button id="payment-button" type="submit" name="submit" class="btn btn-lg btn-info btn-block">
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
require('footer.inc.php');
?>

<?php
if (!isset($_POST['first_name'], $_POST['middle_name'], $_POST['last_name'], $_POST['contact'], $_POST['email'], $_POST['password'])) {
    // Could not get the data that should have been sent.
    exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.

if (empty($_POST['first_name'] ||  empty($_POST['middle_name']) || empty($_POST['last_name']) || empty($_POST['contact']) || empty($_POST['email']) || empty($_POST['password']))) {
    // One or more values are empty.
    exit('Please complete the registration form');
}

//Validation for EMAIL AND PASSWORD
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}
//Validation is finished

if(isset($_POST['submit'])){

    $first_name=get_safe_value($con,$_POST['first_name']);
    $middle_name=get_safe_value($con,$_POST['middle_name']);
    $last_name=get_safe_value($con,$_POST['last_name']);
    $contact= get_safe_value($con,$_POST['contact']);
    $email= get_safe_value($con,$_POST['email']);
    $password= get_safe_value($con,$_POST['password']);


    $sql="insert into users(first_name,middle_name,last_name,contact,email,password) values('$first_name','$middle_name','$last_name','$contact','$email','$password')";
    mysqli_query($con,$sql);
    echo 'Successful';
}
?>