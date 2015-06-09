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
    if( !empty($_POST['dateReglement']) && !empty($_POST['montant']) ){
        $idReglement = htmlentities($_POST['idReglement']);
        $dateReglement = htmlentities($_POST['dateReglement']);    
        $montant = htmlentities($_POST['montant']);
        //create a new Operation object
        $reglementFournisseur = new ReglementFournisseur(array('dateReglement' => $dateReglement, 
        'montant' => $montant, 'id' => $idReglement));
        $reglementFournisseurManager = new ReglementFournisseurManager($pdo);
        $reglementFournisseurManager->update($reglementFournisseur);
        $_SESSION['reglement-update-success']="<strong>Opération valide</strong> : Le réglement du fournisseur est modifié avec succès.";
        header('Location:../fournisseur-reglement.php?idProjet='.$idProjet);
    }
    else{
        $_SESSION['reglement-update-error'] = "<strong>Erreur Modification Réglement Fournisseur</strong> : Vous devez remplir les champs 'Date réglement' et 'Montant'.";
        header('Location:../fournisseur-reglement.php?idProjet='.$idProjet);
    }
    