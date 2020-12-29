<?php

require_once('../../../private/initialize.php');

require_login();

// Initialize values
$subject['menu_name'] = '';
$subject['position'] = '';
$subject['visible'] = '1';

// Handle form values submitted
if(is_post_request()) {
    $subject = [];
    $subject['menu_name'] = $_POST['menu_name'] ?? '';
    $subject['position'] = $_POST['position'] ?? '';
    $subject['visible'] = $_POST['visible'] ?? '';

    $result = insert_subject($subject);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = "The subject was created successfully.";
        redirect_to(url_for('/staff/subjects/show.php?id=' . $new_id));
    } else {
        $errors = $result;
        // var_dump($errors);
    }

} else {
    // GET request so display the form below
}

$result = find_all_subjects();
$subject_count = mysqli_num_rows($result) + 1;
mysqli_free_result($result);
$subject['position'] = $subject_count;

?>

<?php $page_title = 'Create Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

    <div class="subject new">
        <h1>Create Subject</h1>

        <?php echo display_errors($errors); ?>
        <form action="<?php echo url_for('/staff/subjects/new.php'); ?>" method="post">
            <dl>
                <dt>Menu Name</dt>
                <dd>
                    <input type="text" name="menu_name" value="<?php echo h($subject['menu_name']); ?>" />
                </dd>
            </dl>
            <dl>
                <dt>Position</dt>
                <dd>
                    <select name="position">
                        <?php
                            for($i = 1; $i <= $subject_count; $i++) {
                                echo "<option value='{$i}'";
                                if($subject['position'] == $i) {
                                    echo " selected";
                                }
                                echo ">{$i}</option>";
                            }
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Visible</dt>
                <dd>
                    <input type="hidden" name="visible" value="0" />
                    <input type="checkbox" name="visible" value="1" <?php if($subject['visible'] == '1') { echo "checked "; } ?>/>
                </dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Subject" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
