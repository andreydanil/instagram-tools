<?php

ini_set('max_execution_time', 300); //300 seconds 

if (isset($_POST)) {
    $host = $_POST["host"];
    $dbuser = $_POST["dbuser"];
    $dbpassword = $_POST["dbpassword"];
    $dbname = $_POST["dbname"];

    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $login_password = $_POST["password"] ? $_POST["password"] : "";

    $purchase_code = $_POST["purchase_code"];

    //check required fields
    if (!($host && $dbuser && $dbname && $fullname && $email && $login_password && $purchase_code)) {
        echo json_encode(array("success" => false, "message" => "Please input all fields."));
        exit();
    }

    //check for valid email
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo json_encode(array("success" => false, "message" => "Please input a valid email."));
        exit();
    }
    
    //validate purchase code
    $verification = verify_purchase_code($purchase_code);

    if (!empty($verification) && $verification->status != "success") {
        echo json_encode(array("success" => false, "message" => $verification->message));
        exit();
    }

    //check for valid database connection
    $mysqli = @new mysqli($host, $dbuser, $dbpassword, $dbname);

    if (mysqli_connect_errno()) {
        echo json_encode(array("success" => false, "message" => $mysqli->connect_error));
        exit();
    }


    //all input seems to be ok. check required fiels
    if (!is_file('database.sql')) {
        echo json_encode(array("success" => false, "message" => "The database.sql file could not found in install folder!"));
        exit();
    }

    /*
     * check the db config file
     * if db already configured, we'll assume that the installation has completed
     */

    // set random enter_encryption_key
    $config_file_path = "../config.php";
    $encryption_key = substr(md5(rand()), 0, 15);
    $config_file = file_get_contents($config_file_path);
    $is_installed = strpos($config_file, "enter_db_host");

    if (!$is_installed) {
        echo json_encode(array("success" => false, "message" => "Seems this app is already installed! You can't reinstall it again."));
        exit();
    }

    //start installation
    $sql = file_get_contents("database.sql");

    //set admin information to database
    $now = date("Y-m-d H:i:s");

    $sql = str_replace('admin_fullname', $fullname, $sql);
    $sql = str_replace('admin_email', $email, $sql);
    $sql = str_replace('admin_password', md5($login_password), $sql);
    $sql = str_replace('ITEM-PURCHASE-CODE', $purchase_code, $sql);

    //create tables in datbase 
    $mysqli->multi_query($sql);
    do {
        
    } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));

    $mysqli->close();
    // database created
    // set the database config file
    $config_file = str_replace('enter_encryption_key', $encryption_key, $config_file);
    $config_file = str_replace('enter_db_host', $host, $config_file);
    $config_file = str_replace('enter_db_username', $dbuser, $config_file);
    $config_file = str_replace('enter_db_password', $dbpassword, $config_file);
    $config_file = str_replace('enter_db_name', $dbname, $config_file);

    file_put_contents($config_file_path, $config_file);

    // set the environment = production
    $index_file_path = "../index.php";
    $index_file = file_get_contents($index_file_path);
    $index_file = preg_replace('/installation/', 'production', $index_file, 1); //replace the first occurence of 'pre_installation'
    file_put_contents($index_file_path, $index_file);

    echo json_encode(array("success" => true, "message" => "Installation successfull."));
    exit();
}

function verify_purchase_code($code) {
    $code = urlencode($code);
    $website = str_replace("install/", "", $_SERVER['HTTP_REFERER']);
    $url = "http://vtcreators.com/license/verify?purchase_code=" . $code . "&domain=" . $_SERVER['HTTP_HOST'] . "&website=" . $website . "&app=instatool";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data);
}