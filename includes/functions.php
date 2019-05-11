<?php 
function verify_query($result_set) {
	global $connection;
	if (!$result_set) {
		die("Database Query failed : " . mysqli_error($connection));
	}
	}

function check_req_fields($req_fields) {
	$errors = array();
			foreach ($req_fields as $field) {
			if (empty(trim($_POST[$field]))) {
			$errors[] = $field . ' is Required';
			}
		}
		return $errors;
	}

function check_max_len_fields($max_len_fields)	{
	$errors = array();
			foreach ($max_len_fields as $field => $max_len) {
			if (strlen(trim($_POST[$field])) > $max_len) {
			$errors[] = $field . ' must be less than ' . $max_len . ' characters';
			}
		}
		return $errors;
}

function display_errors($errors) {
				echo '<div class="alert alert-danger"><strong>Warning!</strong><br>';
				foreach ($errors as $error) {
					$error = ucfirst(str_replace("_", " ", $error));
					echo $error . '<br>';
				}
				echo '</div>';
}
 ?>

