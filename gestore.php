<?php
    session_start();
    
    if($_SESSION["permit"]!='G')
    {
        session_destroy();
        header("location:index.html");
        die();
    }
    
    
?>