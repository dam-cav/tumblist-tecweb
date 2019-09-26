<?php  
    $pagename="Profilo" ;
    include_once( "include/connect.inc.php"); 
    $ricorsioni=false;
    if(isset($usern)) $user=Database::getInstance()->getUserInfo($usern);
    else{
        header("Location: reglogin.php");
        exit();
    }
    $numArt=Database::getInstance()->numUserArticle($usern);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Profilo | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Profilo" />
        <meta name="author" content="D, F, M" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width: 768px)" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="script/script.js"></script>
    </head>
    <body>
<?php  include("include/header.php") ?>
        <div class="container">
<?php  include( "include/menu.php") ?>
            <div class="corpo">
<?php if(isset($usern)){?>
                <div class="profilo">
                    <div class="avatar"><img src="img/<?php if(isset($user['LinkImg']))echo "user_avatar/".$user['LinkImg'];else echo"avatar.png ";?>" alt="Avatar di <?php echo $user['Username'];?>" />
                    </div>
                    <div class="username">
                        <h1 tabindex="0">Profilo di <?php echo $user['Username'];?></h1>
                        <p tabindex="0"><?php echo $user[ 'Biografia'];?></p>
                        <hr/>
                        <ul>
                            <li tabindex="0"><img src="img/icon/emailb.svg" alt="Email" aria-hidden="true" /> <?php echo $user[ 'Mail'];?></li>
                            <li tabindex="0"><img src="img/icon/role.svg" alt="Permessi" aria-hidden="true" /> <?php echo userrole($role);?></li>
                            <li tabindex="0"><img src="img/icon/contract.svg" alt="Articoli Creati" aria-hidden="true" /> <?php echo $numArt ?> Articoli</li>
                        </ul>                         
                    </div>
                    <div class="options">
                        <a href="profile_settings.php" title="Modifica Profilo">
                            <img src="img/icon/settings.svg" alt="Icona Modifica Profilo" />
                            <p>Modifica Profilo</p>
                        </a>
                    </div>
                </div>

                <div class="boxes">
                    <div class="interabox">
                        <h1 tabindex="0">Attivit&agrave; Recenti</h1>
<?php   foreach(Database::getInstance()->getUserActivity($usern, 5) as $u_act){ $ricorsioni=true; ?>
                        <div class="interaction type">
                            <div class="ico <?php echo intericon($u_act['Tipo']);?>"></div><p tabindex="0">Hai <?php echo internotify($u_act[ 'Tipo']);?> l'articolo "<a href="viewer.php?id=<?php echo $u_act['IdL'];?>" title="Articolo <?php echo $u_act['Titolo'];?>"><?php echo truncate($u_act[ 'Titolo'],80);?></a>"</p>
                        </div>
                        <div class="interaction time">
                            <p tabindex="0"><?php echo date( "d-m-Y", strtotime($u_act[ 'Data']));?></p>
                        </div>
<?php } if(!$ricorsioni){ ?>
                        <div class="interaction noaction">
                            <img src="img/icon/sad-face.svg" alt="Faccina Triste"/>
                            <p tabindex="0">Non hai ancora fatto nulla</p>
                        </div>
<?php }else $ricorsioni=false; ?>
                        <a href="activity.php?act=F" title="Tutte le tue attività" class="allinteractions">Vedi tutte le interazioni</a>
                    </div>

                    <div class="interabox">
                        <h1 tabindex="0">Interazioni sui Tuoi Articoli</h1>
<?php foreach(Database::getInstance()->getUserArticleAct($usern, 5) as $u_aa){ $ricorsioni=true; ?>
                        <div class="interaction type">
                            <div class="ico <?php echo intericon($u_aa['Tipo']);?>"></div>
                            <p tabindex="0"><?php echo $u_aa[ 'NickIns'];?> ha <?php echo internotify($u_aa[ 'Tipo']);?> la tua lista "<a href="viewer.php?id=<?php echo $u_aa['IdL'];?>" title="Articolo <?php echo $u_act['Titolo'];?>"><?php echo truncate($u_aa[ 'Titolo'],80);?></a>"</p>    
                        </div>
                        <div class="interaction time">
                            <p tabindex="0"><?php echo date( "d-m-Y", strtotime($u_aa[ 'Data']));?></p>
                        </div>
<?php } if(!$ricorsioni){ ?>
                        <div class="interaction noaction">
                            <img src="img/icon/sad-face.svg" alt="Faccina Triste"/>
                            <p tabindex="0">Nessuno si &egrave; ancora fatto vivo</p>
                        </div>
<?php }else $ricorsioni=false; ?>
                        <a href="activity.php?act=R" title="Tutte le tue attività" class="allinteractions" >Vedi tutte le interazioni</a>
                    </div>

                    <div class="interabox bigger">
                        <h1 tabindex="0">I Tuoi Articoli</h1><br/>
<?php foreach(Database::getInstance()->getUserArticle($usern, 5) as $u_a){ 
            $ricorsioni=true; ?>
                        <div class="interaction type">
                            <div class="ico edit"></div>
                            <p tabindex="0"> Articolo <?php if($u_a['Pubblico']) echo "Pubblicato"; else echo "Privato";?>: "<a href="viewer.php?id=<?php echo $u_a['Id'];?>" title="Articolo <?php echo $u_a['Titolo'];?>"><?php echo truncate($u_a[ 'Titolo'],120);?></a>"</p>
                        </div>
                        <div class="interaction time">
                                <p tabindex="0"><?php echo date( "d-m-Y", strtotime($u_a[ 'DataCreazione']));?></p>
                        </div>
<?php } if(!$ricorsioni){ ?>
                        <div class="interaction noaction">
                            <img src="img/icon/sad-face.svg" alt="Faccina Triste" />
                            <p tabindex="0">Non hai ancora scritto articoli, perchè non farlo ora?</p>
                        </div>
<?php } ?>
                        <a href="activity.php?act=C" title="Tutte le tue attività" class="allinteractions" >Vedi tutti gli articoli</a>
                    </div>
                </div>
<?php }
else echo "Non puoi"; ?>
            </div>
        </div>
<?php  include( "include/footer.php"); ?>
    </body>
</html>