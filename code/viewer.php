<?php 
    $pagename="Articolo";
    include_once( "include/connect.inc.php");
    if(isset($_SESSION[ "logName"])) $usern=$_SESSION[ "logName"];
    if(isset($_GET[ 'id'])){ 
        $id=$_GET[ 'id'];
        $basicinfo=Database::getInstance()->getArticleinfo($id);
    }

    if(isset($usern)){
        if(isset($_POST["like"])) Database::getInstance()->likePressed($id, $usern, "L");
        else if(isset($_POST["dlike"])) Database::getInstance()->likePressed($id, $usern, "D");
        else if(isset($_POST["report"])){ 
            if(Database::getInstance()->addReport($id, $usern)) $conferma="Articolo Segnalato agli Amministratori.";
        }
        else if(isset($_POST["ncomm"])){
            if(Database::getInstance()->addComment($id, $usern, $_POST["comm"])) $conferma="Commento aggiunto correttamente.";
            else $errore="Commento non inserito.";
        }
        else if(isset($_POST["delcomm"])){
            if(Database::getInstance()->deleteInteraction($id,$_POST["commid"])) $conferma="Commento eliminato correttamente.";
            else $errore="Commento non eliminato.";
        }
    }

    if(isset($usern)) $user=Database::getInstance()->getUserInfo($usern);
    $blockses= Database::getInstance()->getArticleBlocks($id);
    $comms= Database::getInstance()->getArticleComments($id,"DESC",250);
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Articolo | TumbList</title>
        <meta charset="UTF-8" />
        <meta name="description" content="<?php if(isset($basicinfo) && $basicinfo["Pubblico"]) echo $basicinfo['Titolo']; else echo "Articolo";?>">
        <meta name="author" content="D, F, M">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php  if(isset($basicinfo) && $basicinfo["Pubblico"]){ ?>
        <meta property="og:url" content="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?id=".$id); ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo str_replace("\"","&quot;",$basicinfo[ 'Titolo']);?>" />
        <meta property="og:description" content="<?php echo str_replace("\"","&quot;",$basicinfo[ 'Sottotitolo']);?>" />
        <meta property="og:image" content="<?php echo $_SERVER['HTTP_HOST']."/img/".$basicinfo['LinkFig'];?>" />
<?php } ?>
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
            <div class="corpo flex foglio">
                <?php  if(isset($basicinfo) &&  ($basicinfo["Pubblico"] || $usern== $basicinfo["Autore"] || $role== 'A')){?>
                <div class="articlelist">
                    <article class="prefazione">
                        <h2 tabindex="0"><?php echo $basicinfo['Titolo'];?></h2>
                        <p class="sottotitolo" tabindex="0"><?php echo $basicinfo[ 'Sottotitolo'];?></p>
<?php  if($basicinfo[ 'LinkFig']!="" ){ ?>
                        <div class="imgcontainer">
                            <img src="img/article_image/<?php echo $basicinfo['LinkFig'];?>" alt="<?php echo $basicinfo['AltFig'];?>" />
                        </div>
<?php  } ?>
                        <p tabindex="0"><?php echo $basicinfo[ 'Descrizione'];?></p>
                    </article>
                    <hr/>
<?php  foreach($blockses as $blocks){ ?>
                    <article>
                        <h2 tabindex="0"><?php echo $blocks['Nome'];?></h2>
<?php if($blocks[ 'LinkImg']!="" ){?>
                        <div class="imgcontainer">
                            <img src="img/article_image/<?php echo $blocks['LinkImg'];?>" alt="<?php echo $blocks['AltImg'];?>" />
                        </div>
<?php } ?>
                        <p tabindex="0"><?php echo $blocks[ 'Etichetta'];?></p>
                    </article>
<?php  } ?>
                </div>
                <div class="infopost">
                    <h1 tabindex="0">Info Articolo</h1>
                    <div class="authorbox"><img src="img/<?php if(isset($basicinfo['LinkImg']))echo "user_avatar/".$basicinfo['LinkImg'];else echo"avatar.png";?>" alt="Avatar dell'autore <?php echo $basicinfo['Username'];?>" />
                    </div>
                    <p tabindex="0">Articolo di <?php echo $basicinfo[ 'Username'];?><br/>
                        creato il <?php echo date( "d-m-Y", strtotime($basicinfo['DataCreazione']));?></p>
                    <p class="appartenenza" tabindex="0">Categoria <?php  echo $basicinfo[ 'Categoria'];?></p>
                    <p tabindex="0">Biografia autore:<br/><?php if($basicinfo['Ruolo']!="B") echo $basicinfo[ 'Biografia']; else echo "Utente Bannato";?></p>
<?php if(isset($usern) && ($usern==$basicinfo[ 'Username'] || $role=="A")){?>
                    <a href="editor.php?id=<?php echo $id;?>" title="modifica articolo">Modifica Articolo</a>
<?php }?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id."#like"); ?>"  id="like" method="POST">
<?php  $reactnum=Database::getInstance()->getReactionNumber($id); ?>
                        <p class="punteggio" tabindex="0"> Apprezzamenti: <?php  echo $reactnum[ "L"]; ?><br/>Disprezzamenti: <?php  echo $reactnum[ "D"]; ?></p>
