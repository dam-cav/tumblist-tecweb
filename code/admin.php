<?php 
    $pagename="Admin";
    include_once( "include/connect.inc.php");
    if(!isset($usern) || $role!="A"){
        header("Location: reglogin.php");
        exit();
    }
    else $user=Database::getInstance()->getUserInfo($usern);
    if(isset($_POST['ban'])) Database::getInstance()->banUser($_POST['targetUser']);
    else if(isset($_POST['unban'])) Database::getInstance()->unbanUser($_POST['targetUser']);
    else if(isset($_POST['setAdmin'])) Database::getInstance()->userToAdmin($_POST['targetUser']);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Admin | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Pannello Admin" />
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
                    <div class="avatar"><img src="img/<?php if(isset($user['LinkImg']))echo"user_avatar/".$user['LinkImg'];else echo"avatar.png ";?>" alt="Avatar di <?php echo $user['Username'];?>" />
                    </div>
                    <div class="username" >
                        <h1 tabindex="0">Pannello Admin di <?php echo $usern;?></h1>
                        <hr/>
                    </div>
                    <div class="options">
                        <a href="profilo.php" title="Torna al Profilo">
                            <img src="img/icon/settings.svg" alt="Icona Torna al Profilo" />
                            <p >Torna al Profilo</p>
                        </a>
                    </div>
                </div>
                <div class="boxes">
<?php  if(isset($_GET[ 'list'])){ 
        if($_GET[ 'list']=="S" ){?>
                    <h1 tabindex="0">Articoli Segnalati</h1>
                    <div class="interabox bigger">
<?php   foreach(Database::getInstance()->getMostSignal() as $art){ ?>
                        <div class="interaction type">
                            <div class="ico report"></div>
                            <p tabindex="0">Articolo "
                                <a href="viewer.php?id=<?php echo $art['Id'];?>" title="Articolo <?php echo $art['Titolo'];?>">
                                    <?php echo truncate($art[ 'Titolo'],80);?>
                                </a>" di
                                <?php echo $art[ "Autore"]?>
                            </p>
                        </div>
                        <a class="interaction adminoptions" href="viewer.php?id=<?php echo $art["Id"];?>" title="Vai all'articolo">
                            <?php echo $art[ 'Gravita'];?> Utenti</a>
<?php   }?>
                    </div>
<?php }
        if($_GET[ 'list']=="U" ) {?>
                    <h1 tabindex="0">Lista utenti</h1>
                    <div class="interabox bigger">
<?php   foreach(Database::getInstance()->getUSerList() as $user){ ?>
                        <div class="interaction type">
                            <div class="ico user"></div>
                            <p tabindex="0"><?php echo $user["Username"];?>, Iscritto il <?php echo date( "d-m-Y", strtotime($user["DataIscrizione"]));?>, Ruolo: <?php echo userrole($user["Ruolo"]);?></p>
                        </div>
                        <form name="userManagement" class="interaction useroptions" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?list=".$_GET['list']); ?>" method="POST" enctype="multipart/form-data" onSubmit="return makeAdminValidation();">
                            <div class="fieldset">
                                <input type="hidden" name="targetUser" value="<?php  echo $user['Username'];?>" />
<?php if($user[ 'Ruolo']!="A" ){?>
<?php   if($user[ 'Ruolo']=="U" ){?>
                                <button type="submit" name="ban" title="Espelli Utente" onClick="Clicked(this);"><img src="img/icon/ban.svg" alt="Espelli Utente"/></button>
<?php   }else{?>   
                                <button type="submit" name="unban" title="Riammetti Utente" onClick="Clicked(this);"><img src="img/icon/unban.svg" alt="Riammetti Utente"/></button>
<?php   }?>
                                <button type="submit" name="setAdmin" title="Rendi Admin" onClick="Clicked(this);"><img src="img/icon/admin.svg" alt="Rendi Admin"/></button>
<?php   }else {?>
                                    <p tabindex="0">ADMIN</p>
<?php   }?>
                            </div>
                        </form>
<?php   }?>
                    </div>
<?php  } } else{ ?>
                            <div class="adminbox">
                                <h1 tabindex="0">Statistiche Articoli</h1>
                                <div class="stat">
                                    <div>
                                        <p tabindex="0">
                                            <?php echo Database::getInstance()->getNumArt();?></p>
                                        <p tabindex="0">ARTICOLI</p>
                                    </div>
                                    <div>
                                        <p tabindex="0">
                                            <?php echo Database::getInstance()->getNumElem();?></p>
                                        <p tabindex="0">ELEMENTI</p>
                                    </div>
                                </div>
                                <hr/>
                                <a href="admin.php?list=S" title="Gestione Articoli"><p>Gestione Articoli</p></a>
                            </div>
                            <div class="adminbox">
                                <h1 tabindex="0">Statistiche Utenti</h1>
                                <div class="stat">
                                    <div>
                                        <p tabindex="0">
                                            <?php echo Database::getInstance()->getNumUsers();?></p>
                                        <p tabindex="0">UTENTI</p>
                                    </div>
                                    <div>
                                        <p tabindex="0">
                                            <?php echo Database::getInstance()->getNumComm();?></p>
                                        <p tabindex="0">COMMENTI</p>
                                    </div>
                                </div>
                                <hr/>
                                <a href="admin.php?list=U" title="Gestione Utenti"><p>Gestione Utenti</p></a>
                            </div>
<?php  } ?>
                </div>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>