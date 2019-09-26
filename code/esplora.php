<?php 
    $ricorsioni=false; $pagename="Esplora" ; 
    include( "include/connect.inc.php"); 
    $exploredart=Database::getInstance()->getBestArt();
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Esplora | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Esplora" />
        <meta name="author" content="D, F, M" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width: 768px)" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="script/script.js"></script>
    </head>
    <body>
<?php include("include/header.php") ?>
        <div class="container">
<?php include( "include/menu.php") ?>
            <div class="corpo foglio">
                <h1 tabindex="0">Articoli Consigliati</h1>
                <div class="boxes">                
<?php foreach($exploredart as $basicinfo){ ?>
                    <div class="result">
                        <div class="imgbox">
                            <a href="viewer.php?id=<?php echo $basicinfo['Id'];?>" title="<?php echo $basicinfo['Titolo'];?>">
                                <img src="img/<?php if(isset($basicinfo['LinkFig'])) echo "article_image/".$basicinfo['LinkFig'];else  echo "article_image/noimage.png";?>" alt="<?php if(isset($basicinfo['AltFig']) && $basicinfo['AltFig']!="") echo $basicinfo['AltFig'];else echo "article_image/noimage.png";?>"/>
                            </a>
                        </div>
                        <div class="textbox">
                            <a href="viewer.php?id=<?php echo $basicinfo['Id'];?>" title="<?php echo $basicinfo['Titolo'];?>">
                                <h2><?php echo truncate($basicinfo['Titolo'],70);?></h2></a>
                            <p tabindex="0"><?php echo truncate($basicinfo['Sottotitolo'],40);?></p>
                            <p tabindex="0">Articolo di <?php echo $basicinfo['Autore'];?></p>
                            <a href="search.php?cat=<?php echo str_replace(" ","%20",$basicinfo['Categoria']);?>" title="Categoria<?php echo $basicinfo['Categoria'];?>">
                                <p>Categoria <?php echo $basicinfo[ 'Categoria'];?></p>
                            </a>
                        </div>
                    </div>
<?php  } ?>
                </div>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>
