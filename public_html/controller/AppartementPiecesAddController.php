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
	include('../lib/image-processing.php');
    //classes loading end
    session_start();
	
	$idProjet = htmlentities($_POST['idProjet']);
	$idAppartement = htmlentities($_POST['idAppartement']);
	if(file_exists($_FILES['url']['tmp_name']) || is_uploaded_file($_FILES['url']['tmp_name'])) {
		$url = imageProcessing($_FILES['url'], '/pieces/pieces_appartement/');
		$nom = htmlentities($_POST['nom']);
		$pieceAppartement = new PiecesAppartement(array('nom'=>$nom, 'url'=>$url, 'idAppartement'=>$idAppartement));
		$pieceAppartementManager = new PiecesAppartementManager($pdo);
		$pieceAppartementManager->add($pieceAppartement);
		$_SESSION['pieces-add-success'] = "<strong>Opération valide : </strong>La pièce a été ajoutée avec succès.";
		header('Location:../appartements.php?idProjet='.$idProjet);
	}
	else{
		$_SESSION['pieces-add-error'] = "<strong>Erreur Ajout Documents Appartement : </strong>Vous devez ajouté un lien.";
		header('Location:../appartements.php?idProjet='.$idProjet);
	}
