<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="fonctions.js"></script>
</head>
<body>
    <header>
        <?php
            include "configBD.php";
            if(isset($_COOKIE['login'])){                       // Utilisateur connecté
                echo "<p>Bienvenue ".$_COOKIE['login']."</p>";
            }
            else{
                if($_POST['submitConnect'] == "Connexion"){  // Demande de connexion envoyée

                    try{
                        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
                    }
                    catch(Exception $e){
                        exit($e->getMessage());
                    }
                   
                    // Requete du mot de passe
                    $sql="SELECT password FROM Utilisateurs WHERE login = ? ;";
                    $resultat = $pdo->prepare($sql);
                    $resultat->execute([$_POST['login']]);
                    foreach ($resultat as $row){
                        $pswd = $row[0];
                    } 

                    if (password_verify($_POST['password'], $pswd)){   // Le mot de passe correspond
                        echo "<p>Bienvenue ".$_POST['login']."</p>";
                        setcookie("login", $_POST['login'], time()+3600);
                    }
                    else{
                        echo "<p>Erreur de connexion</p>";
                    }
                    unset($_POST['submitConnect']);
                }
                else{                                               // Affichage du formulaire de connection
                    echo "<form method=\"post\" id=\"loginForm\">
                            <legend>Login</legend>
                            <input type=\"text\" name=\"login\">
                            <legend>Mot de passe</legend>
                            <input type=\"password\" name=\"password\">
                            <input type=\"submit\" value=\"Connexion\" name=\"submitConnect\">
                        </form>
                        <form action=\"creerCompte.php\" id=\"signinForm\">
                            <button type=\"submit\">Créer un compte</button>
                        </form>";
                }
            }

        ?>
    </header>
    <div class="main">
        <nav id="nav">
            <div id="filAriane"></div>
            <div id="hierarchie"></div>
        </nav>
        <main>
            Vestibulum eget tellus sit amet erat ullamcorper semper. Sed a faucibus enim. Vestibulum ut bibendum lacus, et pharetra purus. Integer convallis velit nec metus lobortis, sed mattis dolor efficitur. Nam at lacus nisl. Vestibulum euismod interdum eros, nec molestie ligula aliquam a. Aliquam augue dolor, fermentum non nisl semper, blandit aliquet mauris.
            Donec at posuere nibh, a malesuada enim. Quisque et nulla ullamcorper, fringilla purus id, euismod ligula. Etiam egestas volutpat urna, id eleifend quam lobortis nec. Phasellus accumsan ornare nibh at convallis. Phasellus nec quam consectetur ligula consectetur ultricies. Nunc eget dignissim lorem. Aliquam malesuada eros et ligula auctor semper. Maecenas tempus sem in purus porttitor finibus. Maecenas nisl libero, gravida sit amet blandit et, mollis blandit arcu. Quisque dictum eros quis augue viverra tincidunt. Sed quis vestibulum turpis.
            Phasellus sit amet condimentum ligula, ac dictum mi. Quisque ut nisi enim. Aenean tincidunt nunc vitae felis convallis, in vestibulum nisl convallis. Donec fringilla quam quam, a gravida ligula egestas in. Fusce lobortis finibus malesuada. Nunc sit amet consequat libero. Nulla bibendum, ipsum ut bibendum fermentum, purus turpis euismod urna, at molestie lacus nulla in mauris. Proin at ipsum ut dui cursus blandit.
            Quisque ut bibendum nunc, at venenatis nibh. Fusce id sollicitudin magna. Donec faucibus magna at orci commodo, et egestas leo consectetur. Integer malesuada convallis nisi eu placerat. Morbi efficitur, tortor vel ullamcorper rhoncus, felis urna fermentum enim, sit amet bibendum enim est rhoncus nunc. In cursus in augue vitae sollicitudin. Donec ac maximus sapien, id facilisis tortor. Vestibulum ac tincidunt elit.
            In nisi tellus, tincidunt sed mi nec, viverra laoreet turpis. Morbi consequat ipsum sagittis vehicula tincidunt. Donec consequat, turpis eget tincidunt gravida, urna enim dapibus libero, eget tincidunt nisi ante nec risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur dapibus finibus pretium. Aliquam eget placerat felis. Morbi aliquet dui id tortor gravida dictum. Aenean arcu ipsum, cursus et tempus ut, interdum vel ex. Sed accumsan elit nec justo fringilla euismod. Ut molestie tortor eu tortor semper, eu mattis leo bibendum. Integer hendrerit, felis eget lacinia tincidunt, quam libero cursus leo, a fermentum velit mi posuere eros. Aliquam sit amet est ac odio finibus imperdiet. Nulla lacinia ullamcorper ex a varius. Morbi consectetur porttitor neque. Maecenas sed imperdiet libero.
            Sed turpis ex, dignissim in fermentum non, feugiat a odio. Donec tristique urna tellus, interdum volutpat tellus ultricies dapibus. Nulla arcu metus, interdum in nisi eget, convallis euismod libero. Sed ac rutrum lacus. Aenean in dolor at turpis efficitur rutrum et ut ligula. Pellentesque mi purus, ornare ac pulvinar ut, dapibus commodo erat. Vestibulum dui eros, consectetur nec turpis sed, maximus facilisis tellus. Vivamus augue ligula, accumsan ac tempor a, vulputate vel purus. Donec eget sagittis sem, a consequat urna.
            Nunc vehicula nec ipsum id pellentesque. Quisque risus libero, vehicula eu ultrices vitae, tristique vitae tortor. Nunc feugiat convallis libero, quis pulvinar tellus posuere at. Morbi et varius justo. Phasellus congue et urna sed pharetra. Pellentesque id elit suscipit, pretium augue ut, malesuada nisl. Duis vel rutrum odio, id porttitor enim. Sed elementum at odio vitae ultrices. Ut eu sollicitudin dui, eu vestibulum est. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut dictum feugiat scelerisque. Integer vulputate nisi urna, eu varius ipsum semper condimentum. Sed interdum lacus id porta dignissim. Quisque eu metus in massa auctor egestas.
            In id dui diam. Sed bibendum lacus in porttitor laoreet. Sed vitae tristique ligula. In hac habitasse platea dictumst. Pellentesque nec lectus nec massa convallis pellentesque. Vestibulum venenatis leo et mattis pharetra. Nunc faucibus dictum orci, consectetur placerat erat elementum a. Sed ac arcu viverra, euismod massa tempor, varius quam. Fusce iaculis quam et mauris accumsan, a bibendum tortor bibendum.
            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In blandit fringilla elit vel maximus. Nunc maximus, nisl in venenatis vehicula, nunc augue blandit purus, id pharetra quam dui non velit. Phasellus sed tristique leo. Integer sit amet vehicula ex. Quisque tempor ex porta est mattis, non sagittis turpis suscipit. Vivamus sit amet nulla non dolor blandit ullamcorper quis non mauris. Nam ac rhoncus augue. Praesent suscipit feugiat arcu, sit amet tincidunt diam pulvinar sit amet. Donec euismod lacus ut sollicitudin facilisis. Suspendisse auctor egestas risus eget facilisis. Quisque et condimentum ligula, a lobortis justo.
            Aenean iaculis sapien sit amet orci egestas blandit tincidunt vitae nisl. Phasellus eleifend sollicitudin lacus eget tempus. Etiam efficitur nulla arcu, ac lobortis lacus dictum non. Nulla finibus ultricies ultrices. In vel nisi massa. Proin sit amet urna porta, condimentum neque et, pulvinar lectus. Phasellus elementum mauris lacus, quis suscipit odio convallis sit amet. In maximus at augue eget cursus. Duis mollis mollis nisl, nec sollicitudin nulla blandit in. Donec varius magna sem, sit amet hendrerit justo ornare nec. Fusce quis massa vel quam interdum rhoncus in sit amet ipsum. Phasellus ut condimentum augue. Nulla quis nisl orci.
            Ut posuere ultricies ante, pulvinar interdum nibh interdum eu. Pellentesque accumsan tellus non metus congue tristique. Maecenas luctus maximus est, vel fermentum tellus mollis nec. Vestibulum facilisis nunc at sem vestibulum mattis. Vestibulum iaculis neque non rhoncus fringilla. Curabitur auctor aliquam ex, et cursus tellus tincidunt sed. Maecenas hendrerit viverra varius. Praesent iaculis hendrerit efficitur. Ut porttitor placerat rutrum. Cras finibus dolor est, nec vestibulum quam tempus id. Praesent lobortis justo eu ligula ornare viverra. Nam ullamcorper auctor ex eu scelerisque.
            Phasellus ornare, quam et porttitor elementum, ante justo mollis libero, ac tincidunt nisl eros hendrerit turpis. Sed porta massa mauris, quis lacinia nunc condimentum vitae. Etiam vehicula blandit orci vel varius. Vivamus vitae venenatis eros. Fusce euismod nec sapien vitae auctor. Praesent auctor semper risus. Suspendisse quis augue interdum massa euismod molestie et ac nibh. Nulla egestas odio sed purus viverra euismod. Fusce placerat orci dolor, non vehicula quam ornare eget. Donec maximus, quam eu cursus consectetur, diam nisl tempus ligula, id ullamcorper felis lectus in leo. Donec a ipsum rhoncus mi dictum porta. Suspendisse vehicula ante eu quam condimentum, a mattis diam consequat.
            Nullam vitae risus eget est cursus maximus vitae quis ex. Nam cursus leo sem, venenatis varius quam sollicitudin ac. Quisque finibus tortor non lacus aliquet, id maximus erat venenatis. Aenean dictum tellus metus, et maximus risus ullamcorper sed. Integer ut accumsan felis, ac viverra felis. Suspendisse faucibus ac arcu at blandit. Ut est nibh, faucibus non turpis vitae, commodo ullamcorper quam. Aenean vitae tincidunt dui. Integer venenatis pellentesque ante vel sodales. Aliquam erat volutpat. Nulla accumsan maximus lorem, sed facilisis ex aliquam porttitor. Nunc elementum urna id ultricies faucibus. Maecenas sit amet orci scelerisque, congue leo eu, laoreet nisi. Pellentesque sit amet enim vitae lacus varius cursus ac non erat. Donec sit amet dolor erat.
            Aenean vitae ligula eget dui placerat consequat non sit amet mi. Pellentesque at auctor justo. Nunc sed augue at velit bibendum tempor ut sit amet libero. Nam aliquam metus nibh, ut vestibulum libero hendrerit eget. Quisque iaculis iaculis nulla. Cras quis mattis felis. Mauris maximus neque sit amet velit porta, in volutpat tellus dictum. Quisque quis purus dolor. Etiam velit ligula, venenatis sed sem at, malesuada aliquet tortor. Mauris fringilla quam non libero ornare, ac commodo dui semper. Quisque rutrum sapien eget tempus consectetur. Etiam ultricies, est non vestibulum imperdiet, mauris augue eleifend est, lacinia tristique nisl sem at velit. Etiam quis sem sit amet enim pulvinar iaculis ac vel nulla.
            Vivamus ac pellentesque ex, sodales aliquam nisl. Aenean volutpat nibh in aliquet iaculis. Duis pellentesque elit et orci vulputate tempus. In facilisis tristique egestas. Nullam sed maximus ex. Curabitur sit amet placerat velit, non dictum sapien. Sed tincidunt quam vitae posuere faucibus. Proin at iaculis ligula. Ut convallis quam elit, id laoreet sapien rutrum eu. Mauris congue eros magna, et pulvinar lacus bibendum non. Sed malesuada lorem vel aliquet consequat. Sed commodo diam eu egestas laoreet. Donec mollis enim sed quam luctus molestie. 
        </main>
    </div>
</body>
</html>