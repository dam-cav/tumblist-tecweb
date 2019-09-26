<?php
    $pagename="Editor Profilo";
    include_once( "include/connect.inc.php");
    if(!isset($usern)){
        header("Location: reglogin.php");
        exit();
    }
    if(isset($_POST["pass"])){  
        if($_POST["newpass1"]==$_POST["newpass2"]){
            if(strlen($_POST["newpass1"])>=4){
                if(Database::getInstance()->changePass($usern,$_POST["oldpass"],$_POST["newpass1"],$_POST["newpass2"])) $conferma="Password modificata correttamente";
                else $errore="La vecchia password non è stata scritta correttamente.";
            }
            else $errore="La nuova password deve essere formata da almeno 4 caratteri.";            
        }
        else $errore="Le nuove password digitate non coincidono.";
    }
    else if(isset($_POST["changebio"])||isset($_POST["changebio_x"])){
        if($_POST["bio"] == strip_tags($_POST["bio"])){
            if(Database::getInstance()->changeBio($usern,trim($_POST["bio"]))) $conferma = "Biografia aggiornata correttamente.";
            else $errore="Biografia non aggiornata.";
        }
        else $errore="La biografia non può contenere tag html.";
    }
    else if(isset($_POST["newav"])){
        if(Database::getInstance()->setAvatar($_FILES["avatarlink"], $usern)) $conferma = "Avatar cambiato correttamente.";
        else $errore="Avatar non Modificato: formati supportati PNG e JPG, dimensione massima: 500Kb.";
    }
    else if(isset($_POST["delacc"])) {
        if($_POST["oldpass1"]==$_POST["oldpass2"]){
            if(Database::getInstance()->deleteUser($usern, $_POST["oldpass1"],$_POST["oldpass2"])) 
            logout();
            else $errore="La password inserita non è corretta.";
        }
        else $errore="Le password inserite non coincidono.";   
    }
    $user=Database::getInstance()->getUserInfo($usern);
    $numArt=Database::getInstance()->numUserArticle($usern);
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Impostazioni Profilo | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Impostazioni Profilo" />
        <meta name="author" content="D, F, M" />
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
            <div class="corpo">
                <div class="profilo">
                    <div class="avatar"><img src="img/<?php if(isset($user['LinkImg']))echo "user_avatar/".$user['LinkImg'];else echo"avatar.png ";?>" alt="Avatar di <?php echo $user['Username'];?>" />
                    </div>
                    <div class="username">
                        <h1 tabindex="0">Impostazioni profilo di <?php echo $user['Username'];?></h1>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                            <div class="fieldset">
                                <h2>MODIFICA BIOGRAFIA:</h2>
                                <textarea name="bio" rows="2" title="Biografia" maxlength="140"><?php echo $user['Biografia'];?></textarea>
                                <button type="submit" name="changebio" title="Conferma Modifica Biografia" ><img src="img/icon/save.svg" alt="Conferma Modifica Biografia"/></button>
                            </div>
                        </form>
                        <hr/>
                            <ul>
                                <li tabindex="0"><img src="img/icon/emailb.svg" alt="Email" aria-hidden="true" /> <?php echo $user[ 'Mail'];?></li>
                                <li tabindex="0"><img src="img/icon/role.svg" alt="Permessi" aria-hidden="true" /> <?php echo userrole($role);?></li>
                                <li tabindex="0"><img src="img/icon/contract.svg" alt="Articoli Creati" aria-hidden="true" /> <?php echo $numArt ?> Articoli</li>
                            </ul>  
                    </div>
                    <div class="options">
                        <a href="profilo.php" title="Torna al Profilo">
                            <img src="img/icon/settings.svg" alt="Icona Torna al Profilo" />
                            <p>Torna al Profilo</p>
                        </a>
                    </div>
                </div>
                <div class="boxes">
                    <div class="verticalboxes">
                        <div class="settingsbox">
                            <form name="changePass" action="<?php  echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onSubmit="return newPassValidation();">
                                <div class="fieldset">
                                    <h2>Modifica Password:</h2>

                                    <label for="oldpass">INSERISCI VECCHIA <span lang="en">PASSWORD</span></label>
                                    <input type="password" title="Inserisci Vecchia Password" id="oldpass" name="oldpass" placeholder="Vecchia Password"  />

                                    <label for="newpass1">INSERISCI NUOVA <span lang="en">PASSWORD</span></label>
                                    <input type="password" title="Inserisci Nuova" id="newpass1" name="newpass1" placeholder="Nuova Password"  />

                                    <label for="newpass2">RIPETI NUOVA <span lang="en">PASSWORD</span></label>
                                    <input type="password" title="Ripeti Nuova Password" id="newpass2" name="newpass2" placeholder="Nuova Password"  />

                                    <input type="submit" title="Conferma Modifica Password" id="pass" name="pass" value="Conferma Modifica"/>
                                </div>
                            </form>
                        </div>

                        <div class="settingsbox bigger">
                            <form name="deleteAcc" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onSubmit="return deleteAccValidation();">
                                <div class="fieldset">
                                    <h2>Elimina Account:</h2>

                                    <label for="oldpass1">INSERISCI <span lang="en">PASSWORD</span></label>
                                    <input type="password" title="Inserisci Password" id="oldpass1" name="oldpass1" placeholder="Password"  />

                                    <label for="oldpass2">RIPETI <span lang="en">PASSWORD</span></label>
                                    <input type="password" title="Ripeti Password" id="oldpass2" name="oldpass2" placeholder="Ripeti Password"  />

                                    <input type="submit" title="Conferma Eliminazione Account" name="delacc" value="Conferma Eliminazione" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="verticalboxes">
                        <div class="settingsbox"> 
                             <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                                <div class="fieldset">
                                    <h2>Cambio Avatar:</h2> 
                                    <div>                         
                                        <div class="avatar"><img src="img/<?php if(isset($user['LinkImg']))echo "user_avatar/".$user['LinkImg'];else echo"avatar.png";?>" alt="Avatar di <?php echo $user['Username'];?>" />
                                        </div>
                                        <label for="avatarlink">SCEGLI IMMAGINE</label>
                                        <input type="file" name="avatarlink" id="avatarlink" /><input type="submit" title="Conferma Cambio Avatar" name="newav" value="Carica Nuovo Avatar" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include( "include/footer.php"); ?>
    </body>
</html>
