<?php  if(isset($pagename)){ ?>
        <header>
            <div class="limiter">
                <a href="index.php" class="logo">
                    <img src="img/Logo.svg" alt="Logo TumbList"/><p class="sitename"><span lang="en">TumbList</span></p>
                </a>
                <div class="map">
                    <h1><a href="#top"><?php echo $pagename ?></a></h1>
                </div>
                <div class="searchlogin">
                    <form class="searchform" action="<?php echo htmlspecialchars("search.php");?>" method="GET">
                        <div class="fieldset">
                            <input type="text" title="Inserisci Termini Ricerca" name="search" placeholder="Cerca"/>
                            <button type="submit" title="Conferma Ricerca"><img src="img/icon/search.svg" alt="Conferma Ricerca"/></button>
                        </div>
                    </form>            
<?php  if(isset($_SESSION["logName"])){ ?>
                    <form method="POST" class="loginform">
                        <fieldset>
                            <input type="submit" title="Logout" name="logout" value="Esci" />
                        </fieldset>
                    </form>
<?php  }else{ ?>
                    <a href="reglogin.php" title="Effettua l'Accesso"  class="loginform" >Entra</a>
<?php  } ?>                  
                </div>                
            </div>
        </header>
<?php  } ?>
