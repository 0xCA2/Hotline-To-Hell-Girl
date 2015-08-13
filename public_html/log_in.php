<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<div class="row">
	<div class="large-6 columns">
		<div class="panel">
			<h2>Login</h2>
			<form data-abide action="attempt_user_login.php" method="post">
				<div class="row">
					<div class="large-12 columns">
						<label> Username:
							<input name="username" type="text" placeholder="Enter username." required >
						</label>
						<small class="error">Username is required.</small>
					</div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<label> Password:
							<input name="password" type="password" placeholder="Enter password." required>
						</label>
						<small class="error">Password is required.</small>

					</div>
				</div>	
				<div class="row" id="user_login_button_set">
					<div class="large-12 columns">
						<input type="submit" href="#" class="button small right" value="Submit" name="submit">
						<a id="log_in_cancel_button" href="index.php" class="button small right">Cancel</a>
						<a class="left" href="forgot.php"> Forgot password?</a>
						
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<div class="large-6 columns">
		<div class="panel">
			<h2>Sign Up</h2>
			<form data-abide action="signup.php" method="post">
				<div class="row">
					<div class="large-12 columns">
						<label> Username:
							<input name="username" type="text" placeholder="Enter username." required > 
						</label>
						<small class="error">Username is required. </small>
					</div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<label> Password:
							<input name="password" id="password" type="password" placeholder="Enter password." required>
						</label>
						<small class="error">Password is required.</small>

					</div>
				</div>	
				<div class="row">
					<div class="large-12 columns">
						<label> Confirm Password:
							<input type="password" placeholder="Confirm Password." data-equalto="password">
						</label>
						<small class="error">The password did not match</small>

					</div>
				</div>	
				<div class="row">
					<div class="large-12 columns">
						<label> Email:
							<input name="email" type="text" placeholder="Enter email." required pattern="email">
							<small class="error">Please use a valid email address.</small>
						</label>
					</div>
				</div>					
				<div class="row" id="user_login_button_set">
					<div class="large-12 columns">
						<input type="submit" href="#" class="button small right" value="Submit" name="submit">
						<a id="log_in_cancel_button" href="index.php" class="button small right">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</div>
<?php include("../includes/layouts/footer.php"); ?>
