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
        -
    }
    
    echo "</div>";
    
    echo "<div class='row'><div class='col-sm-3'>";
    
    //Insert book
    echo "Inserisci un libro<br/>
            <form action='gestore.php' method='POST'>
                ISBN<input type='text' name='isbn'><br/>
                Nome<input type='text' name='name'><br/>
                Copie Totali<input type='number' name='copietot'><br/>
                <input type='submit' name='insert'><br/>
            </form>";
    
    echo "</div><div class='col-sm-3'>";
    
    //Ereasing books
    echo "<form action='gestore.php' method='POST'>
            Cancella un libro per ISBN<input type='text' name='isbn'><input type='submit' name='ereaseisbn'><br/>
            </form>";
            
    echo "</div><div class='col-sm-3'>";
    
    //Adding books
    echo "<form action='gestore.php' method='POST'>
            ISBN<input type='text' name='isbn'>
            Numero di libri<input type='number' name='number'>
            <input type='submit' name='add'>
            </form>";
            
    echo "</div><div class='col-sm-3'>";
    
    //Removing books
    echo "<form action='gestore.php' method='POST'>
            ISBN<input type='text' name='isbn'>
            Numero di libri<input type='number' name='number'>
            <input type='submit' name='erase'>
            </form>";
    
    echo "</div>";
    
    
?>