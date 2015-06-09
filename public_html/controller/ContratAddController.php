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
	$codeClient = $_POST['codeClient'];
    if( !empty($_POST['idProjet']) and !empty($_POST['codeClient'])){	
    	if( !empty($_POST['typeBien']) ){
    		if( !empty($_POST['prixNegocie']) ){
    			$prixNegocie = htmlentities($_POST['prixNegocie']);
    		}
			else{
			$_SESSION['contrat-add-error'] = "<strong>Erreur Création Contrat : </strong>Vous devez remplir le 'Prix négocié'.";	
			header('Location:../contrats-add.php?idProjet='.$idProjet.'&codeClient='.$codeClient);
			exit;
			}
    		$typeBien = htmlentities($_POST['typeBien']);
			$dateCreation = htmlentities($_POST['dateCreation']);
			$idBien = htmlentities($_POST['bien']);
			$avance = htmlentities($_POST['avance']);
			$modePaiement = htmlentities($_POST['modePaiement']);
			$dureePaiement = htmlentities($_POST['dureePaiement']);
			$echeance = htmlentities($_POST['echeance']);
			$note = htmlentities($_POST['note']);
			$idClient = htmlentities($_POST['idClient']);
			$codeContrat = uniqid();
			$contratManager = new ContratManager($pdo);
			$contrat = new Contrat(array('dateCreation' => $dateCreation, 'prixVente' => $prixNegocie, 
			'avance' => $avance, 'modePaiement' => $modePaiement, 'dureePaiement' => $dureePaiement, 
			'echeance' => $echeance, 'note' => $note, 'idClient' => $idClient, 'idProjet' => $idProjet, 
			'idBien' => $idBien, 'typeBien' => $typeBien, 'code' => $codeContrat));
			$contratManager->add($contrat);
			if($typeBien=="appartement"){
				$appartementManager = new AppartementManager($pdo);
				$appartementManager->changeStatus($idBien, "Oui");
			}
			else if($typeBien=="localCommercial"){
				$locauxManager = new LocauxManager($pdo);
				$locauxManager->changeStatus($idBien, "Oui");
			}
			//add note client into db and show it in the dashboard
			$notesClientManager = new NotesClientManager($pdo);
			$notesClient = new NotesClient(array('note' => $note, 'created' => date('Y-m-d'), 
			'idProjet' => $idProjet, 'codeContrat' => $codeContrat));
			$notesClientManager->add($notesClient);
			header('Location:../contrat.php?codeContrat='.$codeContrat);
    	}
		else{
			$_SESSION['contrat-add-error'] = "<strong>Erreur Création Contrat : </strong>Vous devez choisir un 'Type de bien'.";	
			header('Location:../contrats-add.php?idProjet='.$idProjet.'&codeClient='.$codeClient);
			exit;
		}
    }
    else{
        $_SESSION['contrat-add-error'] = "<strong>Erreur Création Contrat : </strong>Vous devez remplir au moins le champ 'Nom'.";
		header('Location:../contrats-add.php?idProjet='.$idProjet.'&codeClient='.$codeClient);
    }
	
    