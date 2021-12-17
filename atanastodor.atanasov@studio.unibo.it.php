<?php
    class DatabaseHelper{
        private $db;
    
        public function __construct($servername, $username, $password, $dbname, $port){
            $this->db = new mysqli($servername, $username, $password, $dbname, $port);
            if($this->db->connect_error){
                die("Connesione fallita al db");
            }
        }
    
        public function getVal($v){
            $stmt = $this->db->prepare("SELECT * FROM insiemi WHERE insieme=?");
            $stmt->bind_param("i", $v);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    
        public function getIns(){
            $stmt = $this->db->prepare("SELECT * FROM insiemi");
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    
        public function getID(){
            $stmt = $this->db->prepare("SELECT MAX(insieme) AS insieme FROM insiemi");
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_object();
        }

        public function insertIns($id, $valore, $insieme){
            $stmt = $this->db->prepare("INSERT INTO insiemi (id, valore, insieme) VALUES (?,?,?)");
            $stmt->bind_param("iii", $id, $valore, $insieme);
            $stmt->execute();
        }
    
    }

    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $database = "giugno";
    $port = "3307";

    $conn = new DatabaseHelper($host, $user, $password, $database, $port);

    $a = $_GET['A'];
    $b = $_GET['B'];

    $isPresentA = false;
    $isPresentB = false;

    /* Controllo sui parametri A e B*/
    if((is_null($a) || is_null($b)) || ($a < 0 || $b < 0)){
        die("<br>Errore sui valori inseriti");
    } else {
        $rows = $conn->getIns();
        foreach($rows as $row){
            if($row["insieme"] == $a){
                $isPresentA = true;
            }
            if($row["insieme"] == $b){
                $isPresentB = true;
            }
        }
        /*while($row = $result->fetch_assoc()){
            if($row["insieme"] == $a){
                $isPresentA = true;
            }
            if($row["insieme"] == $b){
                $isPresentB = true;
            }
        }*/
        if(!$isPresentA || !$isPresentB){
            die("<br>I valori inseriti non sono presenti nell'insieme");
        }
    }

    /*Controllo sul parametro O*/
    $o = $_GET['O'];
    
    if(is_null($o) || ($o != 'i' && $o != 'u')){
        die("<br>Errore sui valori inseriti di O");
    }

    $vectorA = array();
    $vectorB = array();

    $rows = $conn->getVal($a);
    foreach($rows as $row){
        $vectorA[] = $row["valore"];
    }

    $rows = $conn->getVal($b);
    foreach($rows as $row){
        $vectorB[] = $row["valore"];
    }
    /*echo("<br>");
    print_r($vectorA);
    echo("<br>");
    print_r($vectorB);*/
    if($o == 'u'){
        $vector = array_unique(array_merge($vectorA, $vectorB));
    } else {
        $vector = array_intersect($vectorA, $vectorB);
    }
    
    print_r($vector);

    foreach($vector as $val){
        $conn->insertIns($conn->getID, $val, 3);
    }

    $conn->close();

?>