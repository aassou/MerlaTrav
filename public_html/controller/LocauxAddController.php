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
		$mezzanine = htmlentities($_POST['mezzanine']);
        $superficie = 0;
        $prix = 0;
        if(filter_var($_POST['superficie'], FILTER_VALIDATE_FLOAT)==true){
            $superficie = htmlentities($_POST['superficie']);
        }
        else {
            $_SESSION['locaux-add-error']="<strong>Erreur Ajout Local commercial</strong> : La valeur du champ 'Superficie' est incorrecte !";
            header('Location:../locaux.php?idProjet='.$idProjet);
            exit;
        }
        if(filter_var($_POST['prix'], FILTER_VALIDATE_FLOAT)==true){
            $prix = htmlentities($_POST['prix']);
        }
        else {
            $_SESSION['locaux-add-error']="<strong>Erreur Ajout Local commercial</strong> : La valeur du champ 'Prix' est incorrecte !";
            header('Location:../locaux.php?idProjet='.$idProjet);
            exit;
        }
        
        $locaux = new Locaux(array('nom' => $nom, 'prix' => $prix,'superficie' => $superficie, 
        'facade' =>$facade, 'status' => $status, 'mezzanine' => $mezzanine,'idProjet' => $idProjet));
        $locauxManager = new LocauxManager($pdo);
        $locauxManager->add($locaux);
        $_SESSION['locaux-add-success']="<strong>Opération valide : </strong>Le Local commercial est ajouté avec succès !";
    }
    else{
        $_SESSION['locaux-add-error'] = "<strong>Erreur Ajout Local commercial : </strong>Vous devez remplir au moins le champ 'Code'.";
    }
	header('Location:../locaux.php?idProjet='.$idProjet);
    