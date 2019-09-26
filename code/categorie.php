<?php 
    $pagename="Categorie" ; 
    include_once( "include/connect.inc.php");
    $cats = Database::getInstance()->getCategory();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Categorie | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Categorie">
        <meta name="author" content="D, F, M">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width: 768px)" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="script/script.js"></script>
    </head>
    <body>
<?php include("include/header.php") ?>
<?php include("include/svgdefinitions.php") ?>
        <div class="container">
<?php include("include/menu.php") ?>
            <div class="corpo">
                <div class="categorie">
                    <?php foreach($cats as $cat){ ?>
                    <a href="search.php?cat=<?php echo str_replace(" ","%20",$cat['Nome']);?>" title="Categoria <?php echo $cat['Nome'];?>" class="categoria">
                        <svg aria-hidden="true">
                            <use xlink:href="#icon-<?php echo $cat['TagImg'];?>"></use>
                        </svg>
                        <h2 tabindex="0"><?php echo $cat['Nome'] ;?></h2>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>
