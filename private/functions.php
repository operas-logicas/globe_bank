<?php

// Assign absolute path to URL
function url_for($script_path) {
    // add leading '/' if not present
    if($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }
    // prepend path to root
    return WWW_ROOT . $script_path;
}

// Shortcut for urlencode()
function u($string="") {
    return urlencode($string);
}

// Shortcut for rawurlencode()
function raw_u($string="") {
    return rawurlencode($string);
}

// Shortcut for htmlspecialchars()
function h($string="") {
    return htmlspecialchars($string);
}

// Error 404 Not Found
function error_404() {
    header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
    exit();
}

// Error 500 Internal Server Error
function error_500() {
    header($_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error");
    exit();
}

// 302 Redirect
function redirect_to($location) {
    header("Location: " . $location);
    exit();
}

// Check if request is 'post'
function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// Check if request is 'get'
function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Display errors
function display_errors($errors=array()) {
    $output = '';
    if(!empty($errors)) {
        $output .= "<div class='errors'>";
        $output .= "<strong>Please fix the following errors:</strong>";
        $output .= "<ul>";
        foreach($errors as $error) {
            $output .= "<li>" . h($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}

function get_and_clear_session_message() {
    if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']);
        return $msg;
    }
}

function display_session_message() {
    $msg = get_and_clear_session_message();
    if(!is_blank($msg)) {
        return "<div id='message'>" . h($msg) . "</div>";
    }
}

?>
