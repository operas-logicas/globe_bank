<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php

$result = find_all_admins();

?>

<?php $page_title = 'Admins'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="admins listing">
    <h1>Admins</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/admins/new.php'); ?>">Create New Admin</a>
    </div>

    <table class="list">
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Email</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>

      <?php while ($admin = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo h($admin['id']); ?></td>
          <td><?php echo h($admin['username']); ?></td>
          <td><?php echo h($admin['last_name']); ?></td>
          <td><?php echo h($admin['first_name']); ?></td>
          <td><?php echo h($admin['email']); ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/show.php?id=' . h(u($admin['id']))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/edit.php?id=' . h(u($admin['id']))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/delete.php?id=' . h(u($admin['id']))); ?>">Delete</a></td>
        </tr>
      <?php } ?>
    </table>

    <?php mysqli_free_result($result); ?>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>