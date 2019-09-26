<?php 
    $pagename="Login" ; 
    include_once( "include/connect.inc.php"); 
    if (isset($usern)){
        header("Location: profilo.php");
        exit();
    }
    if (isset($_POST[ "login"])){
        if(Database::GetInstance()->tryLog($_POST["name"], $_POST["pass"])){
            header("Location: profilo.php");
            exit();
        }
        else $errore="Durante durante il tentativo di Login, hai inserito una coppia nome utente e password non corretta.";
    } 
    else if (isset($_POST["reg"])){
        if($_POST["pass1"]==$_POST["pass2"]){
            if(strlen($_POST["pass1"])>=4){
                if(filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)){
                    if(strlen($_POST["name"])>=2){
                        if ($_POST["name"]==strip_tags($_POST["name"])){
                            if(preg_match("/^([a-zA-Z]|[0-9])([a-zA-Z]|[0-9]|[_ ])*$/", $_POST["name"])==1){
                                if(Database::GetInstance()->register($_POST["mail"], $_POST["name"], $_POST["pass1"],$_POST["pass2"])) $conferma="Registrazione avvenuta correttamente, ora puoi effettuare l'accesso.";
                                else $errore = "Il nome utente o la mail inserita potrebbero essere già stati utilizzati";
                            }
                            else  $errore = "Il nome utente non può contenere caratteri diversi da lettere, numeri, spazi o il carattere \"_\"";
                        }
                        else $errore = "Il nome utente non deve contenere tag html";
                    }
                    else $errore = "Il nome utente deve essere lungo almeno 2 caratteri";
                }
                else $errore = "L'indirizzo email non è corretto"; 
            }
            else $errore = "La password Inserita deve essere lunga almeno 4 caratteri";
        }
        else $errore = "Le 2 password inserite non coincidono";
    } 
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Articolo | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Liste">
        <meta name="author" content="D, F, M">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width: 768px)" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="script/script.js"></script>
    </head>
    <body>
<?php include( "include/header.php") ?>
        <div class="container">
<?php include( "include/menu.php") ?>
            <div class="corpo foglio flex">
<?php if(!isset($_SESSION[ "logName"])){?>
                <form name="loginname" class="loginbox" action="<?PHP echo htmlspecialchars(" reglogin.php "); ?>" method="POST" onSubmit="return loginValidation();">
                    <div class="fieldset">
                        <h2>Accedi</h2>
                        <label for="name1">NOME UTENTE</label>
                        <input type="text" title="Inserisci Nome Utente" id="name1" name="name" placeholder="Username" class="logintext" />
                        <label for="pass"><span lang="en">PASSWORD</span></label>
                        <input type="password" title="Inserisci Password" id="pass" name="pass" placeholder="Password" class="logintext" />
                        <input type="submit" title="Effettua Login" id="login" name="login" value="Accedi" class="loginbutton" />
                    </div>
                </form>
                <form name="registrationname" class="loginbox" action="<?PHP echo htmlspecialchars(" reglogin.php "); ?>" method="POST" onSubmit="return registrationValidation();">
                    <div class="fieldset">
                        <h2>Registrati</h2>
                        <label for="mail"><span lang="en">EMAIL</span></label>
                        <input type="text" title="Inserisci Email" id="mail" name="mail" placeholder="email@email.em" class="logintext" />
                        <label for="name">NOME UTENTE</label>
                        <input type="text" title="Inserisci Nome Utente" id="name" name="name" placeholder="Username" class="logintext" />
                        <label for="pass1"><span lang="en">PASSWORD</span></label>
                        <input type="password" title="Inserisci Password" id="pass1" name="pass1" placeholder="Password" class="logintext" />
                        <label for="pass2">RIPETI <span lang="en">PASSWORD</span></label>
                        <input type="password" title="Ripeti Password" id="pass2" name="pass2" placeholder="Ripeti Password" class="logintext" />
                        <input type="submit" title="Effettua Registrazione" id="reg" name="reg" value="Registra" class="loginbutton" />
                    </div>
                </form>
            </div>
<?php } else echo "Hai già effettuato il login";?>
        </div>
 <?php include( "include/footer.php"); ?>
    </body>
</html>
