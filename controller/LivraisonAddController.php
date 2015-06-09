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
	$idFournisseur = htmlentities($_POST['idFournisseur']);
	if( !empty($_POST['idProjet']) and !empty($_POST['idFournisseur']) ){
		if( !empty($_POST['numeroBL']) ){
			$numeroBL = htmlentities($_POST['numeroBL']);
	        $dateLivraison = htmlentities($_POST['dateLivraison']);
			$codeLivraison = uniqid().date('YmdHis');
	        //CREATE NEW Livraison object
	        $livraison = new Livraison(array('libelle' => $numeroBL, 'dateLivraison' => $dateLivraison, 
	        'idFournisseur' => $idFournisseur, 'idProjet' => $idProjet, 'code' => $codeLivraison));
	        $livraisonManager = new LivraisonManager($pdo);
	        $livraisonManager->add($livraison);
	        $_SESSION['livraison-add-success']='<strong>Opération valide</strong> : La livraison est ajouté avec succès !';
			$redirectLink = 'Location:../livraison-detail.php?codeLivraison='.$codeLivraison;
			/*if( isset($_GET['p']) and $_GET['p']==99 ){
				$redirectLink = 'Location:../livraisons.php';
			}*/
	        header($redirectLink);
			exit;
    	}
	    else{
	    	$_SESSION['livraison-add-error'] = "<strong>Erreur Ajout Livraison</strong> : Vous devez remplir le champ 'Numéro BL'.";
			$redirectLink = 'Location:../livraison-add.php?idProjet='.$idProjet.'&idFournisseur='.$idFournisseur;
			/*if( isset($_GET['p']) and $_GET['p']==99 ){
				$redirectLink = 'Location:../livraisons.php';
			}*/
	        header($redirectLink);
			exit;
    	}	
	}
	else{
		header('Location:../projet-list');
	}
    
    