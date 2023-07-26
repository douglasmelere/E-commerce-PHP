<?php
    session_start();
    if(isset($_SESSION['logado']) || !$_SESSION['logado']){
        header('LOCATION: login.php');
    }

    echo "Bem vindo " . $_SESSION['nome'];

?>