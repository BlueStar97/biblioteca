<?php
    session_start();
    
    if($_SESSION["permit"]!='A')
    {
        session_destroy();
        header("location:index.html");
        die();
    }
    
    echo "<html><head><title>Alunno</title></head><body>";
    
    $host="localhost";
    $username="root";
    $password="";
    $dbname="biblio";
    
    $conn = new mysqli($host, $username, $password, $dbname);
    
    //Checking for the prenoted books
    $query = "SELECT ISBN.nome 
                FROM (ISBN i INNER JOIN libro l ON i.ISBN = l.ISBN) INNER JOIN prestito p ON l.IdLib=p.IdLib 
                WHERE p.username = '" . $_SESSION["user"] . "';";
        
    $res=$conn->query($query);
    
    $risultato = "Questi sono i libri che hai in prestito:<br/><table>";
    
    while($row = $res -> fetch_assoc())
    {
        $risultato += "<tr><td>" . $row["nome"] . "</td></tr>";
    }
    
    $risultato+="</table>";
    
    if($risultato!="Questi sono i libri che hai in prestito:<br/><table></table>")
        echo $risultato + "<br/><br/><br/>";
    
    if($_POST["case"]=="Ricerca")
    {
        $query = "SELECT nome, ISBN, copiedisp 
                    FROM ISBN 
                    WHERE nome = '" . $_POST["nomeLib"] . "';";
        
        $res=$conn->query($query);
        
        if($row = $res -> fetch_assoc())
        {
            echo "Ecco il libro che cercavi:<br/>
            <table><tr><th>Nome</th><th>ISBN</th><th>Copie Disponibili</th></tr>
            <tr><td>" . $row["nome"] . "</td><td>" . $row["ISBN"] . "</td><td>" . $row["copiedisp"] . "</td></tr>
            </table><br/><br/>
            Effettua la Prenotazione!<br/>
            <form method='POST' action='alunno.php'
                <input type='date' nome='datainit'>
                <input type='date' nome='datafin'>
                <input type='submit' name='case' value='Prenota!'>
            </form>
            ";
            
        }
        else
        {
            echo "Non sono stati trovati libri con il nome corrispondente.";
        }
        
        echo "<br/><br/><br/>";  
    }
    
    if($_POST["case"]=="Prenota!")
    {
        $datafin=$_POST["Datafin"];
        $datainit=$_POST["Datainit"];
        
        $yearfin=intval($datafin[0]+$datafin[1]+$datafin[2]+$datafin[3]);
        $monthfin=intval($datafin[5]+$datafin[6]);
        $dayfin=intval($datafin[8]+$datafin[9]);
        
        $yearinit=intval($datainit[0]+$datainit[1]+$datainit[2]+$datainit[3]);
        $monthinit=intval($datainit[5]+$datainit[6]);
        $dayinit=intval($datainit[8]+$datainit[9]);
        
        if((($yearfin-$yearinit)=0)||(($yearfin-$yearinit)=1))
        {
            
        }
    }
    
    
?>