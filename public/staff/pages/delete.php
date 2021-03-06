<?php

require_once('../../../private/initialize.php');

require_login();

if (!isset($_GET['id'])) {
    redirect_to(url_for('/staff/pages/index.php'));
}
$id = $_GET['id'];
$page = find_page_by_id($id);

// Handle form values submitted if POST request
if (is_post_request()) {
    if (isset($_POST['commit'])) {
        $result = delete_page($id);
        $_SESSION['message'] = "The page was deleted successfully.";
        redirect_to(url_for('/staff/subjects/show.php?id=' . h(u($page['subject_id']))));
    }
} else {
    // GET request so display the form below
    
}

?>

<?php $page_title = 'Delete Page'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/subjects/show.php?id=' . h(u($page['subject_id']))); ?>">&laquo; Back to Subject Page</a>

    <div class="page delete">
        <h1>Delete Page</h1>

        <p>Are you sure you want to delete this page?</p>
        <p class="item"><strong><?php echo h($page['menu_name']); ?></strong></p>

        <form action="<?php echo url_for('/staff/pages/delete.php?id=' . h(u($page['id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Page" />
            </div>
        </form>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>