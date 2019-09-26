<?php 
    $pagename="Profilo";
    include_once( "include/connect.inc.php");
    if(isset($_GET[ "act"]))$act=$_GET[ "act"];
    else echo "non vedere";
    if(isset($_SESSION[ "logName"])) $usern=$_SESSION[ "logName"];
    $ricorsioni=false;
    $user=Database::getInstance()->getUserInfo($usern);
    $numArt=Database::getInstance()->numUserArticle($usern);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Profilo | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Liste" />
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
                        <h1>Profilo di <?php echo $user['Username'];?></h1>
                        <p><?php echo $user[ 'Biografia'];?></p>
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
                    <div class="interabox bigger">
<?php switch ($act) { 
        case 'F': ?>
                        <h1>Attivit&agrave; Recenti</h1>
<?php       foreach(Database::getInstance()->getUserActivity($usern, 100) as $u_act){
                $ricorsioni=true; ?>
                        <div class="interaction type">
                            <div class="ico <?php echo intericon($u_act['Tipo']);?>"></div>
                            <p>Hai <?php echo internotify($u_act[ 'Tipo']);?> l'articolo "<a href="viewer.php?id=<?php echo $u_act['IdL'];?>" title="Articolo <?php echo $u_act['Titolo'];?>"><?php echo truncate($u_act[ 'Titolo'],80);?></a>"</p>
                        </div>
                        <div class="interaction time">
                            <p><?php echo date( "d-m-Y", strtotime($u_act[ 'Data']));?></p>
                        </div>
<?php       } if(!$ricorsioni){ ?>
                        <div>Nulla da Mostrare</div>
<?php       } ?>
                    </div>
<?php       break; 
            case 'R': ?>
                        <h1>Interazioni sui Tuoi Articoli</h1>
<?php       foreach(Database::getInstance()->getUserArticleAct($usern, 100) as $u_aa){
                $ricorsioni=true; ?>
                        <div class="interaction type">
                            <div class="ico <?php echo intericon($u_aa['Tipo']);?>"></div>
                                <p><?php echo $u_aa[ 'NickIns'];?> ha <?php echo internotify($u_aa[ 'Tipo']);?> la tua lista "<a href="viewer.php?id=<?php echo $u_aa['IdL'];?>" title="Articolo <?php echo $u_act['Titolo'];?>"><?php echo truncate($u_aa[ 'Titolo'],80);?></a>"</p>    
                            </div>
                            <div class="interaction time">
                                <p><?php echo date( "d-m-Y", strtotime($u_aa[ 'Data']));?></p>
                            </div>
<?php       } if(!$ricorsioni){ ?>
                        <div>Nulla da Mostrare</div>
<?php       } ?>
                    </div>
<?php       break;
            case 'C': ?>
                        <h1>I Tuoi Articoli</h1>
<?php       foreach(Database::getInstance()->getUserArticle($usern, 100) as $u_a){ 
                $ricorsioni=true; ?>
                        <div class="interaction type">
                            <div class="ico edit"></div>
                            <p> Articolo <?php if($u_a['Pubblico']) echo "Pubblicato"; else echo "Privato";?>: "<a href="viewer.php?id=<?php echo $u_a['Id'];?>" title="Articolo <?php echo $u_a['Titolo'];?>"><?php echo truncate($u_a[ 'Titolo'],120);?></a>"</p>
                        </div>
                        <div class="interaction time">
                            <p><?php echo date( "d-m-Y", strtotime($u_a[ 'DataCreazione']));?></p>
                        </div>
<?php       }?>
                    </div>
<?php       if(!$ricorsioni){ ?>
                        <div>Nulla da Mostrare</div>
<?php       } 
            break;
} ?>
                </div>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>