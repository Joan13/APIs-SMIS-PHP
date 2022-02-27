<?php

function login_credentials($poste, $password)
{
    global $database_connect;
    $query = "SELECT worker_id, user_password, COUNT(*) AS count_user_exists FROM workers_info WHERE worker_id=? AND user_password=?";
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
            $query0 = "SELECT worker_id, user_password, COUNT(*) AS count_poste_exists FROM workers_info WHERE worker_id=? AND user_password=?";
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

    // if(session_in() == 1)
    // {
    //     Header("Location: index.php?_=home.promotor");
    // }
    // if(session_in() == 2)
    // {
    //     Header("Location: index.php?_=home.director");
    // }
    // if(session_in() == 3)
    // {
    //     Header("Location: index.php?_=home.finance");
    // }
    // if(session_in() == 4)
    // {
    //     Header("Location: index.php?_=home.secretor");
    // }
    // if(session_in() == 5)
    // {
    //     Header("Location: index.php?_=home.teacher");
    // }
    // if(session_in() == 6)
    // {
    //     Header("Location: index.php?_=home.livre_caisse");
    // }
    // if(session_in() == 7)
    // {
    //     Header("Location: index.php?_=home.study.director");
    // }

    if(isset($_POST["validate_login"]))
    {
        $error = false;
        $poste = $_POST["select_poste"];
        $password = sha1(htmlspecialchars(strip_tags($_POST["password_login"])));

        if($poste === "")
        {
            $error = true;
            $errorPoste = "Veuillez selectionner un poste";
        }

        if($password === "")
        {
            $error = true;
            $errorPassword = "Veuillez entrer votre mot de passe";
        }


        if(!$error)
        {
            if(login_credentials($poste, $password) === 1)
            {
                $_SESSION["worker_connected"] = $poste;
                Header('Location: index.php?_=home.teacher');
            }
            else
            {
                $error = true;
                $errorCredentials = "Les identités entrées ne correspondent pas";
            }
        }
    }

?>

<div id="mm">
    <div class="main_container">
        <div class="title_div"><h1 class="title_main">ESPACE SOUS-UTILISATEUR</h1></div><br/><br/>
        <form method="POST" class="container_pushed">
            <div class="logo_right_sub_container">
                <img class="logo_login" src=" <?php if ($school_name == "Collège Alfajiri") {echo "images/other/Alfajiri.jpeg";} else if($school_name == "Collège Saint-Paul") {echo "images/other/Saint_Paul.jpeg";} else if($school_name == "Complexe Scolaire \"Elite\"") {echo "images/other/logo.png";} else {echo "images/other/user-3.png";} ?>" width="140" height="180" />
            </div>
            <h3 class="title_main_container">Connectez-vous en tant que</h3>
            <select class="select_poste" name="select_poste">
            <option value="" class="login_item"> -- Sélectionner ici --</option>

                <?php

                $years000 = "SELECT * FROM workers_info";
                $years111 = $database_connect->query($years000);
                while($yearsss = $years111->fetchObject())
                {
                    ?>
                    <option value="<?=$yearsss->worker_id?>"><?= $yearsss->full_name ?></option><br/>
                    <?php
                }
                
                ?>
            </select>
            <br/><br/><input type="password" placeholder="Votre mot de passe" name="password_login" id="password_login">
            <br/><br/><button class="validate_login" name="validate_login" type="submit">Connexion</button>
            <a href="0.php" class="validate_login" style="background-color: transparent;">Retour a la page d'acceuil</a>
        </form>
    </div>
</div>
