<?php
    session_start();
    
    //Checking the session
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
    
    //Lends
    $risultato = "Questi sono i libri che hai in prestito:<br/><table>";
    
    while($row = $res -> fetch_assoc())
        $risultato += "<tr><td>" . $row["nome"] . "</td></tr>";

    
    $risultato+="</table>";
    
    if($risultato!="Questi sono i libri che hai in prestito:<br/><table></table>")
        echo $risultato + "<br/><br/><br/>";
    
    //Searching for a book
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
            Richiedi un prestito!<br/>
            <form method='POST' action='alunno.php'>
                <input type='date' nome='datainit'>
                <input type='date' nome='datafin'>
                <input type='hidden' name='nome' value='" . $row["nome"] . "'>
                <input type='submit' name='case' value='Richiedi il prestito!'>
            </form><br/>
            Oppure effettua una prenotazione!
            <form method='POST' action='alunno.php'>
                <input type='date' nome='datafin'>
                <input type='hidden' name='copie' value='" . $row["copiedisp"] . "'>
                <input type='hidden' name='nome' value='" . $row["nome"] . "'>
                <input type='submit' name='case' value='Prenota!'>
            </form>";
            
        }
        else
        {
            echo "Non sono stati trovati libri con il nome corrispondente.";
        }
        
        echo "<br/><br/><br/>";
    }
    
    //function used for prenoting
    function LendPrenote($name, $datafin, $datainit)
    {
        $yearfin=intval($datafin[0]+$datafin[1]+$datafin[2]+$datafin[3]);
        $monthfin=intval($datafin[5]+$datafin[6]);
        $dayfin=intval($datafin[8]+$datafin[9]);
        
        $yearinit=intval($datainit[0]+$datainit[1]+$datainit[2]+$datainit[3]);
        $monthinit=intval($datainit[5]+$datainit[6]);
        $dayinit=intval($datainit[8]+$datainit[9]);
        
        if(($yearfin-$yearinit)==0)
        {
            $days=($monthfin-$monthinit)*30+($dayfin-$dayinit);
        }
        
        else
        {
            if(($yearfin-$yearinit)==1)
            {
                $days=($monthfin-$monthinit)*30+($dayfin-$dayinit)+365;
            }
            else
            {
                $days=-1;
            }
        }
        
        if(($days>0)&&($days<30))
        {
            $query = "SELECT l.IdLib, l.ISBN, p.datainit, p.datafin 
                    FROM (ISBN i INNER JOIN libro l ON i.ISBN=l.ISBN) INNER JOIN prestito p ON l.IdLib=p.IdLib 
                    WHERE i.nome='" . $name . "';";
        
            $res=$conn->query($query);
            
            $found=false;
            
            while(($row=$res->fetch_assoc()) && ($found=false))
            {
                if(((strcmp($row["datainit"],$datainit)>1)&&(strcmp($datafin,$row["datainit"])>1))||((strcmp($row["datafin"],$datainit)>1)&&(strcmp($datafin,$row["datafin"])>1))||((strcmp($datainit,$row["datainit"])>1)&&(strcmp($row["datafin"],$datainit)>1)))
                {
                    $found=true;
                }
            }
            
            if($found==false)
            {
                $query="INSERT INTO prestito(IdLib, username, datainit, datafine) 
                        VALUES ('" . $row["IdLib"] . "', '" . $_SESSION["user"] . "', '" . $datainit . "', '" . $datafin . "')";
                $conn->query($query);
                
                $query="UPDATE ISBN SET copiedisp=copiedisp-1 WHERE ISBN='" . $row["ISBN"] . "';";
                $conn->query($query);
                    
                echo "Libro prenotato!";
            }
            else
            {
                echo "Errore nella prenotazione";
            }
        }
        else
        {
            echo "Non puoi richiedere un prestito per più di 30 gg!";
        }
        echo "<br/><br/><br/>";
    }
    
    //Asking for a lend
    if($_POST["case"]=="Richiedi il prestito!")
    {
        $name=$_POST["nome"];
        $datafin=$_POST["Datafin"];
        $datainit=$_POST["Datainit"];
        
        LendPrenote($name, $datafin, $datainit);
    }
    
    //asking for a prenotation
    if($_POST["case"]=="Prenota!")
    {
        if($_POST["copie"]>0)
        {
            $name=$_POST["nome"];
            $datafin=$_POST["Datafin"];
            $datainit=date("Y/m/d");
            
            LendPrenote($name, $datafin, $datainit);
        }
    }
    
    //Sending a message
    if($_POST["case"]=="Invia")
    {
        $query="INSERT INTO messages(username, message, read, to) 
                VALUES ('" . $_SESSION["user"] . "','" . $_POST["msg"] . "','N', 'Admin')";
        $conn->query($query);
        
        echo "Messaggio inviato!<br/><br/><br/>";
    }
    
    $conn->close();
    
    echo "Effettua una ricerca!<br/>
        <form method='POST' action='alunno.php'
            <input type='text' name='nomeLib'>
            <input type='submit' name='case' value='Ricerca'>
        </form><br/><br/><br/>";
    
    echo "Invia un messaggio all'admin!<br/>
        <form method='POST' action='alunno.php'
            <input type='textarea' name='msg'>
            <input type='submit' name='case' value='Invia'>
        </form>";
?>