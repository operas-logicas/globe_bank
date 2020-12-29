<?php

/* -------------- Subjects ------------- */

// Get all from subjects
function find_all_subjects($options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM subjects ";
    if($visible) {
        $sql .= "WHERE visible=1 ";
    }
    $sql .= "ORDER BY position ASC";
    // echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

// Get subject by id
function find_subject_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM subjects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "'";
    if($visible) {
        $sql .= " AND visible=1";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $subject = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $subject; // returns an assoc. array
}

// Validate form submission for subject
function validate_subject($subject) {
    $errors = [];

    // menu_name
    if(is_blank($subject['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif(!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    }

    // position
    $position_int = (int) $subject['position'];
    if($position_int <= 0) {
        $errors[] = "Position must be greater than zero.";
    } elseif($position_int > 999) {
        $errors[] = "Position must be less than 999.";
    }

    // visible
    $visible_str = (string) $subject['visible'];
    if(!has_inclusion_of($visible_str, ['0','1'])) {
        $errors[] = "Visible must be true or false.";
    }

    return $errors;
}

// Insert new subject
function insert_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO subjects ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $subject['menu_name']) . "', ";
    $sql .= "'" . db_escape($db, $subject['position']) . "', ";
    $sql .= "'" . db_escape($db, $subject['visible']) . "'";
    $sql .= ")";

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Insert failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Update subject
function update_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE subjects SET ";
    $sql .= "menu_name='" . db_escape($db, $subject['menu_name']). "', ";
    $sql .= "position='" . db_escape($db, $subject['position']) . "', ";
    $sql .= "visible='" . db_escape($db, $subject['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $subject['id']) . "' ";
    $sql .= "LIMIT 1";
    
    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;  
    } else {
        // Update failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Delete subject
function delete_subject($id) {
    global $db;

    $sql = "DELETE FROM subjects ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Delete failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/* -------------- Pages ------------- */

// Get all from pages
function find_all_pages($options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    if($visible) {
        $sql .= "WHERE visible=1 ";
    }
    $sql .= "ORDER BY subject_id ASC, position ASC";
    // echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

// Get page by id
function find_page_by_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "'";
    if($visible) {
        $sql .= " AND visible=1";
    }
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page; // returns an assoc. array
}

// Get all pages by subject_id
function find_pages_by_subject_id($id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE subject_id='" . db_escape($db, $id) . "' ";
    if($visible) {
        $sql .= " AND visible=1 ";
    }
    $sql .= "ORDER BY position ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

// Validate form submission for page
function validate_page($page) {
    global $db;
    $errors = [];

    // menu_name
    $current_id = $page['id'] ?? '0';
    if(is_blank($page['menu_name'])) {
        $errors[] = "Name cannot be blank.";
    } elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Name must be between 2 and 255 characters.";
    } elseif(!has_unique_page_menu_name($page['menu_name'], $current_id)) {
        $errors[] = "Name must be unique.";
    }

    // subject_id
    $subject_id_str = (string) $page['subject_id'];

    $subject_set = find_all_subjects();
    $subject_ids = [];
    while($subject = mysqli_fetch_assoc($subject_set)) {
        $subject_ids[] = $subject['id'];
    }
    mysqli_free_result($subject_set);

    if(!has_inclusion_of($subject_id_str, $subject_ids)) {
        $errors[] = "Subject must already exist.";
    }

    // position
    $position_int = (int) $page['position'];
    if($position_int <= 0) {
        $errors[] = "Position must be greater than zero.";
    } elseif($position_int > 999) {
        $errors[] = "Position must be less than 999.";
    }

    // visible
    $visible_str = (string) $page['visible'];
    if(!has_inclusion_of($visible_str, ['0','1'])) {
        $errors[] = "Visible must be true or false.";
    }

    // content
    if(is_blank($page['content'])) {
        $errors[] = "Content cannot be blank.";
    }

    return $errors;
}

// Insert new page
function insert_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO pages ";
    $sql .= "(subject_id, menu_name, position, visible, content) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $page['subject_id']) . "', ";
    $sql .= "'" . db_escape($db, $page['menu_name']) . "', ";
    $sql .= "'" . db_escape($db, $page['position']) . "', ";
    $sql .= "'" . db_escape($db, $page['visible']) . "', ";
    $sql .= "'" . db_escape($db, $page['content']) . "'";
    $sql .= ")";

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Insert failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Update page
function update_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE pages SET ";
    $sql .= "subject_id='" . db_escape($db, $page['subject_id']) . "', ";
    $sql .= "menu_name='" . db_escape($db, $page['menu_name']) . "', ";
    $sql .= "position='" . db_escape($db, $page['position']) . "', ";
    $sql .= "visible='" . db_escape($db, $page['visible']) . "', ";
    $sql .= "content='" . db_escape($db, $page['content']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $page['id']) . "' ";
    $sql .= "LIMIT 1";
    
    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;  
    } else {
        // Update failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Delete page
function delete_page($id) {
    global $db;

    $sql = "DELETE FROM pages ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Delete failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/* -------------- Admins ------------- */

// Get all from admins
function find_all_admins($options=[]) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "ORDER BY last_name ASC, first_name ASC";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
}

// Get admin by id
function find_admin_by_id($id, $options=[]) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "'";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
}

// Get admin by username
function find_admin_by_username($username, $options=[]) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "'";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $admin; // returns an assoc. array
}

// Validate form submission for admin
function validate_admin($admin, $options=[]) {
    $errors = [];
    $password_required = $options['password_required'] ?? true;

    // first_name
    if(is_blank($admin['first_name'])) {
        $errors[] = "First name cannot be blank.";
    } elseif(!has_length($admin['first_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "First name must be between 2 and 255 characters.";
    }

    // last_name
    if(is_blank($admin['last_name'])) {
        $errors[] = "Last name cannot be blank.";
    } elseif(!has_length($admin['last_name'], ['min' => 2, 'max' => 255])) {
        $errors[] = "Last name must be between 2 and 255 characters.";
    }

    // email
    if(is_blank($admin['email'])) {
        $errors[] = "Email cannot be blank.";
    } elseif (!has_length($admin['email'], ['max' => 255])) {
        $errors[] = "Email must be less than 255 characters.";
    } elseif(!has_valid_email_format($admin['email'])) {
        $errors[] = "Email must be valid format (i.e. 'nobody@nowhere.com').";
    } 

    // username
    $current_id = $admin['id'] ?? 0;
    if(is_blank($admin['username'])) {
        $errors[] = "Username cannot be blank.";
    } elseif(!has_length($admin['username'], ['min' => 8, 'max' => 255])) {
        $errors[] = "Username must be between 8 and 255 characters.";
    } elseif(!has_unique_admin_username($admin['username'], $current_id)) {
        $errors[] = "Username must be unique.";
    }

    // password
    if($password_required) {
        if(is_blank($admin['password'])) {
            $errors[] = "Password cannot be blank.";
        } elseif(!has_length($admin['password'], ['min' => 12])) {
            $errors[] = "Password must be 12 or more characters.";
        } elseif(!is_valid_password($admin['password'])) {
            $errors[] = "Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 symbol.";
        } elseif(is_blank($admin['password_confirm'])) {
            $errors[] = "Confirm password cannot be blank.";
        } elseif($admin['password'] !== $admin['password_confirm']) {
            $errors[] = "Confirm password must match password.";
        }
    }

    return $errors;
    
}

// Insert new admin
function insert_admin($admin) {
    global $db;
    $insert = true;

    $errors = validate_admin($admin);
    if(!empty($errors)) {
        return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins ";
    $sql .= "(first_name, last_name, email, username, hashed_password) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "'" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "'" . db_escape($db, $admin['email']) . "', ";
    $sql .= "'" . db_escape($db, $admin['username']) . "', ";
    $sql .= "'" . db_escape($db, $hashed_password) . "'";
    $sql .= ")";

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Insert failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Update admin
function update_admin($admin) {
    global $db;

    // If both password and password_confirm were blank,
    // assume user has chosen not to update their password
    $password_sent = !is_blank($admin['password']) && !is_blank($admin['password_confirm']);

    $errors = validate_admin($admin, ['password_required' => $password_sent]);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE admins SET ";
    $sql .= "first_name='" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "last_name='" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "email='" . db_escape($db, $admin['email']) . "', ";
    $sql .= "username='" . db_escape($db, $admin['username']) . "'";

    if($password_sent) {
        $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);
        $sql .= ", hashed_password='" . db_escape($db, $hashed_password) . "'";
    }

    $sql .= " WHERE id='" . db_escape($db, $admin['id']) . "' ";
    $sql .= "LIMIT 1";
    
    //echo $sql;

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Update failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

// Delete admin
function delete_admin($id) {
    global $db;

    $sql = "DELETE FROM admins ";
    $sql .= "WHERE id='" . db_escape($db, $id) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql); // returns true or false
    if($result) {
        // Success
        return true;
    } else {
        // Delete failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

?>
