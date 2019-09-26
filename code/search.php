<?php 
    $pagename="Ricerca";
    include( "include/connect.inc.php");
    $cat="";
    $search="" ;
    if(isset($_GET[ "cat"])) $cat.=$_GET[ "cat"];
    if(isset($_GET[ "search"])||isset($_GET[ "search.x"])) $search.=$_GET[ "search"];
    if($cat!=""||$search!="") $found = Database::getInstance()->search($search, $cat);
    $best = Database::getInstance()->getBestArt();
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Ricerca | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Ricerca" />
        <meta name="author" content="D, F, M" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width: 768px)" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="script/script.js"></script>
    </head>
    <body>
<?php include("include/header.php"); ?>
        <div class="container">
<?php include("include/menu.php"); ?>
            <div class="corpo foglio">
                <form class="searchform bigsearch" action="<?php echo htmlspecialchars(" search.php ");?>" method="GET">
                    <div class="fieldset">
                        <input type="text" title="Inserisci Termini Ricerca" name="search" placeholder="Cerca" />
                        <button type="submit" title="Conferma Ricerca"><img src="img/icon/search.svg" alt="Conferma Ricerca"/></button>
                    </div>
                </form>
<?php if(isset($found) && count($found)>0){ ?> <h1 tabindex="0">Risultati pi&ugrave; Pertinenti</h1><?php }?>
                <div class="boxes">
                    <?php if(isset($found)) foreach($found as $basicinfo){?>
                    <div class="result">
                        <div class="imgbox">
                            <a href="viewer.php?id=<?php echo $basicinfo['ArtId'];?>" title="<?php echo $basicinfo['Titolo'];?>">
                                <img src="img/<?php if(isset($basicinfo['LinkFig'])) echo "article_image/".$basicinfo['LinkFig']; else echo "article_image/noimage.png";?>" alt="<?php echo $basicinfo['AltFig'];?>"/></a>
                        </div>
                        <div class="textbox">
                            <a href="viewer.php?id=<?php echo $basicinfo['ArtId'];?>" title="<?php echo $basicinfo['Titolo'];?>"><h2><?php echo truncate($basicinfo['Titolo'],70);?></h2></a>
                            <p tabindex="0">
                                <?php echo truncate($basicinfo[ 'Sottotitolo'],40);?>
                            </p>
                            <p tabindex="0">Articolo di <?php echo $basicinfo[ 'Autore'];?>, <?php echo $basicinfo[ 'COUNT(elemento.Id)'];?> Paragrafi.</p>
                            <a href="search.php?cat=<?php echo str_replace(" ","%20",$basicinfo['Categoria']);?>" title="Categoria <?php echo $basicinfo['Categoria'];?>">
                                <p>Categoria <?php echo $basicinfo[ 'Categoria'];?></p>
                            </a>
                        </div>
                    </div>
<?php } ?>
                </div>
<?php if(!isset($found) || count($found)==0){ ?><p>Nessun risultato per questa chiave di ricerca</p><?php }?>
                <hr/>
                <h1 tabindex="0">Articoli Consigliati</h1>
                <div class="boxes">
<?php foreach($best as $basicinfo){ ?>
                    <div class="result">
                        <div class="imgbox">
                            <a href="viewer.php?id=<?php echo $basicinfo['Id'];?>" title="<?php echo $basicinfo['Titolo'];?>">
                                <img src="img/<?php if(isset($basicinfo['LinkFig'])) echo "article_image/".$basicinfo['LinkFig'];else  echo "article_image/noimage.png";?>" alt="<?php if(isset($basicinfo['AltFig']) && $basicinfo['AltFig']!="") echo $basicinfo['AltFig'];else echo "article_image/noimage.png";?>"/>
                            </a>
                        </div>
                        <div class="textbox">
                            <a href="viewer.php?id=<?php echo $basicinfo['Id'];?>" title="<?php echo $basicinfo['Titolo'];?>"><h2><?php echo truncate($basicinfo['Titolo'],70);?></h2></a>
                            <p tabindex="0"><?php echo truncate($basicinfo[ 'Sottotitolo'],40);?></p>
                            <p tabindex="0">Articolo di <?php echo $basicinfo[ 'Autore'];?></p>
                            <a href="search.php?cat=<?php echo str_replace(" ","%20",$basicinfo['Categoria']);?>" title="Categoria <?php echo $basicinfo['Categoria'];?>">
                                <p>Categoria <?php echo $basicinfo[ 'Categoria'];?></p>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>