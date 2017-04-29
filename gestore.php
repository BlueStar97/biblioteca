<?php
    session_start();
    
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
                        $('#mostra').click(function(){
                            $('#showed').show();
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
    
    echo "<div class='row'>
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
        echo "<form action='delete.php' method='POST'>
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
?>