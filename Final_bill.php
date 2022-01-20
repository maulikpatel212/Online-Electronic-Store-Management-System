<div class="content pb-0">
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header" style="text-align:center"><strong>User Profile</strong></div>
					<form method="post" enctype="multipart/form-data">
						<div class="card-body card-block">
							<div class="form-group">
								<label for="first_name" class=" form-control-label">First Name</label>
								<input type="text" name="first_name" class="form-control" required value="<?php echo $first_name ?>">
							</div>

							<div class="form-group">
								<label for="middle_name" class=" form-control-label">Middle Name</label>
								<input type="text" name="middle_name" class="form-control" required value="<?php echo $middle_name ?>">
							</div>

							<div class="form-group">
								<label for="last_name" class=" form-control-label">Last Name</label>
								<input type="text" name="last_name" class="form-control" required value="<?php echo $last_name ?>">
							</div>

							<div class="form-group">
								<label for="contact" class=" form-control-label">Contact</label>
								<input type="text" name="contact" class="form-control" required value="<?php echo $contact ?>">
							</div>

							<div class="form-group">
								<label for="email" class=" form-control-label">Email</label>
								<input type="text" name="email" class="form-control" required value="<?php echo $email ?>" readonly>
							</div>

							<div class="form-group">
								<label for="password" class=" form-control-label">Password</label>
								<input type="text" name="password" class="form-control" required value="<?php echo $password ?>">
							</div>

							<button id="payment-button" name="update" type="submit" class="btn btn-lg btn-info btn-block">
								<span id="payment-button-amount">update</span>
							</button>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
