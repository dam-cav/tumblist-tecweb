<?php 
    $pagename="Editor Articolo";
    include_once( "include/connect.inc.php");
    if(!isset($usern)){
        header("Location: reglogin.php");
        exit();
    }
    if(isset($_GET[ 'id'])){ 
        $id=$_GET[ 'id'];
        if(isset($_POST['delete'])){
            if(Database::getInstance()->deleteArt($id)){
                header("Location: editor.php");
                exit();
            }
            else $errore="Articolo non eliminato.";
        }
        else if(isset($_POST['save'])){
            if(!isset($_FILES["fig"])) $_FILES["fig"]=null;

            if($_POST['titolo']==strip_tags($_POST['titolo']) && $_POST['sottotitolo']==strip_tags($_POST['sottotitolo']) && $_POST['altfig']==strip_tags($_POST['altfig']) && $_POST['descrizione']==strip_tags($_POST['descrizione'])){
                if(trim($_POST['titolo'])!=""){
                    if(Database::getInstance()->editList($id, trim($_POST['titolo']), trim($_POST['sottotitolo']),$_FILES["fig"], trim($_POST['altfig']), trim($_POST['descrizione']),trim($_POST['categoria']))){
                        $conferma="Articolo modificato correttamente.";
                    }
                    else $errore="Articolo non modificato correttamente.";
                }
                else $errore="Il campo Titolo non può essere vuoto.";
            }  
            else $errore="Titolo, Sottotitolo, Descrizione Immagine e Descrizione non devono contenere tag html.";   
        }
        else if(isset($_POST['create'])){
            if(!isset($_FILES["fig"])) $_FILES["fig"]=null;

            if($_POST['titolo']==strip_tags($_POST['titolo']) && $_POST['sottotitolo']==strip_tags($_POST['sottotitolo']) && $_POST['altfig']==strip_tags($_POST['altfig']) && $_POST['descrizione']==strip_tags($_POST['descrizione'])){
                if(trim($_POST['titolo'])!=""){
                    if(Database::getInstance()->createList($usern, $_POST['titolo'], $_POST['sottotitolo'],$_FILES["fig"], $_POST['altfig'], $_POST['descrizione'],$_POST['categoria'])){
                        header("Location: editor.php");
                        exit();
                    }
                    else $errore="Articolo non creato.";
                }
                else $errore="Il campo Titolo non può essere vuoto.";
            }
            else $errore="Titolo, Sottotitolo, e Descrizione Immagine e Descrizione non devono contenere tag html.";
        }
        else if(isset($_POST['uppa'])){
            if(Database::getInstance()->moveElem($id,$_POST['ordelem'],"u")) $conferma="Paragrafo spostato correttamente.";
            else $errore="Paragrafo non spostato.";
        }
        else if(isset($_POST['downa'])){
            if(Database::getInstance()->moveElem($id,$_POST['ordelem'],"d")) $conferma="Paragrafo spostato correttamente.";
            else $errore="Paragrafo non spostato.";
        }
        else if(isset($_POST['delelem'])){
            if(Database::getInstance()->deleteElemOrd($id,$_POST['ordelem'])) $conferma="Paragrafo eliminato correttamente.";
            else $errore="Paragrafo non spostato.";
        } 
        else if(isset($_POST['public'])){
            if(Database::getInstance()->publicList($id,$usern)) $conferma="Articolo pubblicato correttamente.";
            else $errore = "Articolo non pubblicato.";
        }
        if($id!="new"){
            $basicinfo=Database::getInstance()->getArticleinfo($id);
            if(!($usern == $basicinfo["Autore"] || $role== 'A')){
                header("Location: index.php");
                exit();
            }
            $blocks=Database::getInstance()->getArticleBlocks($id);
        }
        else if ($role!="B") $basicinfo="new";
        else{
            header("Location: profilo.php");
            exit();
        }
    }
    $cats= Database::getInstance()->getCategory();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Editor | Tumblist</title>
        <meta charset="UTF-8" />
        <meta name="description" content="Editor Articoli">
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
            <div class="corpo flex <?php if(isset($id)) echo "foglio "?>reversible">
                <div class="articlelist <?php if(!isset($id)){?>order<?php }?>">                
<?php if(!isset($id)){?>                <!-- CASO LISTA ARTICOLI-->
                    <h1 tabindex="0">Lista Articoli</h1>
<?php   foreach(Database::getInstance()->getUserArticle($usern, 100) as $u_a){?>
                    <div class="interabox bigger">
                        <div class="interaction type">
                            <div class="ico edit"></div>
                            <p tabindex="0"> Articolo <?php if($u_a['Pubblico']) echo "Pubblicato"; else echo "Privato";?>: "<a href="viewer.php?id=<?php echo $u_a['Id'];?>" title="Articolo <?php echo $u_a['Titolo'];?>"><?php echo truncate($u_a[ 'Titolo'],120);?></a>"</p>
                        </div>
                        <div class="interaction editoptions">
                            <a href="viewer.php?id=<?php  echo $u_a["Id"] ?>" title="Vai all'articolo"><img src="img/icon/go.svg" alt="Vai"></a>
                            <a href="editor.php?id=<?php  echo $u_a["Id"] ?>" title="Modifica Articolo"> <img src="img/icon/modify.svg" alt="Modifica"></a>
                        </div>
                    </div>
<?php   }
    } else {?>
                <!-- CASO NUOVO ARTICOLO SCELTO-->
                    <h1 tabindex="0"><?php if($basicinfo=="new") echo "Nuovo Articolo"; else echo "Modifica Articolo"?></h1>
                    <form name="intestazione" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id); ?>" method="POST" class="editor" enctype="multipart/form-data" onSubmit="return EditorValidation();">
                        <div class="fieldset">
                            <h2><?php if($basicinfo=="new") echo "Nuova Intestazione"; else echo "Modifica Intestazione"?></h2>
                            <label for="titolo">TITOLO</label>
                            <input type="text" id="titolo" name="titolo" title="titolo" maxlength="140" value="<?php if($basicinfo!="new")echo $basicinfo['Titolo'];?>" />
                            <label for="sottotitolo">SOTTOTITOLO</label>
                            <textarea id="sottotitolo" name="sottotitolo" title="sottotitolo" rows="4" maxlength="300"><?php if($basicinfo!="new") echo $basicinfo[ 'Sottotitolo'];?></textarea>
                            <label for="categoria">CATEGORIA</label>
                            <select id="categoria" name="categoria">
