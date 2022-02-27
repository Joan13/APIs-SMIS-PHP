<?php

    require_once("../config/dbconnect.functions.php");

    header("Access-Control-Allow-Origin: *");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);

    $response = array();

    function login_credentials($name, $password)
    {
        global $database_connect;
        $response_variable = 0;
       
        $query0 = "SELECT full_name, user_password, COUNT(*) AS count_user_exists FROM workers_info WHERE full_name=? AND user_password=?";
        $request0 = $database_connect->prepare($query0);
        $request0->execute(array($name, $password));
        $response0 = $request0->fetchObject();
        $res2 = $response0->count_user_exists;

        if ($res2 == 1) {
            $query = "SELECT * FROM workers_info WHERE full_name='$name' AND user_password='$password'";
            $request = $database_connect->query($query);
            $response_object = $request->fetchObject();
            
            $response_variable = $response_object; 
        }
        return $response_variable;
    }

    
    $username = htmlspecialchars(trim(strip_tags($_POST["username"])));
    $password = sha1(htmlspecialchars(strip_tags($_POST["password"])));

    if(login_credentials($username, $password) === 0)
    {
        $response['success'] = '0';
        echo json_encode($response);
    }
    else
    {
        $response['success'] = '1';
        $response['user_data'] = login_credentials($username, $password);
        echo json_encode($response);
    }


?>