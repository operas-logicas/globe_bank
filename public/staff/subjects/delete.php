<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['id'])) {
    redirect_to(url_for('/staff/subjects/index.php'));
}
$id = $_GET['id'];

// Handle form values submitted if POST request
if(is_post_request()) {
    if(isset($_POST['commit'])) {
        $result = delete_subject($id);
        $_SESSION['message'] = "The subject was deleted successfully.";
        redirect_to(url_for('/staff/subjects/index.php'));
    }

} else {
    // GET request
    $subject = find_subject_by_id($id);
}

?>

<?php $page_title = 'Delete Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

    <div class="subject delete">
        <h1>Delete Subject</h1>
        <p>Are you sure you want to delete this subject?</p>
        <p class="item"><strong><?php echo h($subject['menu_name']); ?></strong></p>

        <form action="<?php echo url_for('/staff/subjects/delete.php?id=' . h(u($subject['id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Subject" />
            </div>
        </form>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
