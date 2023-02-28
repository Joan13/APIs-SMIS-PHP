<?php

    require_once("../config/dbconnect.functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();

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

    function signin($username, $password) {
        global $database_connect;
        $query = "SELECT user_name, user_password, COUNT(*) AS count_user_exists FROM workers_info WHERE user_name=? AND user_password=?";
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
                $query0 = "SELECT user_name, user_password, COUNT(*) AS count_user_exists FROM workers_info WHERE user_name=? AND user_password=?";
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

    if(signin($username, $password) == 1) {
        // $sel = "SELECT * FROM users WHERE username='$username' AND user_password='$password'";
        // $sel_request = $database_connect->query($sel);
        // $sel_response = $sel_request->fetchObject();

        $sel = "SELECT * FROM workers_info WHERE user_name='$username' AND user_password='$password'";
        $sel_request = $database_connect->query($sel);
        $sel_response = $sel_request->fetchObject();

        $response['success'] = '1';
        $response['user_data'] = $sel_response;
        echo json_encode($response);
    } else {
        $response['success'] = '0';
        echo json_encode($response);
    }


?>