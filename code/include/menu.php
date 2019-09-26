            <label class="openmenu" for="openmenu"><img src="img/icon/menu.svg" alt="Apri menu"/> Menu</label>
            <input type="checkbox" id="openmenu" value="openmenu"/>
            <nav>   
                <ul>
<?php if($pagename!="Home"){?>
                    <li>
                        <a title="Home" href="index.php" lang="en"><span lang="en">Home</span></a>
                    </li>
<?php } else {?>
                    <li class="currentPage">
                        <span lang="en" tabindex="0">Home</span>
                    </li>
<?php   }
if($pagename!="Esplora"){?>
                    <li>
                        <a title="Esplora" href="esplora.php">Esplora</a>
                    </li>
<?php } else {?>
                    <li class="currentPage"><span tabindex="0">Esplora</span></li>
<?php   }
                    if($pagename!="Categorie"){?>
                    <li>
                        <a title="Categorie" href="categorie.php">Categorie</a>
                    </li>
<?php } else {?>
                    <li class="currentPage"><span tabindex="0">Categorie</span></li>
<?php   }
        if(isset($usern)){
            if($pagename!="Profilo"){
?>
                    <li>
                        <a title="Profilo" href="profilo.php">Profilo</a>
                    </li>
<?php       } else {?>
                    <li class="currentPage"><span tabindex="0">Profilo</span></li>  
<?php       }
            if($pagename!="Editor Articolo"){?>
                    <li><a title="Editor" href="editor.php" lang="en"><span lang="en">Editor</span></a></li>
<?php       } else {?>
                    <li class="currentPage"><span lang="en" tabindex="0">Editor</span></li>
<?php       }
        }
        if(isset($usern) && $role=="A"){
            if($pagename!="Admin"){?>
                    <li>
                        <a title="Admin" href="admin.php" lang="en"><span lang="en">Admin</span></a>
                    </li>
<?php       } else {?>
                    <li class="currentPage"><span lang="en" tabindex="0">Admin</span></li>
<?php       }
        }?>                </ul>        
                <div class="changetextsize">
                	<p tabindex="0">Dimensione testo:</p>
                	<a href="#" title="testo dimensione normale" onclick="normal()" id="textsmall">A</a> <a href="#" title="testo dimensione media" onclick="medium()" id="textmedium">A</a> <a href="#" title="testo dimensione grande" onclick="bigger()" id="textbig">A</a>
                </div>
            </nav>
<?php if(isset($errore)){?><div class="corpo errori"><p tabindex="0">ERRORE <?php echo $errore;?></p></div><?php }?>
<?php if(isset($conferma)){?><div class="corpo conferme"><p tabindex="0">CONFERMA <?php echo $conferma;?></p></div><?php }?>