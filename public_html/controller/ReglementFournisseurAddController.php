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
	$idProjet = htmlentities($_POST['idProjet']);
    if( !empty($_POST['dateReglement']) && !empty($_POST['montant']) && !empty($_POST['fournisseur']) ){
    	$idFournisseur = htmlentities($_POST['fournisseur']);    
        $dateReglement = htmlentities($_POST['dateReglement']);    
        $montant = htmlentities($_POST['montant']);
        //create a new Operation object
        $reglementFournisseur = new ReglementFournisseur(array('dateReglement' => $dateReglement, 
        'montant' => $montant,'idProjet' => $idProjet, 'idFournisseur' => $idFournisseur));
        $reglementFournisseurManager = new ReglementFournisseurManager($pdo);
        $reglementFournisseurManager->add($reglementFournisseur);
        $_SESSION['reglement-add-success']="<strong>Opération valide</strong> : Le réglement du fournisseur est réalisé avec succès.";
        header('Location:../fournisseur-reglement.php?idProjet='.$idProjet);
    }
    else{
        $_SESSION['reglement-add-error'] = "<strong>Erreur Ajout Réglement Fournisseur</strong> : Vous devez remplir les champs 'Fournisseu', 'Date réglement' et 'Montant'.";
        header('Location:../fournisseur-reglement.php?idProjet='.$idProjet);
    }
    