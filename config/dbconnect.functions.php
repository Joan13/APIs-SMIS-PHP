<?php

	$dbhost = 'localhost';
	$dbname = 'school_administration';
	$dbuser = 'root';
	$dbpassword = 'root';

	// $dbhost = '185.98.131.158';
	// $dbname = 'cseli1701093';
	// $dbuser = 'cseli1701093';
	// $dbpassword = 'vttwkbtca3';

	try {
		$database_connect = new PDO('mysql:host='.$dbhost.';dbname='.$dbname,$dbuser,$dbpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	}
	catch (Exception $database_error) {
		die('Error: We are unable to access internet. Retry Later : ' . $database_error->getMessage());
	}


	// $dbhostExport = '185.98.131.158';
	// $dbnameExport = 'cseli1701093';
	// $dbuserExport = 'cseli1701093';
	// $dbpasswordExport = 'vttwkbtca3';

	// try
	// {
	// 	$database_connect_export = new PDO('mysql:host='.$dbhostExport.';dbname='.$dbnameExport,$dbuserExport,$dbpasswordExport, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	// }
	// catch(Exception $database_error)
	// {
	// 	die('Error: We are unable to access internet. Retry Later : ' . $database_error->getMessage());
	// }

	// $dbhostExport = 'localhost';
	// $dbnameExport = 'school_administration_export';
	// $dbuserExport = 'root';
	// $dbpasswordExport = 'root';

	// try {
	// 	$database_connect_export = new PDO('mysql:host='.$dbhostExport.';dbname='.$dbnameExport,$dbuserExport,$dbpasswordExport, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	// }
	// catch(Exception $database_error)
	// {
	// 	die('Error: We are unable to access internet. Retry Later : ' . $database_error->getMessage());
	// }


	// $school_name = "Ecole \"Académie La Renaisssance\"";
	// $school_name_abb = "CS La Renaissance";
	// $devise_school = "Une école référence";
	// $school_bp = "B.P. 2276 - BUKAVU";
	// $email_school = "larenaissance@gmail.com";

	// $school_name = "Complexe Scolaire Le Progrès";
	// $school_name_abb = "Le Progrès";
	// $devise_school = "Une école de référence";
	// $school_bp = "B.P. 2276 - BUKAVU";
	// $email_school = "csleprogresbukavu@gmail.com";

	// $school_name = "Collège Alfajiri";
	// $school_name_abb = "Collège Alfajiri";
	// $devise_school = "Stella Duce";
	// $school_bp = "B.P. 2276 - BUKAVU";
	// $email_school = "college.alfajiri@gmail.com";

	// $school_name = "Masomo";
	// // $school_name_abb = "Masomo";
	// $school_name_abb = "Masomo";
	// $devise_school = "Une école de référence";
	// $school_bp = "B.P.  - BUKAVU";
	// $email_school = "cs.elite@gmail.com";
	// $school_city = "Bukavu";
	// $school_province = "Sud-Kivu";
	// $school_commune = "Ibanda";
	// $phone_1 = "+243 991 776 858";
	// $phone_2 = "+243 819 009 678";

	// $school_name = "Complexe Scolaire \"Élite\"";
	// // $school_name_abb = "Masomo";
	// $school_name_abb = "C. S. Élite";
	// $devise_school = "Une école de référence";
	// $school_bp = "B.P.  - BUKAVU";
	// $email_school = "cs.elite@gmail.com";
	// $school_city = "Bukavu";
	// $school_province = "Sud-Kivu";
	// $school_commune = "Ibanda";
	// $phone_1 = "+243 991 776 858";
	// $phone_2 = "+243 819 009 678";

	// $school_name = "Lycée Cirezi";
	// $school_name_abb = "Lycée Cirezi";
	// $devise_school = "Une école de référence";
	// $school_bp = "B.P. 2276 - BUKAVU";
	// $email_school = "lycee.cirezi@gmail.com";
	// $school_city = "Bukavu";
	// $school_province = "Sud-Kivu";
	// $school_commune = "Ibanda";
	// $phone_1 = "+243 971 776 858";
	// $phone_2 = "+243 819 009 678";

	// $school_name = "Lycée Wima";
	// // $school_name_abb = "Masomo";
	// $school_name_abb = "Lycée Wima";
	// $devise_school = "Une école de référence";
	// $school_bp = "B.P. 135 - BUKAVU";
	// $email_school = "lycee.wima@gmail.com";
	// $school_city = "Bukavu";
	// $school_province = "Sud-Kivu";
	// $school_commune = "Kadutu";
	// $phone_1 = "+243 994 108 986";
	// $phone_2 = "+243 971 995 370";

	$school_name = "Complexe Scolaire \"Les Progrès\"";
	// $school_name_abb = "Masomo";
	$school_name_abb = "C. S. Les Progrès";
	$devise_school = "Une école de référence";
	$school_bp = "B.P. - BUKAVU";
	$email_school = "cs.lesprogres@gmail.com";
	$school_city = "Bukavu";
	$school_province = "Sud-Kivu";
	$school_commune = "Ibanda";
	$phone_1 = "+243 994 108 986";
	$phone_2 = "+243 971 995 370";

	// $school_name = "Collège Saint-Paul";
	// $school_name_abb = "Collège Saint-Paul";
	// $devise_school = "Labore et Caritate";
	// $school_bp = "B.P. 2276 - BUKAVU";
	// $email_school = "college.saintpaul@gmail.com";

	// $school_city = "Bukavu";
	// $school_province = "Sud-Kivu";
	// $school_commune = "Ibanda";
	// $phone_1 = "+243 971 776 858";
	// $phone_2 = "+243 819 009 678";

	// $school_city = "Bukavu";
	// $school_province = "Sud-Kivu";
	// $school_commune = "Kadutu";
	// $phone_1 = "+243 994 108 986";
	// $phone_2 = "+243 971 995 370";

    // Info about the connected member
    // Creating a function finding out all info about the connected member
	// function user_connected($user)
	// {
	// 	global $database_connect;
	// 	$session = array(
	// 		'user_id' => $_SESSION['user_connected']);
	// 	$query = 'SELECT * FROM users WHERE user_id=:user_id';
	// 	$request = $database_connect->prepare($query);
	// 	$request->execute($session);
	// 	$user = $request->fetchObject();

	// 	return $user;
	// }


    // function session_in()
	// {
	// 	if(isset($_SESSION['user_connected']))
	// 	{
	// 		$logged = $_SESSION['user_connected'];
	// 	}
	// 	else
	// 	{
	// 		$logged = 0;
	// 	}

	// 	return $logged;
    // }

	// function session_worker()
	// {
	// 	if(isset($_SESSION['worker_connected']))
	// 	{
	// 		$logged = $_SESSION['worker_connected'];
	// 	}
	// 	else
	// 	{
	// 		$logged = 0;
	// 	}

	// 	return $logged;
    // }

    // function detele_unfilled_class () {
    // 	global $database_connect;

    // 	$dr = "DELETE FROM classes_completed";
	// 	$dr1 = $database_connect->query($dr);

    // 	$query = "SELECT * FROM pupils_info";
    // 	$request = $database_connect->query($query);
    // 	while($response = $request->fetchObject()) {
	// 		// $dr = "DELETE FROM classes_completed WHERE cycle_id=? AND class_id!=? AND order_id!=? AND section_id!=? AND option_id!=? AND school_year!=?";

	// 		$classes_alignment = "$response->cycle_school, $response->class_school, $response->class_order, $response->class_section, $response->class_option, $response->school_year";

	// 		insert_class_completed($response->cycle_school, $response->class_school, $response->class_order, $response->class_section, $response->class_option, $response->school_year, $classes_alignment);
	// 	}
	// }

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

	function delete_pupils() {
		global $database_connect;

		$query = "SELECT * FROM pupils_info WHERE is_inactive=1";
    	$request = $database_connect->query($query);
    	while($response = $request->fetchObject()) {
			$paiementsDeleteQuery = "DELETE FROM paiements WHERE pupil_id=?";
			$paiementsDeleteRequest = $database_connect->prepare($paiementsDeleteQuery);
			$paiementsDeleteRequest->execute(array($response->pupil_id_bis));

			$paiementsDeleteQuery = "DELETE FROM pupils_info WHERE is_inactive=?";
			$paiementsDeleteRequest = $database_connect->prepare($paiementsDeleteQuery);
			$paiementsDeleteRequest->execute(array(1));
		}
	}

	make_users();
	delete_pupils();

?>
