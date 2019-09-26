<?php 
    $pagename="Home";
    include_once( "include/connect.inc.php"); 
    $exploredart=Database::getInstance()->getRecentArt();
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Home | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Home TumbList" />
        <meta name="author" content="D, F, M" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta property="og:url" content="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']); ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Crea facilmente decaloghi social-ready." />
        <meta property="og:description" content="Su TumbList puoi preparare i tuoi articoli per i social senza bisogno di particolari conoscenze tecniche." />
        <meta property="og:image" content="<?php echo $_SERVER['HTTP_HOST'];?>/img/169.png" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width: 768px)" />
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="script/script.js"></script>
    </head>
    <body>
<?php include("include/header.php") ?>
        <div id="container" class="container">
<?php include( "include/menu.php") ?>
            <div class="corpo flex foglio">
                <div class="home">
                    <h1 tabindex="0"><span lang="en">Crea facilmente decaloghi social-ready.</span></h1>
                    <h2 tabindex="0">Su TumbList puoi preparare i tuoi articoli all'inclusione nei <span lang="en">social</span> senza bisogno di particolari conoscenze tecniche.</h2>
                    <div class="facebook">
                        <div class="preview">
                            <img src="img/169.png" alt="Immagine preview di articolo come visto su Facebook" />
                        </div>
                        <div class="text">
                            <p class="title" tabindex="0">Ecco come i tuoi articoli appaiono su Facebook.</p>
                            <p class="detail" tabindex="0">Questo &egrave; un esempio di integrazione facebook, non porta ad alcuna pagina.</p>
                            <p class="url" tabindex="0">TUMBLIST</p>
                        </div>
                    </div>
                    <h2 tabindex="0">Tu preoccupati solo di scrivere, tumblist si preoccuperà automaticamente di preparare l'articolo per aderire agli standard delle <span lang="en">preview</span>.</h2>
                    <br/>
                    <p tabindex="0">Come funziona:</p>
                    <ol>
                        <li>
                            <h1 tabindex="0">Unisciti anche tu</h1>
                            <p tabindex="0">Per registrarti o effettuare l'accesso premi "<a href="reglogin.php" title="Entra">Entra</a>" sulla barra superiore.</p>
                        </li>
                        <li>
                            <h1 tabindex="0">Cerca qualunque cosa</h1>        
                            <h2 tabindex="0">Se non sai davvero cosa vuoi, scegli <a href="esplora.php" title="Esplora">Esplora</a></h2>
                            <p tabindex="0">Da qui puoi vedere gli articoli più apprezzati dagli utenti, se sei curioso questa sezione &egrave; quella adatta a te.</p>
                            <h2 tabindex="0">Se hai un'idea approssimativa, scegli <a href="categorie.php" title="Categorie">Categorie</a></h2>
                            <p tabindex="0">Qui puoi filtrare per argomento i post da visualizzare.</p>
                            <h2 tabindex="0">Se sei un tipo deciso, <a href="search.php" title="Cerca">Cerca</a> tu stesso</h2>
                            <p tabindex="0">Sulla barra superiore è presente il bottone di ricerca, da qui puoi inserire tu stesso la chiave ddi ricerca che preferisci.</p>                            
                        </li>
                        <li>
                            <h1 tabindex="0">Crea nuovi contenuti</h1>
                            <p tabindex="0">Una volta effettuato l'accesso, scegli "Editor" dal menu per cominciare subito</p>
                        </li>
                    </ol>              
                </div>
                <div class="lastpost">
                    <h1 tabindex="0">Articoli più Recenti</h1>
                    <div class="boxes">
<?php for($i=0; $i<3;$i++){ if(isset($exploredart[$i])){?>
                        <div class="inhome">
                            <div class="imgbox">
                                <a href="viewer.php?id=<?php echo $exploredart[$i]['Id'];?>" title="<?php echo $exploredart[$i]['Titolo'];?>">
                                    <img src="img/<?php if(isset($exploredart[$i]['LinkFig'])) echo "article_image/".$exploredart[$i]['LinkFig'];else  echo "article_image/noimage.png";?>" alt="<?php if(isset($exploredart[$i]['AltFig']) && $exploredart[$i]['AltFig']!="") echo $exploredart[$i]['AltFig'];else echo "article_image/noimage.png";?>"/>
                                </a>
                            </div>
                            <div class="textbox">
                                <a href="viewer.php?id=<?php echo $exploredart[$i]['Id'];?>" title="<?php echo $exploredart[$i]['Titolo'];?>">
                                    <h2><?php echo truncate($exploredart[$i]['Titolo'],70);?></h2></a>
                                <p tabindex="0"><?php echo truncate($exploredart[$i]['Sottotitolo'],60);?></p>
                                <p tabindex="0">Articolo di <?php echo $exploredart[$i]['Autore'];?>, <?php echo date( "d-m-Y", strtotime($exploredart[$i]['DataCreazione']));?> </p>
                                <a href="search.php?cat=<?php echo str_replace(" ","%20",$exploredart[$i]['Categoria']);?>" title="Categoria<?php echo $exploredart[$i]['Categoria'];?>">
                                    <p>Categoria <?php echo $exploredart[$i][ 'Categoria'];?></p>
                                </a>
                            </div>
                            </div>
<?php }} ?>
                    </div>
                </div>
            </div>
        </div>
<?php include( "include/footer.php") ?>
    </body>
</html>