<?php       foreach($cats as $cat){ ?>
                                <option value="<?php echo $cat['Nome'];?>" <?php if($id!="new" && $cat['Nome']==$basicinfo['Categoria']) echo"selected=\"selected\"";?>><?php echo $cat['Nome'];?></option>
<?php       }?>
                            </select>
                            <div class="editorimage">
                                <label for="fig">IMMAGINE</label>
                                <input type="file" name="fig" id="fig" />
                                <?php if($basicinfo!="new"){?><div><img src="img/article_image/<?php echo $basicinfo['LinkFig'];?>" alt="Immagine Attuale" />
                                </div><?php }?>                            
                            </div>
                            <label for="altfig">DESCRIZIONE IMMAGINE</label>
                            <input type="text" id="altfig" name="altfig"  title="descrizione immagine" maxlength="40" value="<?php if($basicinfo!="new") echo $basicinfo[ 'AltFig'];?>">
                            <label for="descrizione">DESCRIZIONE</label>
                            <textarea id="descrizione" name="descrizione" title="descrizione" rows="4" maxlength="400"><?php if($basicinfo!="new")echo $basicinfo[ 'Descrizione'];?></textarea>
                            <input type="submit" name="<?php if($basicinfo!="new")echo "save"; else echo "create";?>" title="Salva Modifiche all'Articolo" value="Salva Modifiche" />
                        </div>
                    </form>
                    <hr/>
<?php }?>
                </div>
                <div class="infopost <?php if(!isset($id)){?>order<?php }?>">
                    <h1 tabindex="0">Opzioni Articolo</h1>
<?php if(!isset($id) && $role!="B"){?>                
                    <a href="editor.php?id=new" title="Crea Nuovo Articolo">Crea Nuovo Articolo</a>
<?php }else{?>
                    <form action="<?php  echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id); ?>" method="POST" enctype="multipart/form-data">
                        <fieldset>
<?php  if(isset($id)) if($id!="new"){  ?>         
                            <a href="blockeditor.php?id=<?php echo $id;?>&bid=new" title="Aggiungi Paragrafo all'articolo">Aggiungi Paragrafo</a>
                            <a href="<?php echo htmlspecialchars("viewer.php?id=".$id);?>" title="Torna all'articolo">Torna all'articolo</a>
                            <a href="editor.php" title="Scarta Modifiche">Scarta Modifiche</a>
                            <input type="submit" name="delete" title="Elimina Articolo" value="Elimina Articolo" />
<?php   if($basicinfo["Pubblico"]==false){?>
                            <input type="submit" name="public" title="Pubblica Articolo" value="Pubblica Articolo" />
<?php   }
    }else{ ?>
                            <a href="editor.php" title="Annulla Creazione">Annulla</a>
<?php }?>
                        </fieldset>
                    </form>
<?php   }?>
                </div>
<?php if(isset($blocks)){
        if(sizeof($blocks)>0){?>
                <!-- SOLO SE CI SONO PARAGRAFI-->
                <div class="articlelist">
                    <h1 tabindex="0">Paragrafi</h1>
<?php   foreach($blocks as $block){ ?>
                    <form class="blockmanager" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']."?id=".$id); ?>" method="POST" enctype="multipart/form-data">
                        <div class="image"><img src="img/article_image/<?php echo $block['LinkImg'];?>" alt="<?php echo $block['AltImg'];?>" /></div>
                        <div class="text">
                            <p tabindex="0"><?php echo $block['Nome'];?></p>  
                            <p tabindex="0"><?php echo $block['Etichetta'];?></p>
                        </div>
                        <div class="fieldset">                        
                            <a href="blockeditor.php?id=<?php echo $block['IdL']?>&bid=<?php echo $block['Id']?>" title="Modifica Paragrafo"><img src="img/icon/modify.svg" alt="Modifica paragrafo"/></a>
                            <input type="hidden" name="ordelem" value="<?php  echo $block['Ordine']; ?>" />
                            <button type="submit" name="uppa" title="Sposta Su Paragrafo"><img src="img/icon/up.svg" alt="Sposta Su Paragrafo"/></button>
                            <button type="submit" name="downa" title="Sposta Gi&ugrave; Paragrafo"><img src="img/icon/down.svg" alt="Sposta Gi&ugrave; Paragrafo"/></button>
                            <button type="submit" name="delelem" title="Cancella Paragrafo"><img src="img/icon/delete.svg" alt="Cancella Paragrafo"/></button>                          
                        </div>
                    </form>
<?php   }?>
                </div>
<?php   }}?>
            </div>
        </div>
<?php  include( "include/footer.php"); ?>
    </body>

</html>
