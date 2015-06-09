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
    if( isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin" ){
        //classes managers	
        $projetManager = new ProjetManager($pdo);
		$fournisseurManager = new FournisseurManager($pdo);
		$livraisonManager = new LivraisonManager($pdo);
		$reglementFournisseurManager = new ReglementFournisseurManager($pdo);
		if( isset($_GET['idProjet']) and ( $_GET['idProjet']>0 and $_GET['idProjet']<=$projetManager->getLastId() ) ){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
			$fournisseurs = $livraisonManager->getFournisseursByIdProjet($idProjet);
			$idFounisseurs = $reglementFournisseurManager->getIdFournisseurs($idProjet);
			//test the user choice 
			if(isset($_GET['fournisseur']) and strcmp($_GET['fournisseur'], "Tous")!=0){
				$choice = htmlentities($_GET['fournisseur']);
				$reglements = $reglementFournisseurManager->getReglementFournisseur($idProjet, $choice);
				$total = $reglementFournisseurManager->getTotalReglementFournisseur($idProjet, $choice);
			}
			else{
				$reglements = $reglementFournisseurManager->getReglementTous($idProjet);
				$total = $reglementFournisseurManager->getTotalReglementTous($idProjet);
			}
		}	

ob_start();
?>
<style type="text/css">
	p, h1{
		text-align: center;
		text-decoration: underline;
	}
	table {
		    border-collapse: collapse;
		    width:100%;
		}
		
		table, th, td {
		    border: 1px solid black;
		}
		td, th{
			padding : 5px;
		}
		
		th{
			background-color: grey;
		}
</style>
<page backtop="15mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <img src="../assets/img/logo_company.png" style="width: 110px" />
    <br><br><br><br><br><br><br>
    <h1>Bilan des Réglements des Fournisseurs</h1>
    <p>Imprimé le <?= date('d-m-Y') ?> | <?= date('h:i') ?> </p>
    <br><br><br><br><br><br><br>
    <table>
		<tr>
			<th style="width: 15%">N°Réglement</th>
			<th style="width: 25%">Montant</th>
			<th style="width: 25%">Date réglement</th>
			<th style="width: 35%">Fournisseur</th>
		</tr>
		<?php
		foreach($reglements as $reglement){
		?>		
		<tr>
			<td><a><?= $reglement->id() ?></a></td>
			<td><?= $reglement->montant() ?></td>
			<td><?= $reglement->dateReglement() ?></td>
			<td><?= $fournisseurManager->getFournisseurById($reglement->idFournisseur())->nom() ?></td>
		</tr>	
		<?php
		}//end of loop
		?>
	</table>
    <br><br>
    <h2>Total = <?= $total ?></h2> 
    <br><br>
    <page_footer>
    <hr/>
    <p style="text-align: center;font-size: 9pt;">STE MERLA TRAV SARL : Au capital de 100 000,00 DH – siège social QT 313 Old Brahim Mezanine B1, Nador. 
    	<br>Tèl 0536601818 / 0661668860 IF : 40451179   RC : 10999  Patente 56126681</p>
    </page_footer>
</page>    
<?php
    $content = ob_get_clean();
    
    require('../lib/html2pdf/html2pdf.class.php');
    try{
        $pdf = new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->writeHTML($content);
		$fileName = "ReglementFournisseur-".date('Ymdhi').'.pdf';
       	$pdf->Output($fileName, 'D');
    }
    catch(HTML2PDF_exception $e){
        die($e->getMessage());
    }
}
else{
    header("Location:index.php");
}
?>
