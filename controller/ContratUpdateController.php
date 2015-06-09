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
    $codeContrat = $_POST['codeContrat'];
	$idContrat  = $_POST['idContrat'];
	if( !empty($_POST['prixVente']) and !empty($_POST['dateCreation']) ){
		$prixVente = htmlentities($_POST['prixVente']);
		$dateCreation = htmlentities($_POST['dateCreation']);
		$avance = htmlentities($_POST['avance']);
		$modePaiement = htmlentities($_POST['modePaiement']);
		$dureePaiement = htmlentities($_POST['dureePaiement']);
		$echeance = round( ( $prixVente-$avance ) / $dureePaiement );
		$note = htmlentities($_POST['note']);
		//create classes managers
		$locauxManager = new LocauxManager($pdo);
		$contratManager = new ContratManager($pdo);
		$appartementManager = new AppartementManager($pdo);
		//create classes
		//this contrat object is used to test the appartement/locaux object 
		$contrat = $contratManager->getContratById($idContrat);
		$newContrat = new Contrat(array('id' => $idContrat, 'dateCreation' => $dateCreation, 
		'prixVente' => $prixVente, 'avance' => $avance, 'modePaiement' => $modePaiement, 
		'dureePaiement' => $dureePaiement,'echeance' => $echeance, 'note' => $note));
		//begin processing
		$contratManager->update($newContrat);
		//update notes client
		$notesClientManager = new NotesClientManager($pdo);
		$notesClient = new NotesClient(array('note' => $note, 'created' => date('Y-m-d'), 
		'idProjet' => $contrat->idProjet(), 'codeContrat' => $contrat->code()));
		$notesClientManager->add($notesClient);
		//test if the typeBien radio box is checked
		if( isset($_POST['typeBien']) ){
			$typeBien = $_POST['typeBien'];
			$idBien = $_POST['bien'];
			//change status of the old contrat Bien from reservé to non reservé
			if( $contrat->typeBien()=="appartement" ){
				$appartementManager->changeStatus($contrat->idBien(), "Non");
			}
			else if( $contrat->typeBien()=="localCommercial" ){
				$locauxManager->changeStatus($contrat->idBien(), "Non");
			}
			//change status of the new contrat Bien from non reservé to reservé
			if( $typeBien=="appartement" ){
				$contratManager->changerBien($idContrat, $idBien, $typeBien);
				$appartementManager->changeStatus($idBien, "Oui");
			}
			else if( $typeBien=="localCommercial" ){
				$contratManager->changerBien($idContrat, $idBien, $typeBien);
				$locauxManager->changeStatus($idBien, "Oui");
			}
		}
		$_SESSION['contrat-update-success'] = "<strong>Opération valide : </strong>Les informations du contrat sont modifiées avec succès.";
		header('Location:../contrat.php?codeContrat='.$codeContrat);
	}
	else{
        $_SESSION['contrat-update-error'] = "<strong>Erreur Modification Contrat : </strong>Vous devez remplir au moins les champs 'Date de création' et 'Prix de vente'.";
		header('Location:../contrat.php?codeContrat='.$codeContrat);
	}
