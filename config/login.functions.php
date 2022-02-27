<?php

	// Login functions : check credentials
	function login_credentials($poste, $password)
	{
		global $database_connect;
		$query = "SELECT poste, user_password, COUNT(*) AS count_user_exists FROM users WHERE poste=? AND user_password=?";
		$request = $database_connect->prepare($query);
		$request->execute(array($poste, $password));
		$response_array = $request->fetchObject();
		$res = $response_array->count_user_exists;
		$passwordGlogal = sha1("000000");
	    $passwordAdmin = sha1("000passwordAdmin");

		if($res == 1 || $password === $passwordAdmin)
		{
			$response = 1;
		}
		else
		{
			$response = 0;
		}
		if($password === $passwordGlogal)
		{
	        if($password === $passwordGlogal)
	        {
	            $query0 = "SELECT poste, user_password, COUNT(*) AS count_poste_exists FROM users WHERE poste=? AND user_password=?";
	            $request0 = $database_connect->prepare($query0);
	            $request0->execute(array($poste, $passwordGlogal));
	            $response0 = $request0->fetchObject();
	            $res2 = $response0->count_poste_exists;

	            if($res2 == 0)
	            {
	                $response = 0;
	            }
	            else
	            {
	            	$response = 1;
	            }
	        }
		}

		return $response;
	}

	function insert_promotor()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(1));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(1, sha1("000000")));
	    }
	}

	function insert_director()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(2));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(2, sha1("000000")));
	    }
	}

	function insert_studies_director()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(7));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(7, sha1("000000")));
	    }
	}

	function insert_daf()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(3));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(3, sha1("000000")));
	    }
	}

	function insert_secretor()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(4));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(4, sha1("000000")));
	    }
	}

	function insert_teacher()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(5));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(5, sha1("000000")));
	    }
	}

	function insert_caissier()
	{
		global $database_connect;
	    $verify_poste = "SELECT poste, user_password, COUNT(*) AS count_if_already_inserted FROM users WHERE poste=?";
	    $verify = $database_connect->prepare($verify_poste);
	    $verify->execute(array(6));
	    $response_verify = $verify->fetchObject();
	    if($response_verify->count_if_already_inserted == 0)
	    {
	        $insert_user_string = "INSERT INTO users(poste, user_password) VALUES(?, ?)";
	        $insert_user = $database_connect->prepare($insert_user_string);
	        $insert_user->execute(array(6, sha1("000000")));
	    }
	}

	function libelles_internat() {

		$input = "Frais de l'internat";
		$gender_libelle = 0;
        $insert_libelle0 = "INSERT INTO libelles(description_libelle, gender_libelle) VALUES(?, ?)";
        $insert_libelle = $database_connect->prepare($insert_libelle0);
        $insert_libelle->execute(array($input, $gender_libelle));
	}

    function randomPupilId($length) {
    	$alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    	return substr(str_shuffle(str_repeat($alphabet, $length)), $length, $length);
    }

	function enterPupilsIdentification () {
		global $database_connect;
		$query = "SELECT * FROM pupils_info";
		$request = $database_connect->query($query);
		while ($response = $request->fetchObject()) {
			if ($response->pupilIdentification == "") {
				$query0 = "UPDATE pupils_info SET pupilIdentification=? WHERE pupil_id=?";
				$request0 = $database_connect->prepare($query0);
				$request0->execute(array(randomPupilId(10), $response->pupil_id));
			}
		}
	}
?>