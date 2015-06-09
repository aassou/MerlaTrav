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
    if( !empty($_POST['code'])){
        $nom = htmlentities($_POST['code']);    
        $facade = htmlentities($_POST['facade']);
		$status = htmlentities($_POST['status']);
		$cave = htmlentities($_POST['cave']);
		$niveau = htmlentities($_POST['niveau']);
		$nombrePiece = htmlentities($_POST['nombrePiece']);		
        $superficie = 0;
        $prix = 0;
        if(filter_var($_POST['superficie'], FILTER_VALIDATE_FLOAT)==true){
            $superficie = htmlentities($_POST['superficie']);
        }
        else {
            $_SESSION['appartement-add-error']="<strong>Erreur Ajout Appartement</strong> : La valeur du champ 'Superficie' est incorrecte !";
            header('Location:../appartements.php?idProjet='.$idProjet);
            exit;
        }
        if(filter_var($_POST['prix'], FILTER_VALIDATE_FLOAT)==true){
            $prix = htmlentities($_POST['prix']);
        }
        else {
            $_SESSION['appartement-add-error']="<strong>Erreur Ajout Appartement</strong> : La valeur du champ 'Prix' est incorrecte !";
            header('Location:../appartements.php?idProjet='.$idProjet);
            exit;
        }
        
        $appartement = new Appartement(array('nom' => $nom, 'prix' => $prix,'superficie' => $superficie, 
        'facade' =>$facade, 'status' => $status, 'cave' => $cave, 'niveau' => $niveau, 
        'nombrePiece' => $nombrePiece,'idProjet' => $idProjet));
        $appartementManager = new AppartementManager($pdo);
        $appartementManager->add($appartement);
        $_SESSION['appartement-add-success']="<strong>Opération valide : </strong>L' Appartement est ajoutée avec succès !";
    }
    else{
        $_SESSION['appartement-add-error'] = "<strong>Erreur Ajout Appartement : </strong>Vous devez remplir au moins le champ 'Code'.";
    }
	header('Location:../appartements.php?idProjet='.$idProjet);
    