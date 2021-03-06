<?php session_start(); ?>
<?php require_once('includes/db_connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 

	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	
	$errors = array();
	$user_id = '';
	$first_name = '';
	$last_name = '';
	$email = '';
	$password = '';

	if (isset($_GET['user_id'])) {
		$user_id =mysqli_real_escape_string($connection,$_GET['user_id']);
		$query = "SELECT * FROM tbl_users WHERE id={$user_id} LIMIT 1";
		$result_set = mysqli_query($connection, $query);

		if ($result_set) {
			if (mysqli_num_rows($result_set) ==1 ) {
				$result = mysqli_fetch_assoc($result_set);
				$first_name = $result['first_name'];
				$last_name = $result['last_name'];
				$email = $result['email'];

			} else {
				header('Location : users.php');
			}
		} else {
			header('Location : users.php');
		}
	}



	if (isset($_POST['submit'])) {

	$user_id = $_POST['user_id'];
	$password = $_POST['password'];


		$req_fields = array('user_id', 'password');

		$errors = array_merge($errors,check_req_fields($req_fields));

		$max_len_fields = array('password' => 40);

		$errors = array_merge($errors,check_max_len_fields($max_len_fields));



		if (empty($errors)) {
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			$query = "UPDATE tbl_users SET ";
			$query .= "password = '{$hashed_password}' ";
			$query .= "WHERE id = {$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);
			if ($result) {
				header('Location: users.php');
			} else {
				$errors[] = 'Failed to update the password';
			}
		}

	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Change user password</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">Administration Panel</div>
		<div class="loggedin"> Welcome ! <?php echo $_SESSION['first_name']; ?> <a href="logout.php"> Log Out </a></div>
	</header>

	<main>
		<h2>Change user password <span><a class="btn btn-info btn-xs" href="users.php"> < Back to Main </a></span></h2><hr>

		<?php 
			if (!empty($errors)) {
				display_errors($errors);
			}
		 ?>

		<form action="change-password.php" method="post" class="userform">
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<p>
				<label for="">First Name : </label>
				<input type="text" name="first_name" <?php echo 'value="'. $first_name .'"'; ?> disabled>
			</p>

			<p>
				<label for="">Last Name : </label>
				<input type="text" name="last_name" <?php echo 'value="'. $last_name .'"'; ?> disabled>
			</p>

			<p>
				<label for="">Email Address : </label>
				<input type="email" name="email" <?php echo 'value="'. $email .'"'; ?> disabled>
			</p>

			<p>
				<label for="">New Password : </label>
				<input type="password" name="password" id="password">
			</p>

			<p>
				<label for="">Show Password : </label>
				<input type="checkbox" name="showpassword" id="showpassword" style="width: 20px; height: 20px;">
			</p>

			<p>
				<label for="">&nbsp; </label>
				<button type="submit" class="btn btn-success" name="submit"> Update Password </button>
			</p>

		</form>

	</main>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$('#showpassword').click(function(){
				if ($('#showpassword').is(':checked')) {
					$('#password').attr('type','text');
				}
				else {
					$('#password').attr('type','password');
				}
			});
		});
	</script>
</body>
</html>