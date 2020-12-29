<?php

require_once('../../private/initialize.php');

// iniitialize login values
$errors = [];
$username = '';
$password = '';

if(is_post_request()) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validations
    if(is_blank($username)) {
        $errors[] = "Username cannot be blank.";
    }
    if(is_blank($password)) {
        $errors[] = "Password cannot be blank.";
    }

    // If there were no errors, try to login
    if(empty($errors)) {
        $login_failure_msg = "Log in was unsuccessful.";
        $admin = find_admin_by_username($username);

        if($admin) {
            if(password_verify($password, $admin['hashed_password'])) {
                // Password matches
                log_in_admin($admin);
                redirect_to(url_for('/staff/index.php'));
            } else {
                // Username found but password does NOT match
                $errors[] = $login_failure_msg;
            }

        } else {
            // No username found
            $errors[] = $login_failure_msg;
        }
    }

} else {
    // GET request so show login form below
}

?>

<?php $page_title = 'Log In'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <h1>Log In</h1>

    <?php echo display_errors($errors); ?>
    <form action="<?php echo url_for('/staff/login.php'); ?>" method="post">
        Username:<br>
        <input type="text" name="username" value="<?php echo h($username); ?>"/><br>
        Password:<br>
        <input type="password" name="password" value="" /><br>
        <input type="submit" value="Submit" />
    </form>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>