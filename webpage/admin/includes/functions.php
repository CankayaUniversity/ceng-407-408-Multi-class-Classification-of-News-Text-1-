<?php
session_start();
//same connection as db.php but for login system
$db = mysqli_connect('localhost', 'root', '', 'mtlbl');

$username = "";
$email    = "";
$errors   = array();


if (isset($_POST['reg_btn'])) {
	register();
}

// register
function register(){

	global $db, $errors, $username, $email;

	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match!");
	}

     //check if username exists

    if(isset($username)){

    $query = "SELECT * FROM user WHERE uName='$username'";
        $results = mysqli_query($db, $query);

        $get_rows = mysqli_affected_rows($db);

        if($get_rows >= 1){
            array_push($errors, "This username already in use");

        }

    }

    //check if email exists
    if(isset($email)){

    $query = "SELECT * FROM user WHERE uEmail='$email'";
        $results = mysqli_query($db, $query);

        $get_rows = mysqli_affected_rows($db);

        if($get_rows >= 1){
            array_push($errors, "This email already in use");

        }

    }


	if (count($errors) == 0) {
		$password = md5($password_1);//password encryption

		if (isset($_POST['uType'])) {
			$user_type = e($_POST['uType']);
			$query = "INSERT INTO user (uName, uEmail, uType, uPassword, isActive)
					  VALUES('$username', '$email', '$user_type', '$password', 0)";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO user (uName, uEmail, uType, uPassword, isActive)
					  VALUES('$username', '$email', 'user', '$password', 0)";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are logged in";
			header('location: index.php');
		}
	}
}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM user WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error" style= "color: crimson;" >';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}


if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}



	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM user WHERE uName='$username' AND uPassword='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['uType'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "Login is successful!";
				header('location: admin/index.php');
			}else if($logged_in_user['isActive'] == 1){

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "Login is successful!";

				header('location: index.php');
                }
              else {
                    $_SESSION['wait']  = "Login is not successful!";


                }
			}
		else {
			array_push($errors, "Wrong username/password combination!");
		}
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['uType'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}
