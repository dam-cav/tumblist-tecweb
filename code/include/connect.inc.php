<?php
    //session_save_path("/home/dcavazza/public_html/php_sessions");

    function intericon($char){
        switch ($char) {
            case 'C':
                return "comment";
                break;
            case 'L':
                return "heart";
                break;
            case 'M':
                return "edit";
                break;
            case 'D':
                return "bheart";
                break;
            case 'S':
                return "report";
                break;
        }
    }

    function internotify($char){
        switch ($char) {
            case 'C':
                return "commentato";
                break;
            case 'L':
                return "apprezzato";
                break;
            case 'M':
                return "modificato";
                break;
            case 'D':
                return "disprezzato";
                break;
            case 'S':
                return "segnalato";
                break;
        }
    }

    function userrole($char){
        switch ($char) {
            case 'U':
                return "Utente";
                break;
            case 'B':
                return "Bannato";
                break;
            case 'A':
                return "Admin";
                break;
        }
    }

    function uploadImage($file ,$targetdir, $username){
        $target_dir = "img/".$targetdir."/";
        //sostituisci nome file con nick utente che usa avatar
        $file["name"] = $username.".".substr($file["name"], strrpos($file["name"], '.') + 1);
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Evita immagini fake
        if(!empty($file["tmp_name"])){
            $check = getimagesize($file["tmp_name"]);
            if(!$check) return false;
        }
        else return false;
        // Controllo dimensione
        if ($file["size"] > 500000) return false;
        // Controllo formati supportati
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") return false;
        if (move_uploaded_file($file["tmp_name"], $target_file)) return $file["name"];
        else return false;
    }

    function truncate($string, $chars) {
        if(strlen($string)>$chars) return substr($string, 0, $chars) . "...";
        else return $string;
    }

    function logout(){
        if(isset($_SESSION[ "logName"])) unset($_SESSION[ "logName"]);
        session_destroy();
        header("Location: index.php");
        exit();
    }


    class Database {
        private $_connection;
        private static $_instance;
        private $_host = "localhost";
        private $_username = "root";
        private $_password = ""; 
        private $_database = "test";
        

        public static function getInstance() {
            if(!self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        private function __construct() {
            @$this->_connection = new mysqli($this->_host, $this->_username, 
                $this->_password, $this->_database);

            if (mysqli_connect_errno()) {
                echo "Connessione Fallita: ".mysqli_connect_error();
                exit();
            }
            
        }

        private function __clone() {}

        public function getConnection() {
            return $this->_connection;
        }

        public function setAvatar($img, $usern){
            $imgname= uploadImage($img ,"user_avatar", $usern);
            if($imgname){
                $mysqli = $this->getConnection();
                $sql_query = "UPDATE utente SET LinkImg=NULL WHERE Username = \"".$usern."\"";
                $mysqli->query($sql_query);
                $sql_query = "UPDATE utente SET LinkImg=\"".$imgname."\" WHERE Username = \"".$usern."\"";
                $mysqli->query($sql_query);
                $result = $mysqli->affected_rows;
                return true;
            }
            return false;
        }

        public function getNumUsers(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT IFNULL(COUNT(*),0) AS NumUtenti FROM utente";
            $result = $mysqli->query($sql_query);
            $user = $result->fetch_array(MYSQLI_ASSOC);
            $result->free();
            return $user['NumUtenti'];    
        }

        public function getNumArt(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT IFNULL(COUNT(*),0) AS NumArticles FROM lista";
            $result = $mysqli->query($sql_query);
            $list = $result->fetch_array(MYSQLI_ASSOC);
            $result->free();
            return $list['NumArticles'];    
        }

        public function getNumComm(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT IFNULL(COUNT(*),0) AS NumComment FROM interazione WHERE Tipo LIKE 'C'";
            $result = $mysqli->query($sql_query);
            $comm = $result->fetch_array(MYSQLI_ASSOC);
            $result->free();
            return $comm['NumComment'];    
        }

        public function getNumElem(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT IFNULL(COUNT(*),0) AS NumElem FROM elemento";
            $result = $mysqli->query($sql_query);
            $elem = $result->fetch_array(MYSQLI_ASSOC);
            $result->free();
            return $elem['NumElem'];    
        }



        public function tryLog($usern, $pass){
            $pass= hash('sha512' ,$pass);
            $sql_query = "SELECT * FROM utente WHERE Username LIKE ? AND Password LIKE ?;";
            $mysqli = $this->getConnection();
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("ss", $usern, $pass);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();
                $info = $result->fetch_array(MYSQLI_ASSOC);
                $result->free();
                if(isset($info)){
                    $_SESSION["logName"] = $info["Username"];
                    $_SESSION["logRole"] = Database::getInstance()->getUserRole($usern);
                    if (isset($_SESSION["logName"])) return $_SESSION["logName"];
                }
            }
            return false;
        }

        public function register($mail, $usern, $pass1, $pass2){
            
            //controllo nome utente password e mail                            
            $usern= strip_tags($usern);
            $pass1= hash('sha512' ,$pass1); //hash password

            $mysqli = $this->getConnection();

            $sql_query = "SELECT * FROM utente WHERE Username LIKE ? OR Mail LIKE ?;";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("ss", $usern, $mail);
                $prep-> execute();//query che cerca nomi e mail già usati 
                $prep-> store_result();
                $result= $prep->num_rows;
                $prep->close();
                if($result==0){ //se mail non già usata
                    $sql_query = "INSERT INTO utente VALUES(?,?,\"Nuovo utente\",NULL,?,NULL,now(),'U');";
                    if($prep = $mysqli->prepare($sql_query)){
                        $prep-> bind_param("sss", $mail, $usern ,$pass1);
                        $prep-> execute();
                        $prep-> store_result();
                        $result= $prep->affected_rows;
                        $prep->close();           
                        if($result>0) return true;
                    }
                }            
            }
            return false;
        }

        public function changePass($usern, $oldpass, $newpass1, $newpass2){
            $oldpass= hash('sha512',$oldpass);
            $newpass1= hash('sha512',$newpass1); 
            $sql_query = "UPDATE utente SET Password=? WHERE Password LIKE ? AND Username LIKE \"".$usern."\"";
            $mysqli = $this->getConnection();
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("ss", $newpass1,$oldpass);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();               
                if($result>0) return true;
            }
            return false;
        }

        public function deleteUser($usern, $pass1, $pass2){
            $pass1= hash('sha512',$pass1);
            $sql_query = "SELECT * FROM utente WHERE Password=? AND Username=\"".$usern."\"";
            $mysqli = $this->getConnection();
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("s", $pass1);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();               
                if($result>0){
                    $sql_query = "DELETE FROM utente WHERE Username=\"".$usern."\";";
                    $mysqli->query($sql_query);
                    $result = $mysqli->affected_rows;
                    if($result>0) return true;
                }
            }
            return false;
        }

        public function changeBio($usern, $newbio){
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE utente SET Biografia=? WHERE Username LIKE \"".$usern."\"";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("s", $newbio);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();            
                if($result>0) return true;
            }
            return false;
        }
        
        public function getCategory(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM categoria ORDER BY Nome";
            $result = $mysqli->query($sql_query);
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $categorie[$row['Nome']] = $row;
            }
            $result->free();
            return $categorie;
        }

        public function getUserInfo($usern){
            $mysqli = $this->getConnection(); 
            $sql_query = "SELECT * FROM utente WHERE Username=\"".$usern."\"";
            $result = $mysqli->query($sql_query);
            $user = $result->fetch_array(MYSQLI_ASSOC);
            $result->free();
            return $user;
        }

        public function getUserActivity($usern, $limit){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM interazione INNER JOIN utente ON NickIns = Username INNER JOIN lista ON IdL=lista.Id WHERE Username=\"".$usern."\" ORDER BY Data DESC LIMIT ".$limit;
            $result = $mysqli->query($sql_query);
            $u_act=array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $u_act[] = $row;
            }
            $result->free();
            return $u_act;
        }

        public function getUserArticleAct($usern, $limit){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM interazione INNER JOIN lista ON Idl=lista.Id WHERE Autore=\"".$usern."\" AND interazione.NickIns <>\"".$usern."\" AND Tipo NOT LIKE \"S\" ORDER BY Data DESC LIMIT ".$limit;
            $result = $mysqli->query($sql_query);
            $u_aa=array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $u_aa[] = $row;
            }
            $result->free();
            return $u_aa;
        }

        public function numUserArticle($usern){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT IFNULL(COUNT(*),0) AS NumArt FROM lista WHERE Autore=\"".$usern."\" GROUP BY Autore";
            $result = $mysqli->query($sql_query);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $n_a = $row["NumArt"];
            $result->free();
            return $n_a;
        }


        public function getUserArticle($usern, $limit){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM lista WHERE Autore=\"".$usern."\" ORDER BY DataCreazione DESC LIMIT ".$limit;
            $result = $mysqli->query($sql_query);
            $u_a=array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $u_a[] = $row;
            }
            $result->free();
            return $u_a;
        }

        public function getArticleInfo($id){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM lista INNER JOIN utente On Autore=Username WHERE Id=?";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();
                $info = $result->fetch_array(MYSQLI_ASSOC);
                $result->free();
            }
            return $info;
        }

        public function getSpecificBlock($id, $bid){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM elemento WHERE IdL=? AND Id=?";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("ii", $id, $bid);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();
                $info = $result->fetch_array(MYSQLI_ASSOC);
                $result->free();
            }
            return $info;
        }

        public function getArticleBlocks($id){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM elemento WHERE idL=? ORDER BY Ordine";
            $blocks= Array();
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();                
                while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $blocks[] = $row;
                }
                $result->free();
            }
            return $blocks;
        }

        public function getArticleComments($id, $order, $limit){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM interazione INNER JOIN utente ON NickIns=UserName WHERE IdL=? AND Tipo='C' ORDER BY Data ".$order." LIMIT ".$limit;
            $comms= Array();
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();   
                while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $comms[] = $row;
                }
                $result->free();
            }
            return $comms;
        }

        public function getReactionNumber($id){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT Tipo, COUNT(Tipo) FROM interazione WHERE IdL=? AND (Tipo LIKE 'L' OR Tipo LIKE 'D') GROUP BY Tipo";
            $reactnum = array(
                    "L" => 0,
                    "D" => 0,
                );
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();
                while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $reactnum[$row["Tipo"]] = $row["COUNT(Tipo)"];
                }
                $result->free();
            }
            return $reactnum;

        }

        public function getLikes($id, $usern){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM interazione WHERE NickIns LIKE \"".$usern."\" AND IdL=? AND (Tipo LIKE 'L' OR Tipo LIKE 'D')";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();
                $info = $result->fetch_array(MYSQLI_ASSOC);
                $result->free();
            }
            return $info["Tipo"];
        }

        public function removeLike($id, $usern){
            $mysqli = $this->getConnection();
            $sql_query = "DELETE FROM interazione WHERE NickIns LIKE \"".$usern."\" AND IdL=? AND (Tipo LIKE 'L' OR Tipo LIKE 'D')";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
            }
        }

        public function addReact($id, $usern, $react){
            $mysqli = $this->getConnection();
            $sql_query = "INSERT INTO interazione (Tipo,Data,Testo,NickIns,IdL) VALUES (\"".$react."\",now(),NULL,\"".$usern."\",?)";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();            
                if($result>0) return true;
            }
            return false;
        }

        public function addReport($id, $usern){
            $mysqli = $this->getConnection();
            $sql_query ="SELECT * FROM interazione WHERE Tipo=\"S\" AND NickIns=\"".$usern."\" AND IdL=?";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result =  $prep->num_rows;
                $prep->close();
            }
            if($result==0){
                $sql_query = "INSERT INTO interazione (Tipo,Data,Testo,NickIns,IdL) VALUES ('S',now(),NULL,\"".$usern."\",?)";
                if($prep = $mysqli->prepare($sql_query)){
                    $prep-> bind_param("i", $id);
                    $prep-> execute();
                    $prep-> store_result();
                    $result= $prep->affected_rows;
                    $prep->close();
                    if($result>0) return true;
                }
            }
            return false;
        }

        public function likePressed($id, $usern, $react){
            $youreact= $this->getLikes($id, $usern);
            if (isset($youreact) && $youreact==$react){
                $this->removeLike($id, $usern);
            }
            else{
                $this->removeLike($id, $usern);
                $this->addReact($id, $usern, $react);
            }
        }

        public function addComment($id, $usern, $text){
            $mysqli = $this->getConnection();
            $text=strip_tags($text);
            if(!empty($text)){
                $sql_query = "INSERT INTO interazione (Tipo,Data,Testo,NickIns,IdL) VALUES (\"C\",now(),?,\"".$usern."\",?)";
                if($prep = $mysqli->prepare($sql_query)){
                    $prep-> bind_param("si", $text, $id);
                    $prep-> execute();
                    $prep-> store_result();
                    $result= $prep->affected_rows;
                    $prep->close();
                    if($result>0) return true;
                }
            }
            return false;
        }

        public function getBestArt(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT *, IFNULL(lik,0) AS likes FROM lista LEFT JOIN (SELECT Idl,COUNT(Tipo) As lik FROM interazione WHERE Tipo LIKE \"L\" GROUP BY Idl) AS l ON lista.Id=Idl WHERE Pubblico IS TRUE ORDER BY likes DESC LIMIT 20";
            $result = $mysqli->query($sql_query);
            $b_a=array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $b_a[] = $row;
            }
            $result->free();
            return $b_a;
        }

        public function getRecentArt(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM lista WHERE Pubblico IS TRUE ORDER BY DataCreazione DESC";
            $result = $mysqli->query($sql_query);
            $r_a=array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $r_a[] = $row;
            }
            $result->free();
            return $r_a;
        }

        public function search($keystring, $cat){
            $mysqli = $this->getConnection();

            $found= array();
            $ricerche= array();
            $tipi = "";
            $query = "SELECT lista.Id AS ArtId, Titolo, Sottotitolo, Autore, Categoria,LinkFig, AltFig, COUNT(elemento.Id) FROM lista LEFT JOIN elemento ON lista.Id=elemento.IdL WHERE ";

            if($cat!="") {
                $query .="categoria LIKE ? AND (";
                $tipi .= 's';
            }

            //prendo e separo termini ricerca
            if($keystring!="") {
                $ricerche = explode(" ", $keystring);           

                //Costruisco query con elementi cercati e aggiungo % universali
                for($i=0; $i < sizeof($ricerche); $i++){
                        $ricerche[$i] = "%".$ricerche[$i]."%";
                        $query .="(lista.Titolo LIKE ? OR elemento.Etichetta LIKE ? )";
                        if($i != sizeof($ricerche)-1) $query .= " OR ";
                        $tipi .= 'ss';
                }
                if($cat!="")  $query .= ") ";
            }
            else $query .="TRUE) ";
            $query .="AND Pubblico = TRUE GROUP BY lista.Id ORDER BY DataCreazione LIMIT 14";

            if($cat=="") $ricerche = array_merge(array($tipi),$ricerche,array_reverse($ricerche));
            else $ricerche = array_merge(array($tipi),array($cat),$ricerche,array_reverse($ricerche));

            if ($prep = $mysqli->prepare($query)) {
                $prep->bind_param(...$ricerche);
                $prep-> execute();
                $result= $prep->get_result();
                while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                    $found[] = $row;
                }
            }
            return $found;
        }

        public function createList($usern, $titolo, $stitolo, $img, $altfig, $desc, $cat){
                $mysqli = $this->getConnection();
                $sql_query = "INSERT INTO lista (Autore,Titolo,Sottotitolo,Descrizione,Pubblico,LinkFig,AltFig,categoria,DataCreazione) VALUES(\"".$usern."\",?,?,?,FALSE,NULL,?,?,now());";
                if($prep = $mysqli->prepare($sql_query)){
                    $prep-> bind_param("sssss",$titolo, $stitolo, $desc, $altfig, $cat);
                    $prep-> execute();
                    if($img["name"]!="") $this->updateListImg(mysqli_insert_id($mysqli), $img);
                    $prep-> store_result();
                    $result= $prep->affected_rows;
                    $prep->close();
                    if($result>0) return true;
                }
            return false;
        }

        public function publicList($id, $usern){
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE lista SET Pubblico=TRUE WHERE Id=? AND Autore=\"".$usern."\"";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();
                if($result>0) return true;
            }
            return false;
        }

        public function editList($id, $titolo, $stitolo, $img, $altfig, $desc, $cat){
            $imgchanged=false;
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE lista SET Titolo=?, Sottotitolo=?, AltFig=?, Descrizione=?, Categoria=? WHERE Id=?";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("sssssi", $titolo, $stitolo, $altfig, $desc , $cat, $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();
                if($img["name"]!="")  $imgchanged=$this->updateListImg($id, $img);
                if($result>0 || $imgchanged) return true;
            }
        return false;
        }

        public function updateListImg($id, $img){
            $mysqli = $this->getConnection();
            $img['name']=uploadImage($img ,"article_image", "List".$id);
            $sql_query = "UPDATE lista SET LinkFig=\"".$img['name']."\" WHERE Id=?";            
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();          
                if($img['name']!=false) return true;
            }
            return false;
        }

        public function editElem($idl, $bid, $nome ,$img, $altimg, $etichetta){
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE elemento SET Nome=?, Etichetta=?, AltImg=? WHERE IdL=? AND Id=? ";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("sssii", $nome, $etichetta, $altimg, $idl , $bid);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();
                if($img["name"]!="") $this->updateBlockImg($idl, $bid, $img);
                if($result>0) return true;
            }
            return false;
        }

        public function createElem($idl, $nome, $img , $altimg, $etichetta){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT COUNT(Id) AS NumBlock FROM elemento WHERE IdL=?";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i",$idl);
                $prep-> execute();
                $result= $prep->get_result();
                $prep->close();
                $ord = $result->fetch_array(MYSQLI_ASSOC);
                $result->free();
                if(!isset($ord)) $info=0;
                else $ord=$ord['NumBlock'];  

                $sql_query = "INSERT INTO elemento (Nome,Etichetta,LinkImg,AltImg,Ordine,IdL) VALUES (?,?,NULL,?,".($ord+1).",?)";
                if($prep = $mysqli->prepare($sql_query)){
                    $prep-> bind_param("sssi", $nome, $etichetta, $altimg, $idl);
                    $prep-> execute();
                    if($img["name"]!="") $this->updateBlockImg($id, mysqli_insert_id($mysqli), $img);
                    $prep-> store_result();
                    $result= $prep->affected_rows;
                    $prep->close();            
                    if($result>0) return true;
                }
            }
            return false;
        }

        public function updateBlockImg($idl,$id, $img){
            $mysqli = $this->getConnection();
            $img['name']=uploadImage($img ,"article_image", "List".$idl."-".$id);           
            $sql_query = "UPDATE elemento SET LinkImg=\"".$img['name']."\" WHERE Id=?";  
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();            
                if($result>0) return true;
            }
            return false;
        }

        public function deleteArt($id){
            $mysqli = $this->getConnection();
            $sql_query ="DELETE FROM lista WHERE Id=?;";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();            
                if($result>0) return true;
            }
            return false;
        }

        public function deleteElemOrd($idA,$nOrd){
            $mysqli = $this->getConnection();
            $sql_query ="DELETE FROM elemento WHERE IdL=? AND Ordine=?;";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("ii", $idA, $nOrd);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();            
                if($result>0)return true;
            }
            return false;
        }

        public function deleteInteraction($idA,$idI){
            $mysqli = $this->getConnection();
            $sql_query ="DELETE FROM interazione WHERE IdL=? AND id=?;";
            if($prep = $mysqli->prepare($sql_query)){
                $prep-> bind_param("ii", $idA, $idI);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();            
                if($result>0)return true;
            }
            return false;
        }

        public function moveElem($id, $nOrd,$verso){
            if ($verso=="u") $targetPos=$nOrd-1;
            else if($verso=="d") $targetPos=$nOrd+1;
            else return false;
            $mysqli = $this->getConnection();
            $sql_query ="UPDATE elemento SET Ordine=NULL WHERE Ordine=".$targetPos." AND IdL=?";
            if($prep = $mysqli->prepare($sql_query)){
            $prep-> bind_param("i", $id);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();
                if($result>0){
                    $sql_query = "UPDATE elemento SET Ordine=".$targetPos." WHERE Ordine=".$nOrd." AND IdL=?";
                    if($prep = $mysqli->prepare($sql_query)){
                        $prep-> bind_param("i", $id);
                        $prep-> execute();
                        $prep-> store_result();
                        $result= $prep->affected_rows;
                        $prep->close();            
                        if($result>0){
                            $sql_query = "UPDATE elemento SET Ordine=".$nOrd." WHERE Ordine IS NULL AND IdL=? ";
                            if($prep = $mysqli->prepare($sql_query)){
                                $prep-> bind_param("i", $id);
                                $prep-> execute();
                                $prep-> store_result();
                                $result= $prep->affected_rows;
                                $prep->close();            
                                if($result>0)return true;
                            }
                        }
                    }
                }
            }
            return false;
        }

        public function getMostSignal(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM lista INNER JOIN (SELECT IdL, COUNT(IdL) AS Gravita FROM interazione WHERE Tipo='S' GROUP BY IdL) AS sign ON lista.Id = IdL ORDER BY Gravita DESC";
            $result = $mysqli->query($sql_query);
            $art= array();
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $art[] = $row;
            }
            $result->free();
            return $art;
        }

        public function getUserList(){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM utente ORDER BY Ruolo, Username";
            $result = $mysqli->query($sql_query);
            $user= array();
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $user[] = $row;
            }
            $result->free();
            return $user;
        }

        public function banUser($usern){
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE utente SET Ruolo='B' WHERE Username=\"".$usern."\" AND Ruolo=\"U\"";
            $result = $mysqli->query($sql_query);
            return true;
        }

        public function unbanUser($usern){
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE utente SET Ruolo='U' WHERE Username=\"".$usern."\" AND Ruolo=\"B\"";
            $result = $mysqli->query($sql_query);
            return true;
        }

        public function userToAdmin($usern){
            $mysqli = $this->getConnection();
            $sql_query = "UPDATE utente SET Ruolo='A' WHERE Username=\"".$usern."\" AND Ruolo=\"U\"";
            $result = $mysqli->query($sql_query);
            return true;
        }

        public function removeReport($idl){
            $mysqli = $this->getConnection();
            $sql_query = "DELETE FROM interazione WHERE IdL=? AND Tipo='S'";
            if($prep = $mysqli->prepare($sql_query)){
            $prep-> bind_param("i", $idl);
                $prep-> execute();
                $prep-> store_result();
                $result= $prep->affected_rows;
                $prep->close();
                if($result>0) return true;
            }
            return false;
        }

        public function getUserRole($usern){
            $mysqli = $this->getConnection();
            $sql_query = "SELECT * FROM utente WHERE Username=\"".$usern."\"";
            $result = $mysqli->query($sql_query);
            $permit = $result->fetch_array(MYSQLI_ASSOC);
            return $permit["Ruolo"];
        }
    }

    session_start();
    if (isset($_POST["logout"])){
        logout();
    }
    if(isset($_SESSION[ "logName"])) {
        $usern=$_SESSION["logName"];
        $role =$_SESSION["logRole"];

    }
?>
