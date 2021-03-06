<?php

require_once('../../../private/initialize.php');

require_login();

// Initialize values
$admin['first_name'] = '';
$admin['last_name'] = '';
$admin['email'] = '';
$admin['username'] = '';
$admin['password'] = '';
$admin['password_confirm'] = '';

// Handle form values submitted
if(is_post_request()) {
    $admin = [];
    $admin['first_name'] = $_POST['first_name'] ?? '';
    $admin['last_name'] = $_POST['last_name'] ?? '';
    $admin['email'] = $_POST['email'] ?? '';
    $admin['username'] = $_POST['username'] ?? '';
    $admin['password'] = $_POST['password'] ?? '';
    $admin['password_confirm'] = $_POST['password_confirm'] ?? '';

    $result = insert_admin($admin);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The admin was created successfully.";
        redirect_to(url_for('/staff/admins/show.php?id=' . $new_id));
    } else {
        $errors = $result;
        // var_dump($errors);
    }

} else {
    // GET request so display the form below
}

?>

<?php $page_title = 'Create Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

    <div class="admin new">
        <h1>Create Admin</h1>

        <?php echo display_errors($errors); ?>
        <form action="<?php echo url_for('/staff/admins/new.php'); ?>" method="post">
            <dl>
                <dt>First Name</dt>
                <dd>
                    <input type="text" name="first_name" value="<?php echo h($admin['first_name']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Last Name</dt>
                <dd>
                    <input type="text" name="last_name" value="<?php echo h($admin['last_name']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Email</dt>
                <dd>
                    <input type="text" name="email" value="<?php echo h($admin['email']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Username</dt>
                <dd>
                    <input type="text" name="username" value="<?php echo h($admin['username']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Password</dt>
                <dd>
                    <input type="password" name="password" value="<?php echo h($admin['password']); ?>" />
                </dd>
            </dl>
             <dl>
                <dt>Confirm Password</dt>
                <dd>
                    <input type="password" name="password_confirm" value="<?php echo h($admin['password_confirm'])?>" />
                </dd>
            </dl>
            <p>Password must be at least 12 characters and contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 symbol.</p>
            <div id="operations">
                <input type="submit" value="Create Admin" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
