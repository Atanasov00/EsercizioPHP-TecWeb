<?php
    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $database = "giugno";
    $port = "3307";

    $conn = new mysqli($host, $user, $password, $database, $port);

    if($conn === false){
        die("Errore di connesone: " . $conn->connect_error);
    }

    //echo("Connessione avvenuta con successo" . $conn->host_info);

    /*function queryDB($query, $conn){
        $result = mysqli_query($conn, $query);
        return $result;
    }*/


    $a = $_GET['A'];
    $b = $_GET['B'];

    $isPresentA = false;
    $isPresentB = false;

    /* Controllo sui parametri A e B*/
    if((is_null($a) || is_null($b)) || ($a < 0 || $b < 0)){
        die("<br>Errore sui valori inseriti");
    } else {
        $query = "select insieme from insiemi";
        $result = mysqli_query($conn, $query);
        
        while($row = $result->fetch_assoc()){
            if($row["insieme"] == $a){
                $isPresentA = true;
            }
            if($row["insieme"] == $b){
                $isPresentB = true;
            }
        }
    
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

    $query = "select valore from insiemi where insieme = $a";
    $result = mysqli_query($conn, $query);
    while($row = $result->fetch_assoc()){
        $vectorA[] = $row["valore"];
    }

    $query = "select valore from insiemi where insieme = $b";
    $result = mysqli_query($conn, $query);
    while($row = $result->fetch_assoc()){
        $vectorB[] = $row["valore"];
    }
    /*echo("<br>");
    print_r($vectorA);
    echo("<br>");
    print_r($vectorB);*/
    if($o == 'u'){
        $vector = array_merge($vectorA, $vectorB);
    } else {
        $vector = array_intersect($vectorA, $vectorB);
    }
    
    print_r($vector);

    $conn->close();

?>