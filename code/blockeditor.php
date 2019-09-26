<?php
    $pagename="Editor Blocco";
    include_once( "include/connect.inc.php");
    if(!isset($usern)){
        header("Location: reglogin.php");
        exit();
    }
    if(isset($_GET[ 'id']) && isset($_GET[ 'bid'])){ 
        $id=$_GET[ 'id'];
        $bid=$_GET[ 'bid'];

        if(isset($_POST['save'])) {
            if(!isset($_FILES["img"])) $_FILES["img"]=null;

            if($_POST['nome']==strip_tags($_POST['nome']) && $_POST['etichetta']==strip_tags($_POST['etichetta']) && $_POST['altimg']==strip_tags($_POST['altimg'])){
                if(trim($_POST['nome']!="")){
                    if(Database::getInstance()->editElem($id, $bid, trim($_POST['nome']),$_FILES["img"], trim($_POST['altimg']), trim($_POST['etichetta']))){
                        header("Location: editor.php?id=".$id);
                        exit();
                    }
                    else $errore="Paragrafo non modificato.";
                }
                else $errore="Il campo Nome non può essere vuoto.";
            }
            else $errore="Nome, Etichetta e Descrizione Immagine non devono contenere tag html.";
        }

        else if(isset($_POST['create'])) {
            if(!isset($_FILES["img"])) $_FILES["img"]=null;
            
            if($_POST['nome']==strip_tags($_POST['nome']) && $_POST['etichetta']==strip_tags($_POST['etichetta']) && $_POST['altimg']==strip_tags($_POST['altimg'])){
                if(trim($_POST['nome']!="")){
                    if(Database::getInstance()->createElem($id, trim($_POST['nome']), $_FILES["img"], trim($_POST['altimg']), trim($_POST['etichetta']))){
                        header("Location: editor.php?id=".$id);
                        exit();
                    }
                    else $errore="Paragrafo non creato.";
                }
                else $errore="Il campo Nome non può essere vuoto.";
            }
            else $errore="Nome, Etichetta e Descrizione Immagine non devono contenere tag html.";
        }
    }
    if(isset($_GET[ 'id']) && $bid!="new") $basicinfo=Database::getInstance()->getSpecificBlock($id,$bid);
    else if(isset($_GET[ 'id'])) $basicinfo="new";
    else{ 
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Editor Paragrafo | Tumblist</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="description" content="Editor Paragrafo">
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
            <div class="corpo flex foglio">
<?php  if(isset($basicinfo)){?>
                <div class="articlelist">
                    <h1 tabindex="0"><?php if($basicinfo=="new") echo "Nuovo Paragrafo"; else echo "Modifica Paragrafo"?></h1>
                    <form name="blockname" action="<?php  echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id."&bid=".$bid); ?>" method="POST" class="editor" enctype="multipart/form-data" onSubmit="return blockEditorValidation();">                        
                        <div class="fieldset">
                            <label for="nome">NOME</label>
                            <input type="text" id="nome" name="nome" maxlength="140" value="<?php if($basicinfo!="new") echo $basicinfo['Nome']; ?>" />
                            <div class="editorimage">
                                <label for="img">IMMAGINE</label>
                                <input type="file" id="img" name="img" />
                                <div><img src="img/article_image/<?php if($basicinfo!="new") echo $basicinfo['LinkImg'];?>" alt="Immagine Attuale" /></div>
                            </div>
                            <label for="altimg">DESCRIZIONE IMMAGINE</label>
                            <input type="text" id="altimg" name="altimg"  title="descrizione immagine" maxlength="40" value="<?php if($basicinfo!="new") echo $basicinfo[ 'AltImg'];?>">
                            <label for="etichetta">ETICHETTA</label>
                            <textarea id="etichetta" name="etichetta" rows="4" maxlength="250"><?php if($basicinfo!="new") echo $basicinfo[ 'Etichetta'];?></textarea>
                            <input type="submit" name="<?php if($basicinfo!="new")echo "save"; else echo "create";?>" title="Salva Modifiche alla Lista" value="Salva Modifiche" />
                        </div>
                    </form>
                    <hr/>
                </div>
                <div class="infopost">
                    <h1 tabindex="0">Opzioni Paragrafo</h1>
                    <a href="editor.php<?php echo"?id=".$id ?>" title="Scarta Modifiche">Scarta Modifiche</a>
                </div>
<?php  } else echo "Ops! Questo articolo non esiste!"; ?>
            </div>
        </div>
<?php include( "include/footer.php"); ?>
    </body>
</html>
