<?php session_start(); ?>
<?php require_once('includes/db_connection.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}


	$user_list = '';
	$query = "SELECT * FROM tbl_users WHERE is_deleted=0 ORDER BY first_name";
	$users = mysqli_query($connection, $query);

	verify_query($users);
		while ($user = mysqli_fetch_assoc($users)) {
			$user_list .= "<tr>";
			$user_list .= "<td>{$user['first_name']}</td>";
			$user_list .= "<td>{$user['last_name']}</td>";
			$user_list .= "<td>{$user['last_login']}</td>";
			$user_list .= "<td><a class=\"btn btn-warning btn-xs\" href=\"modify-user.php?user_id={$user['id']}\">Edit</a></td>";
			$user_list .= "<td><a class=\"btn btn-danger btn-xs\" href=\"delete-user.php?user_id={$user['id']}\" onclick=\"return confirm('Are you sure?');\">Delete</a></td>";
			$user_list .= "</tr>";
		}

?>

<!DOCTYPE html>
<html>
<head>
	<title>users</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">Administration Panel</div>
		<div class="loggedin"> Welcome ! <?php echo $_SESSION['first_name']; ?> <a href="logout.php"> Log Out </a></div>
	</header>

	<main>
		<h2>Administration user panel <span><a class="btn btn-success btn-xs" href="add-user.php"> + Add New User </a></span></h2><hr>
		<table  class="table table-striped table-bordered">
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Last Login</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>

			<?php echo $user_list; ?>

		</table>
	</main>

</body>
</html>