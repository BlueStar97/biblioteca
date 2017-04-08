<?php
    $host="localhost";
    $username="root";
    $password="";
    $dbname="biblio";
    
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if(!($conn->connect_error))
    {
        session_start();
        $userLog=$_POST["user"];
        $pwLog=$_POST["pw"];
        
        $query = "SELECT username, password, permessi, email 
                    FROM Account 
                    WHERE username='" . $userLog . "' OR email='" . $userLog . "';";
        
        $res=$conn->query($query);
        
        if($row = $res->fetch_assoc())
        {
            if(((strcmp($row["username"], $userLog)==0)&&(strcmp($row["password"], $pwLog)==0))OR((strcmp($row["email"], $userLog)==0)&&(strcmp($row["password"], $pwLog)==0)))
            {
                $_SESSION["user"]=$row["username"];
                $_SESSION["permit"]=$row["permessi"];
                
                if(strcmp($_SERVER["permit"], 'A')==0)
                    header("location: alunno.php");
                else
                    if(strcmp($_SERVER["permit"], 'G')==0)
                        header("location: gestore.php");
                die();
            }
        }
        
        session_destroy();
        session_start();
        $_SESSION["situation"]="user_pass_err";
        header("location:index.html");
        die();
    }
?>