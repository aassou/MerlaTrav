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
    if( !empty($_POST['nomProjet'])){
        $nomProjet = htmlentities($_POST['nomProjet']);    
        $adresse = htmlentities($_POST['adresse']);
        $superficie = 0;
        $budget = 0;
        if(filter_var($_POST['superficie'], FILTER_VALIDATE_FLOAT)==true){
            $superficie = htmlentities($_POST['superficie']);
        }
        else {
            $_SESSION['projet-add-error']="<strong>Erreur Ajout Projet</strong> : La valeur du champ 'Superficie' est incorrecte !";
            header('Location:../projet-add.php');
            exit;
        }
        if(filter_var($_POST['budget'], FILTER_VALIDATE_FLOAT)==true){
            $budget = htmlentities($_POST['budget']);
        }
        else {
            $_SESSION['projet-add-error']="<strong>Erreur Ajout Projet</strong> : La valeur du champ 'Budget' est incorrecte !";
            header('Location:../projet-add.php');
            exit;
        }
        $description = htmlentities($_POST['description']);
        
        $projet = new Projet(array('nom' => $nomProjet, 'adresse' => $adresse,'superficie' => $superficie, 
        'description' =>$description, 'budget' => $budget));
        $projetManager = new ProjetManager($pdo);
        $projetManager->add($projet);
        $_SESSION['projet-add-success']="<strong>Opération valide : </strong>Le projet '".strtoupper($nomProjet)."' est ajouté avec succès !";
    }
    else{
        $_SESSION['projet-add-error'] = "<strong>Erreur Ajout Projet : </strong>Vous devez remplir au moins le champs 'Nom du projet'.";
    }
	header('Location:../projet-add.php');
    