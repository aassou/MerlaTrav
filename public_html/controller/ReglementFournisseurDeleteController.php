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
	$idReglement = $_POST['idReglement'];
    $reglementFournisseurManager = new ReglementFournisseurManager($pdo);
	$reglementFournisseurManager->delete($idReglement);
	$_SESSION['reglement-delete-success'] = "<strong>Opération valide : </strong>Réglement supprimé avec succès.";
	header('Location:../fournisseur-reglement.php?idProjet='.$idProjet);
    
    