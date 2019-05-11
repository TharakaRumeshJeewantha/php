<?php session_start(); ?>
<?php require_once('includes/db_connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 

	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}


	if (isset($_GET['user_id'])) {
		$user_id =mysqli_real_escape_string($connection,$_GET['user_id']);
		if ($user_id == $_SESSION['user_id']) {
			header('Location : users.php');
		}
		else {
			$query = "UPDATE tbl_users SET is_deleted = 1 WHERE id = {$user_id} LIMIT 1";
			$result = mysqli_query($connection, $query);

			if ($result) {
				header('Location: users.php');
			}
			else {
				header('Location: users.php');
			}
		}
	}
	else {
		header('Location : users.php');
	}


 ?>

