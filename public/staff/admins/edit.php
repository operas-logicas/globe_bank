<?php

require_once('../../../private/initialize.php');

require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/staff/admins/index.php'));
}
$id = $_GET['id'];
$password = '';
$password_confirm = '';

// Handle form values submitted
if (is_post_request()) {
    $admin = [];

    $admin['id'] = $id;
    $admin['first_name'] = $_POST['first_name'] ?? '';
    $admin['last_name'] = $_POST['last_name'] ?? '';
    $admin['email'] = $_POST['email'] ?? '';
    $admin['username'] = $_POST['username'] ?? '';
    $admin['password'] = $_POST['password'] ?? '';
    $admin['password_confirm'] = $_POST['password_confirm'] ?? '';

    $result = update_admin($admin);
    if ($result === true) {
        $_SESSION['message'] = "The admin was updated successfully.";
        redirect_to(url_for('/staff/admins/show.php?id=' . $id));
    } else {
        $errors = $result;
        // var_dump($errors);

        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
    }
} else {
    // GET request
    $admin = find_admin_by_id($id);
}

?>

<?php $page_title = 'Edit Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

    <div class="admin edit">
        <h1>Edit Admin</h1>

        <?php echo display_errors($errors); ?>
        <form action="<?php echo url_for('/staff/admins/edit.php?id=' . h(u($id))); ?>" method="post">
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
                    <input type="password" name="password" value="<?php echo h($password); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Confirm Password</dt>
                <dd>
                    <input type="password" name="password_confirm" value="<?php echo h($password_confirm); ?>" />
                </dd>
            </dl>
            <p>Password must be at least 12 characters and contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 symbol. <strong>Leave blank if not changing password.</strong></p>
            <div id="operations">
                <input type="submit" value="Edit Admin" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>