<?php if(isset($usern)) $react=Database::getInstance()->getLikes($id, $usern);
if(isset($react))if($react=="L" || $react=="D") echo"Hai ".internotify($react)." questo articolo.";
if(isset($usern)){?>
                        <div class="fieldset">
                            <input type="submit" title="Apprezza Articolo" class="reazione like" name="like" value="Bello" />
                            <input type="submit" title="Disprezza Articolo" class="reazione dislike" name="dlike" value="Brutto" />
                        </div>
                    </form>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id); ?>" method="POST" onSubmit="return signalValidation()">
                        <div class="fieldset">
                            <input type="submit" title="Segnala Articolo" name="report" class="reazione" value="Segnala Articolo" />
                        </div>
<?php } ?>
                    </form>
                    <hr/>
                    <h1 tabindex="0">Commenti</h1>
                    <div class="commenti">
<?php  foreach($comms as $comm){ ?>
                        <div class="commento">
                            <div class="avatarbox"><img src="img/<?php if(isset($comm['LinkImg']))echo "user_avatar/".$comm['LinkImg'];else echo"avatar.png";?>" alt="Avatar di <?php echo $comm['NickIns'];?>" />
                            </div>
                            <div class="text">
                                <p class="time" tabindex="0"><?php echo $comm[ 'Data'];?> di <?php echo $comm[ 'NickIns'];?></p>
                                <p tabindex="0"><?php  echo $comm[ 'Testo'];?></p>
                            </div>
<?php  if(isset($usern) && ($comm['NickIns']==$usern || $role=="A")){ ?>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id); ?>" method="POST">
                                <fieldset>
                                    <input type="hidden" name="commid" value="<?php  echo $comm['Id']; ?>" />
                                    <input type="submit" name="delcomm" title="Elimina Commento" value="X" />
                                </fieldset>
                            </form>
<?php } ?>
                        </div>
<?php  } ?>
                    </div>
<?php  if (isset($usern) && $role!="B") {?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id); ?>" method="POST" class="commento">
                        <div class="avatarbox"><img src="img/<?php if(isset($user['LinkImg']))echo "user_avatar/".$user['LinkImg'];else echo"avatar.png";?>" alt="Avatar di <?php echo $usern;?>" />
                        </div>
                        <div class="text">
                            <p class="time" tabindex="0">NUOVO COMMENTO</p>
                            <fieldset>
                                <textarea name="comm" placeholder="Scrivi un commento..." rows="4" maxlength="250"></textarea>
                                <input type="submit" name="ncomm" title="Pubblica Commento" value="Pubblica Commento" />
                            </fieldset>
                        </div>
                    </form>
<?php  } } else echo "Questo articolo non esiste o non possiedi i permessi per visualizzarlo!"; ?>
                </div>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>
