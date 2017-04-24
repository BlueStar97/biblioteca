<?php

    session_start();

    $host="localhost";
    $username="root";
    $password="";
    $dbname="biblio";
    
    $conn = new mysqli($host, $username, $password, $dbname);

    $query="SELECT username, email 
            FROM Account 
            WHERE username <> ' ". $_POST["user"] ." ' OR email <> '" . $_POST["mail"] . "';";

    $res=$conn->query($query);
    
    if(mysql_num_rows($res)==0)
    {
       if($_POST["pw"]==$_POST["pw2"])
       {
           $query="INSERT INTO Account (username, password, email, permessi, nome, cognome, classe)
                    VALUES ('" . $_POST["user"] . "', '" . $_POST["pw"] . "', '" . $_POST["mail"] . "', 'A', '" . $_POST["name"] . "', '" . $_POST["surname"] . "', '" . $_POST["class"] . "')";
       
           $conn->query($query);
           
           $_SESSION["user"]=$_POST["user"];
           $_SESSION["permit"]="A";
       }
       else
       {
           session_destroy();
           session_start();
           $_SESSION["situation"]="err_pass_eq";
           header("location:index.html");
           die();
       }
    }
    else
    {
        $row=$res->fetch_assoc();
       
        if($POST["user"]==$row["username"])
        {
           session_destroy();
           session_start();
           $_SESSION["situation"]="err_user_tkn";
           header("location:index.html");
           die();
        }
        
        if($POST["mail"]==$row["email"])
        {
           session_destroy();
           session_start();
           $_SESSION["situation"]="err_mail_tkn";
           header("location:index.html");
           die();
        }
    }
?>