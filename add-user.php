<?php session_start(); ?>
<?php require_once('includes/db_connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 

	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	
	$errors = array();

	$first_name = '';
	$last_name = '';
	$email = '';
	$password = '';


	if (isset($_POST['submit'])) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];


		$req_fields = array('first_name', 'last_name', 'email', 'password');

		$errors = array_merge($errors,check_req_fields($req_fields));

		$max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100, 'password' => 40);

		$errors = array_merge($errors,check_max_len_fields($max_len_fields));

		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$query = "SELECT * FROM tbl_users WHERE email = '{$email}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'Email address already exists';
			}
		}

		if (empty($errors)) {
			$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
			$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);

			$hashed_password = sha1($password);

			$query = "INSERT INTO tbl_users (";
			$query .= "first_name, last_name, email, password, is_deleted";
			$query .= ") VALUES (";
			$query .= "'{$first_name}', '{$last_name}', '{$email}', '{$hashed_password}', 0";
			$query .= ")";

			$result = mysqli_query($connection, $query);
			if ($result) {
				header('Location: users.php');
			} else {
				$errors[] = 'Failed to add the new record';
			}
		}

	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Add New User</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">Administration Panel</div>
		<div class="loggedin"> Welcome ! <?php echo $_SESSION['first_name']; ?> <a href="logout.php"> Log Out </a></div>
	</header>

	<main>
		<h2>Add New User <span><a class="btn btn-info btn-xs" href="users.php"> < Back to Main </a></span></h2><hr>

		<?php 
			if (!empty($errors)) {
				display_errors($errors);
			}
		 ?>

		<form action="add-user.php" method="post" class="userform">
			
			<p>
				<label for="">First Name : </label>
				<input type="text" name="first_name" <?php echo 'value="'. $first_name .'"'; ?>>
			</p>

			<p>
				<label for="">Last Name : </label>
				<input type="text" name="last_name" <?php echo 'value="'. $last_name .'"'; ?>>
			</p>

			<p>
				<label for="">Email Address : </label>
				<input type="email" name="email" <?php echo 'value="'. $email .'"'; ?>>
			</p>

			<p>
				<label for="">Password : </label>
				<input type="password" name="password">
			</p>

			<p>
				<label for="">&nbsp; </label>
				<button type="submit" class="btn btn-success" name="submit"> Save </button>
			</p>

		</form>

	</main>

</body>
</html>