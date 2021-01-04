<?php

  // Performs all actions necessary to log in an admin
  function log_in_admin($admin) {
  // Renerating the ID protects the admin from session fixation.
    session_regenerate_id();
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_level'] = $admin['level'];
    $_SESSION['last_login'] = time();
    $_SESSION['username'] = $admin['username'];
    return true;
  }

  // is_logged_in() contains all the logic for determining if a
  // request should be considered a "logged in" request or not.
  // It is the core of require_login() but it can also be called
  // on its own in other contexts (e.g. display one link if an admin
  // is logged in and display another link if they are not)
  function is_logged_in() {
    // Having an admin_id in the session serves a dual-purpose:
    // - Its presence indicates the admin is logged in.
    // - Its value tells which admin for looking up their record.
    return isset($_SESSION['admin_id']);
  }

  // Performs all actions necessary to log out an admin
  function log_out_admin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_level']);
    unset($_SESSION['last_login']);
    unset($_SESSION['username']);
    // Optional:
    // session_destroy(); // destroys the whole session
    return true;
  }

  // Call require_login() at the top of any page which needs to
  // require a valid login (user level access [>= 0] )
  function require_login() {
    if (!is_logged_in()) {
      redirect_to(url_for('/staff/login.php'));
    } else {
      // Do nothing, let the rest of the page proceed
    }
  }

  // Call require_admin() at the top of any page which needs to
  // require a valid login AND at least admin level access (>= 400)
  function require_admin() {
    require_login();
    if (!$_SESSION['admin_level'] >= 400) {
      redirect_to(url_for('/staff/index.php'));
    } else {
      // Do nothing, let the rest of the pae proceed
    }
  }

// Call require_superuser() at the top of any page which needs to
// require a valid login AND at least superuser level access (>= 800)
function require_superuser()
{
  require_login();
  if (!$_SESSION['admin_level'] >= 800) {
    redirect_to(url_for('/staff/index.php'));
  } else {
    // Do nothing, let the rest of the pae proceed
  }
}

// Returns logged in user's admin id
function get_user_id()
{
  return $_SESSION['admin_id'] ?? 0;
}

// Returns logged in user's admin level
function get_user_level() {
  return $_SESSION['admin_level'] ?? 0;
}

// Returns user type based on provided access level
function get_user_type($level) {
  switch ($level) {
    case '800':
      $level = 'Superuser';
      break;
    case '400':
      $level = 'Admin';
      break;
    case '0':
    default:
      $level = 'User';
  }
  return $level;
}

?>
