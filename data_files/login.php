<?php

    if(session_in() == 1)
    {
        Header("Location: index.php?_=home.promotor");
    }
    if(session_in() == 2)
    {
        Header("Location: index.php?_=home.director");
    }
    if(session_in() == 3)
    {
        Header("Location: index.php?_=home.finance");
    }
    if(session_in() == 4)
    {
        Header("Location: index.php?_=home.secretor");
    }
    if(session_in() == 5)
    {
        Header("Location: index.php?_=home.teacher");
    }
    if(session_in() == 6)
    {
        Header("Location: index.php?_=home.livre_caisse");
    }
    if(session_in() == 7)
    {
        Header("Location: index.php?_=home.study.director");
    }

    //detele_unfilled_class();

    insert_promotor();
    insert_director();
    insert_studies_director();
    insert_daf();
    insert_secretor();
    insert_teacher();
    insert_caissier();
    enterPupilsIdentification();

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
                if($poste == 1)
                {
                    $_SESSION["user_connected"] = 1;
                    Header('Location: index.php?_=home.promotor');
                }
                else if($poste == 2)
                {
                    $_SESSION["user_connected"] = 2;
                    Header('Location: ./?_=home.director');
                }
                else if($poste == 3)
                {
                    $_SESSION["user_connected"] = 3;
                    Header('Location: ./?_=home.finance');
                }
                else if($poste == 4)
                {
                    $_SESSION["user_connected"] = 4;
                    Header('Location: ./?_=home.secretor');
                }
                else if($poste == 5)
                {
                    $_SESSION["user_connected"] = 5;
                    Header('Location: ./?_=login_employees');
                }
                else if($poste == 6)
                {
                    $_SESSION["user_connected"] = 6;
                    Header('Location: ./?_=home.livre_caisse');
                }
                else if($poste == 7)
                {
                    $_SESSION["user_connected"] = 7;
                    Header('Location: ./?_=home.study.director');
                }
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
        <div class="title_div"><h1 class="title_main"><?= $school_name?>, <?= $devise_school ?></h1></div><br/><br/>
        <form method="POST" class="container_pushed">
            <div class="logo_right_sub_container">
                <img class="logo_login" src=" <?php if ($school_name == "Collège Alfajiri") {echo "images/other/Alfajiri.jpeg";} else if($school_name == "Collège Saint-Paul") {echo "images/other/Saint_Paul.jpeg";} else if($school_name == "Complexe Scolaire \"Elite\"") {echo "images/other/logo.png";} else {echo "images/other/user-3.png";} ?>" width="140" height="180" />
            </div>
            <h3 class="title_main_container">Connectez-vous en tant que</h3>
            <select class="select_poste" name="select_poste">
            <option value="" class="login_item"> -- Sélectionner ici --</option>
                <option value="1" class="login_item">
                    <?php if ($school_name != "Collège Alfajiri") {echo "Promoteur l'école";} else {echo "Recteur du Collège";} ?>
                </option>
                <option value="2" class="login_item">Directeur de discipline</option>
                <?php 
                if ($school_name == "Collège Alfajiri" || $school_name == "Collège Saint-Paul") {
                    ?>
                    <!-- <option value="7" class="login_item">Directeur des études</option> -->
                    <?php
                } ?>
                <option value="7" class="login_item">Directeur des études</option>
                <option value="4" class="login_item">Secrétaire de l'école</option>
                <option value="3" class="login_item">Directeur de finance</option>
                <option value="5" class="login_item">Enseignant</option>
                <option value="6" class="login_item">Caissier/Caissière</option>
            </select>
            <br/><br/><input type="password" placeholder="Votre mot de passe" name="password_login" id="password_login">
            <br/><br/><button class="validate_login" name="validate_login" type="submit">Connexion</button>
        </form>
    </div>
</div>
