<?php
    //classes loading begin
    function classLoad ($myClass) {
        if(file_exists('../model/'.$myClass.'.php')){
            include('../model/'.$myClass.'.php');
        }
        elseif(file_exists('../controller/'.$myClass.'.php')){
            include('../controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('../config.php');  
    //classes loading end
    session_start();
    
    //post input processing
    $idProjet = $_POST['idProjet'];
    if( !empty($_POST['nomProjet'])){
        $nomProjet = htmlentities($_POST['nomProjet']);    
        $adresse = htmlentities($_POST['adresse']);
        $superficie = htmlentities($_POST['superficie']);
        $description = htmlentities($_POST['description']);
        $budget = htmlentities($_POST['budget']);
        if(filter_var($_POST['superficie'], FILTER_VALIDATE_FLOAT)==true){
            $superficie = htmlentities($_POST['superficie']);
        }
        else {
            $_SESSION['projet-update-error']="<strong>Erreur Modification Projet : </strong>La valeur du champ 'Superficie' est incorrecte.";
            header('Location:../projet-update.php?idProjet='.$idProjet);
            exit;
        }
        if(filter_var($_POST['budget'], FILTER_VALIDATE_FLOAT)==true){
            $budget = htmlentities($_POST['budget']);
        }
        else {
            $_SESSION['projet-update-error']="<strong>Erreur Modification Projet : </strong>La valeur du champ 'Budget' est incorrecte.";
            header('Location:../projet-update.php?idProjet='.$idProjet);
            exit;
        }
        $description = htmlentities($_POST['description']);
        
        $projet = new Projet(array('id' => $idProjet, 'nom' => $nomProjet, 'adresse' => $adresse,'superficie' => $superficie, 
        'description' => $description, 'budget' => $budget));
        $projetManager = new ProjetManager($pdo);
        $projetManager->update($projet);
        $_SESSION['projet-update-success']="<strong>Opération valide : </strong>Votre projet '".$nomProjet."' est modifié avec succès.";
        header('Location:../projet-update.php?idProjet='.$idProjet);
    }
    else{
        $_SESSION['projet-update-error'] = "<strong>Erreur Modification Projet : </strong>Vous devez remplir au moins le champ 'Nom du projet'.";
        header('Location:../projet-update.php?idProjet='.$idProjet);
    }
    