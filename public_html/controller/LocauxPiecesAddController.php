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
	$idLocaux = htmlentities($_POST['idLocaux']);
	if(file_exists($_FILES['url']['tmp_name']) || is_uploaded_file($_FILES['url']['tmp_name'])) {
		$url = imageProcessing($_FILES['url'], '/pieces/pieces_locaux/');
		$nom = htmlentities($_POST['nom']);
		$pieceLocaux = new PiecesLocaux(array('nom'=>$nom, 'url'=>$url, 'idLocaux'=>$idLocaux));
		$pieceLocauxManager = new PiecesLocauxManager($pdo);
		$pieceLocauxManager->add($pieceLocaux);
		$_SESSION['pieces-add-success'] = "<strong>Opération valide : </strong>La pièce a été ajouté avec succès.";
		header('Location:../locaux.php?idProjet='.$idProjet);
	}
	else{
		$_SESSION['pieces-add-error'] = "<strong>Erreur Ajout Pièces Local : </strong>Vous devez ajouté un lien.";
		header('Location:../locaux.php?idProjet='.$idProjet);
	}
