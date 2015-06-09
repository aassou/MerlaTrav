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
	$codeFournisseur = htmlentities($_POST['codeFournisseur']);
	if( !empty($_POST['idProjet']) and !empty($_POST['codeFournisseur']) ){
		if( !empty($_POST['libelle']) && !empty($_POST['prixUnitaire']) && !empty($_POST['quantite']) ){
			$idFournisseur = $_POST['idFournisseur'];
	        $libelle = htmlentities($_POST['libelle']);    
	        $designation = htmlentities($_POST['designation']);
	        $dateLivraison = htmlentities($_POST['dateLivraison']);
	        $quantite = htmlentities($_POST['quantite']);
	        $prixUnitaire = htmlentities($_POST['prixUnitaire']);
			$codeLivraison = uniqid();
	        //CREATE NEW Livraison object
	        $livraison = new Livraison(array('libelle' => $libelle, 'designation' => $designation, 
	        'dateLivraison' => $dateLivraison, 'prixUnitaire' => $prixUnitaire, 'quantite' => $quantite
	        ,'idFournisseur' => $idFournisseur, 
	        'idProjet' => $idProjet, 'code' => $codeLivraison));
	        $livraisonManager = new LivraisonManager($pdo);
	        $livraisonManager->add($livraison);
	        $_SESSION['livraison-add-success']='<strong>Opération valide<strong> : La livraison est ajouté avec succès !';
	        header('Location:../livraison.php?codeLivraison='.$codeLivraison);
    	}
	    else{
	    	$_SESSION['livraison-add-error'] = "<strong>Erreur Ajout Livraison</strong> : Vous devez remplir au moins les champs 'Libelle', 'Prix unitaire' et 'Quantité'.";
	        header('Location:../livraison-add.php?idProjet='.$idProjet.'&codeFournisseur='.$codeFournisseur);
			exit;
    	}	
	}
	else{
		header('Location:../projet-list');
	}
    
    