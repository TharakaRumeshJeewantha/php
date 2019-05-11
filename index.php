<?php session_start(); ?>
<?php require_once('includes/db_connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 
	if (isset($_POST['submit'])) {

		$errors = array();

		if (!isset($_POST['email']) || strlen(trim($_POST['email'])) <1 ) {
			$errors[] = 'Username is Missing / Invalid';
		}

		if (!isset($_POST['password']) || strlen(trim($_POST['password'])) <1 ) {
			$errors[] = 'Password is Missing / Invalid';
		}

		if (empty($errors)) {
			$email = mysqli_real_escape_string($connection, $_POST['email']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			$query = "SELECT * FROM tbl_users WHERE email = '{$email}' AND password = '{$hashed_password}' LIMIT 1";

			$result_set = mysqli_query($connection, $query);
			verify_query($result_set);
				if (mysqli_num_rows($result_set) == 1) {
					$user = mysqli_fetch_assoc($result_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['first_name'] = $user['first_name'];

					$query = "UPDATE tbl_users SET last_login = NOW()";
					$query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

					$result_set = mysqli_query($connection, $query);
					verify_query($result_set);

					header('Location: users.php');
				} else {
					$errors[] = 'Invalid Username / Password';
				}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title> PHP - Login</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

	<div class="login">
		<form action="index.php" method="post">
			<fieldset>
				<legend><h1 align="center">Log In</h1><p align="center">Administrator Panel</p></legend>

				<?php if (isset($errors) && !empty($errors)) {
					echo '<div class="alert alert-danger"><strong>Warning!</strong> Invalid Username or Password.</div>';
				} ?>

				<p>
					<label for="">Username </label>
					<input type="email" name="email" id="" placeholder="Email Address">
				</p>

				<p>
					<label for="">Password </label>
					<input type="password" name="password" id="" placeholder="**********">
				</p>

				<p>
					<button class="btn_login btn btn-success" type="submit" name="submit"> Log In </button>
				</p>

			</fieldset>
		</form>
	</div>	

</body>
</html> 

<?php mysqli_close($connection) ?>