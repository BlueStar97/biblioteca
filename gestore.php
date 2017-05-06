<?php
    session_start();
    
    //Checking session
    if($_SESSION["permit"]!='G')
    {
        session_destroy();
        header("location:index.html");
        die();
    }
    
    echo "<html>
            <head>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'>
                    $(document).ready(function(){
                        $('#mostraus').click(function(){
                            $('#showus').show();
                        })
                    })
                    $(document).ready(function(){
                        $('#mostrapres').click(function(){
                            $('#showpres').show();
                        })
                    })
                </script>
                <style>
                    #showed{visibility:hidden;}
                </style>
                <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
                <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
            </head>
            <body>";
    
    $host="localhost";
    $username="root";
    $password="";
    $dbname="biblio";
    
    $conn = new mysqli($host, $username, $password, $dbname);
    
    $query = "SELECT username, email, nome, cognome, classe FROM Account";
    
    $res=$conn->query($query);
    
    //listing Users
    echo "<button type='button' id='mostraus'>Mostra User</button>";
    echo "<div class='container' id='showus' style='display: none;'><div class='row'>
            <div class='col-sm-2'>
                Username
            </div>
            <div class='col-sm-2'>
                Email
            </div>
            <div class='col-sm-2'>
                Nome
            </div>
            <div class='col-sm-2'>
                Cognome
            </div>
            <div class='col-sm-2'>
                Classe
            </div>
            <div class='col-sm-2'>
                Elimina Account
            </div>
        </div>";
    
    while($row=$res->fetch_assoc())
    {
        echo "<form action='gestore.php' method='POST'>
                    <div class='row'>
                        <div class='col-sm-2'>
                            " . $row["username"] . "
                        </div>
                        <div class='col-sm-2'>
                            " . $row["email"] . "
                        </div>
                        <div class='col-sm-2'>
                            " . $row["nome"] . "
                        </div>
                        <div class='col-sm-2'>
                            " . $row["cognome"] . "
                        </div>
                        <div class='col-sm-2'>
                            " . $row["classe"] . "
                        </div>
                        <div class='col-sm-2'>
                            <input type='hidden' name='choice' value='" . $row["username"] . "'>
                            <input type='submit' value='Elimina Account'>
                        </div>
                    </div>
                </form>";
    
    }
    
    echo "</div><br/><br/><br/>";
    
    if($_POST["case"]=='Ricerca')
    {
        $query = "SELECT nome, ISBN, copiedisp, copietot 
                    FROM ISBN 
                    WHERE nome LIKE '" . $_POST["nomeLib"] . "';";
        
        $res=$conn->query($query);
        
        if($row = $res -> fetch_assoc())
        {
            echo "Ecco il libro che cercavi:<br/>
            <table><tr><th>Nome</th><th>ISBN</th><th>Copie prestate</th></tr>
            <tr><td>" . $row["nome"] . "</td><td>" . $row["ISBN"] . "</td><td>" . ($row["copietot"]-$row["copiedisp"]) . "</td></tr>
            </table><br/><br/>";
            //adding
            echo "Aggiungi copie<br/>
                    <form method='POST' action='gestore.php'>
                        <input type='number' name='copie'>
                        <input type='hidden' name='nome' value='" . $row["nome"] . "'>
                        <input type='submit' name='case' value='Aggiungi'>
                    </form><br/><br/><br/>";
                    
            //removing
            if($row["copiedisp"]>0)
            {
                echo "Rimuovi copie(massimo: " . $row["copiedisp"] . ")<br/>
                        <form method='POST' action='gestore.php'>
                            <input type='number' name='copie'>
                            <input type='hidden' name='nome' value='" . $row["nome"] . "'>
                            <input type='hidden' name='max' value='" . $row["copiedisp"] . "'>
                            <input type='submit' name='case' value='Rimuovi'>
                        </form><br/><br/><br/>";
            }
        }
    }
    
    echo "<div class='row'><div class='col-sm-3'>";
    
    //Searching for a book
    echo "Effettua una ricerca<br/>
    <form method='POST' action='gestore.php'
        <input type='text' name='nomeLib'>
        <input type='submit' name='case' value='Ricerca'>
    </form><br/><br/><br/>";
    
    $conn->close();
?>