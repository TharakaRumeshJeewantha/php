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
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];


		$req_fields = array('user_id', 'first_name', 'last_name', 'email');

		$errors = array_merge($errors,check_req_fields($req_fields));

		$max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100);

		$errors = array_merge($errors,check_max_len_fields($max_len_fields));

		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$query = "SELECT * FROM tbl_users WHERE email = '{$email}' AND id != '{$user_id}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'Email address already exists';
			}
		}

		if (empty($errors)) {
			$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
			$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);

			$query = "UPDATE tbl_users SET ";
			$query .= "first_name = '{$first_name}', ";
			$query .= "last_name = '{$last_name}', ";
			$query .= "email = '{$email}' ";
			$query .= "WHERE id = {$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);
			if ($result) {
				header('Location: users.php');
			} else {
				$errors[] = 'Failed to modify the new record';
			}
		}

	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Update user</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">Administration Panel</div>
		<div class="loggedin"> Welcome ! <?php echo $_SESSION['first_name']; ?> <a href="logout.php"> Log Out </a></div>
	</header>

	<main>
		<h2>Update user <span><a class="btn btn-info btn-xs" href="users.php"> < Back to Menu </a></span></h2><hr>

		<?php 
			if (!empty($errors)) {
				display_errors($errors);
			}
		 ?>

		<form action="modify-user.php" method="post" class="userform">
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
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
				<span>**********</span> | <a href="change-password.php?user_id=<?php echo $user_id; ?>">Change Password</a>
			</p>

			<p>
				<label for="">&nbsp; </label>
				<button type="submit" class="btn btn-success" name="submit"> Update </button>
			</p>

		</form>

	</main>

</body>