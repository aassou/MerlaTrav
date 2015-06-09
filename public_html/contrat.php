<?php
	//classes loading begin
    function classLoad ($myClass) {
        if(file_exists('model/'.$myClass.'.php')){
            include('model/'.$myClass.'.php');
        }
        elseif(file_exists('controller/'.$myClass.'.php')){
            include('controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('config.php');  
	include('lib/pagination.php');
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	$idProjet = 0;
    	$projetManager = new ProjetManager($pdo);
		$clientManager = new ClientManager($pdo);
		$contratManager = new ContratManager($pdo);
		$operationManager = new OperationManager($pdo);
		if(isset($_GET['codeContrat']) and (bool)$contratManager->getCodeContrat($_GET['codeContrat']) ){
			$codeContrat = $_GET['codeContrat'];
			$contrat = $contratManager->getContratByCode($codeContrat);
			$projet = $projetManager->getProjetById($contrat->idProjet());
			$client = $clientManager->getClientById($contrat->idClient());
			$biens = "";
			if($contrat->typeBien()=="appartement"){
				$appartementManager = new AppartementManager($pdo);
				$biens = $appartementManager->getAppartementById($contrat->idBien());
			}
			else if($contrat->typeBien()=="localCommercial"){
				$locauxManager = new LocauxManager($pdo);
				$biens = $locauxManager->getLocauxById($contrat->idBien());
			}
		}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>MerlaTravERP - Management Application</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/metro.css" rel="stylesheet" />
	<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style_responsive.css" rel="stylesheet" />
	<link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
	<link href="assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
	<link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<?php include("include/top-menu.php"); ?>	
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
		<!-- BEGIN SIDEBAR -->
		<?php include("include/sidebar.php"); ?>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->			
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
						<h3 class="page-title">
							Gestion des Clients et Contrats
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a>Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<i class="icon-briefcase"></i>
								<a>Gestion des projets</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a>Gestion des clients/contrats</a></li>
							<?php 
								$clientManager = new ClientManager($pdo);
							?>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<?php if(isset($_GET['codeContrat']) and (bool)$contratManager->getCodeContrat($_GET['codeContrat']) ){
						?>
						<div class="row-fluid add-portfolio">
							<div class="pull-left">
								<a href="contrats-list.php?idProjet=<?= $projet->id() ?>" class="btn icn-only green"><i class="m-icon-swapleft m-icon-white"></i> Retour vers Liste des contrats du projet : <strong><?= $projet->nom() ?></strong></a>
							</div>
							<div class="pull-right">
								<a href="projet-list.php" class="btn icn-only green">Aller vers Liste des projets <i class="m-icon-swapright m-icon-white"></i></a>
							</div>
						</div>
	                     <?php if(isset($_SESSION['contrat-add-success'])){ ?>
	                     	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-add-success'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['contrat-add-success']);
	                     ?>
	                     <?php if(isset($_SESSION['client-update-success'])){ ?>
	                     	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['client-update-success'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['client-update-success']);
	                     ?>
	                     <?php if(isset($_SESSION['client-update-error'])){ ?>
	                     	<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['client-update-error'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['client-update-error']);
	                     ?>
	                     <?php if(isset($_SESSION['contrat-update-success'])){ ?>
	                     	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-update-success'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['contrat-update-success']);
	                     ?>
	                      <?php if(isset($_SESSION['contrat-update-error'])){ ?>
	                     	<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-update-error'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['contrat-update-error']);
	                     ?>
	                     <?php
	                     //progress bar processing
	                     $statistiquesResult = ceil((( $contrat->avance()+$operationManager->sommeOperations($contrat->id()) )/$contrat->prixVente())*100);
						 $statusBar = "";
						 if( $statistiquesResult>0 and $statistiquesResult<25 ){
						 	$statusBar = "progress-danger";
						 }
						 else if( $statistiquesResult>=25 and $statistiquesResult<50 ){
						 	$statusBar = "progress-warning";
						 }
						 else if( $statistiquesResult>=50 and $statistiquesResult<75 ){
						 	$statusBar = "progress-success";
						 }
	                     ?>
	                    <h3>Résumé du Contrat</h3>
	                    <hr>
	                    <h4>Avancement du contrat<h4>
	                    <div class="progress <?= $statusBar ?>">
    						<div class="bar" style="width: <?= $statistiquesResult ?>%;"><?= $statistiquesResult ?>%</div>
						</div>
	                    <hr>
                       <div class="span5">
						<div class="portlet sale-summary">
							<div class="portlet-title">
								<h4>Informations du client</h4>
								<a href="#updateClient<?= $client->id() ?>" class="pull-right btn red hidden-phone" data-toggle="modal" data-id="<?= $client->id(); ?>">
									Modifier <i class="icon-refresh icon-white"></i>
								</a>
								<br><br>	
							</div>
							<ul class="unstyled">
								<li>
									<span class="sale-info">Client</span> 
									<span class="sale-num"><?= $client->nom() ?></span>
								</li>
								<li>
									<span class="sale-info">CIN</span> 
									<span class="sale-num"><?= $client->cin() ?></span>
								</li>
								<li>
									<span class="sale-info">Téléphone 1</span> 
									<span class="sale-num"><?= $client->telephone1() ?></span>
								</li>
								<li>
									<span class="sale-info">Téléphone 2</span> 
									<span class="sale-num"><?= $client->telephone2() ?></span>
								</li>
								<li>
									<span class="sale-info">Adresse</span> 
									<span class="sale-num"><?= $client->adresse() ?></span>
								</li>
							</ul>
							<a href="controller/ClientFichePrintController.php?idContrat=<?= $contrat->id() ?>" class="btn big purple">
									<i class="icon-print"></i> Fiche Client
								</a>
						</div>
					 </div>
					 <div class="span6">
						<div class="portlet sale-summary">
							<div class="portlet-title">
								<h4>Informations du contrat</h4>
								<a href="#updateContrat<?= $contrat->id() ?>" class="pull-right btn red hidden-phone" data-toggle="modal" data-id="<?= $contrat->id(); ?>">
									Modifier <i class="icon-refresh icon-white"></i>
								</a>
								<br><br>
							</div>
							<ul class="unstyled">
								<li>
									<span class="sale-info">Type</span> 
									<span class="sale-num">
									<?php 
										if($contrat->typeBien()=="localCommercial"){
											echo "Local commercial"; 
										} 
										else{
											echo "Appartement";
										} 
									?>
									</span>
								</li>
								<li>
									<span class="sale-info">Nom du Bien</span> 
									<span class="sale-num"><?= $biens->nom() ?></span>
								</li>
								<li>
									<span class="sale-info">Prix de Vente</span> 
									<span class="sale-num"><?= $contrat->prixVente() ?>&nbsp;DH</span>
								</li>
								<li>
									<?php
									if($contrat->avance()!=0 or $contrat->avance()!='NULL' ){
									?>
									<span class="sale-info">Avance</span> 
									<span class="sale-num"><?= $contrat->avance() ?>&nbsp;DH</span>
									<?php
									}
									?>
								</li>
								<li>
									<span class="sale-info">Echeance</span> 
									<span class="sale-num"><?= $contrat->echeance() ?></span>
								</li>
							</ul>
							<a href="controller/ContratPrintController.php?idContrat=<?= $contrat->id() ?>" class="btn big blue">
									<i class="icon-print"></i> Contrat Client
								</a>
							<a href="controller/QuittanceAvancePrintController.php?idContrat=<?= $contrat->id() ?>" class="btn big black">
									<i class="icon-print"></i> Quittance Avance
								</a>
						</div>
					 </div>
					 </div>
				   </div>
				</div>
				<!-- updateClient box begin-->
				<div id="updateClient<?= $client->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h3>Modifier les informations du client </h3>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" action="controller/ClientUpdateController.php" method="post">
							<p>Êtes-vous sûr de vouloir modifier les infos du client <strong><?= $client->nom() ?></strong> ?</p>
							<div class="control-group">
								<label class="control-label">Nom</label>
								<div class="controls">
									<input type="text" name="nom" value="<?= $client->nom() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">CIN</label>
								<div class="controls">
									<input type="text" name="cin" value="<?= $client->cin() ?>" />
								</div>
							</div>	
							<div class="control-group">
								<label class="control-label">Adresse</label>
								<div class="controls">
									<input type="text" name="adresse" value="<?= $client->adresse() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Tél.Fix</label>
								<div class="controls">
									<input type="text" name="telephone1" value="<?= $client->telephone1() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Tél.Mobil</label>
								<div class="controls">
									<input type="text" name="telephone2" value="<?= $client->telephone2() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Email</label>
								<div class="controls">
									<input type="text" name="email" value="<?= $client->email() ?>" />
								</div>	
							</div>
							<div class="control-group">
								<input type="hidden" name="idClient" value="<?= $client->id() ?>" />
								<input type="hidden" name="codeContrat" value="<?= $contrat->code() ?>" />
								<div class="controls">	
									<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
									<button type="submit" class="btn red" aria-hidden="true">Oui</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- updateClient box end -->
				<!-- updateContrat box begin-->
				<div id="updateContrat<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h3>Modifier les informations du contrat </h3>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" action="controller/ContratUpdateController.php" method="post">
							<p>Êtes-vous sûr de vouloir modifier le contrat <strong>N°<?= $contrat->id() ?></strong>  ?</p>
							<div class="control-group">
								<label class="control-label">Date Création</label>
								<div class="controls">
									<input type="text" name="dateCreation" value="<?= $contrat->dateCreation() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Prix Vente</label>
								<div class="controls">
									<input type="text" name="prixVente" value="<?= $contrat->prixVente() ?>" />
								</div>
							</div>	
							<div class="control-group">
								<label class="control-label">avance</label>
								<div class="controls">
									<input type="text" name="avance" value="<?= $contrat->avance() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Mode de paiement</label>
								<div class="controls">
									<select name="modePaiement">
										<option value="<?= $contrat->modePaiement() ?>"><?= $contrat->modePaiement() ?></option>
										<option disabled="disabled">-----------</option>
										<option value="Espèces">Espèces</option>
										<option value="Chèque">Chèque</option>
										<option value="Versement">Versement</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Durée de paiement</label>
								<div class="controls">
									<input type="text" name="dureePaiement" value="<?= $contrat->dureePaiement() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Echéance</label>
								<div class="controls">
									<input type="text" name="echance" value="<?= $contrat->echeance() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Note Client</label>
								<div class="controls">
									<textarea name="note"><?= $contrat->note() ?></textarea>
								</div>
							</div>
							<div class="control-group">
                             	<div class="alert alert-error">
									<strong>Remarque</strong> : Ne toucher à cette zone sauf si vous voulez changer le bien.		
								</div>
                            	<label class="control-label">Changer Type du bien ?</label>
                            	<div class="controls">
                                	<label class="radio">
                                 	<input type="radio" class="typeBien" name="typeBien" value="localCommercial" />
                                 	Local commercial
                                	</label>
                                	<label class="radio">
                                 	<input type="radio" class="typeBien" name="typeBien" value="appartement" />
                                 	Appartement
                                	</label>
                             	</div>
                          	</div>
                          	<div class="control-group hidenBlock">
                          		<label class="control-label" for="" id="nomBienLabel"></label>
                             	<div class="controls">
                             		<select class="m-wrap" name="bien" id="bien">
                             		</select>
                            	</div>
                          	</div>
							<div class="control-group">
								<input type="hidden" name="codeContrat" value="<?= $contrat->code() ?>" />
								<input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
								<div class="controls">	
									<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
									<button type="submit" class="btn red" aria-hidden="true">Oui</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- updateContrat box end -->		
				<?php 
				}
				else{
				?>
				<div class="alert alert-error">
					<button class="close" data-dismiss="alert"></button>
					<strong>Erreur système : </strong>Ce projet n'existe pas sur votre système. Pour plus d'informations consulter votre administrateur.		
				</div>
				<?php
				}
				?>
				<!-- END PAGE CONTENT -->
			</div>
			<!-- END PAGE CONTAINER-->
		</div>
		<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
		2015 &copy; MerlaTravERP. Management Application.
		<div class="span pull-right">
			<span class="go-top"><i class="icon-angle-up"></i></span>
		</div>
	</div>
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS -->
	<!-- Load javascripts at bottom, this will reduce page load time -->
	<script src="assets/js/jquery-1.8.3.min.js"></script>	
	<script src="assets/breakpoints/breakpoints.js"></script>	
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>		
	<script src="assets/js/jquery.blockui.js"></script>
	<script src="assets/js/jquery.cookie.js"></script>
	<script src="assets/fancybox/source/jquery.fancybox.pack.js"></script>
	<script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>	
	<script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="assets/js/excanvas.js"></script>
	<script src="assets/js/respond.js"></script>
	<![endif]-->	
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<script src="assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			//App.setPage("table_editable");
			$('.hidenBlock').hide();
			App.init();
		});
	</script>
	<script>
		$(document).ready(function() {
			$('.typeBien').change(function(){
				$('.hidenBlock').show();
				var typeBien = $(this).val();
				var idProjet = <?= $projet->id() ?>;
				var data = 'typeBien='+typeBien+'&idProjet='+idProjet;
				$.ajax({
					type: "POST",
					url: "types-biens.php",
					data: data,
					cache: false,
					success: function(html){
						$('#bien').html(html);
						if(typeBien=="appartement"){
							$('#nomBienLabel').text("Appartements");	
						}
						else{
							$('#nomBienLabel').text("Locaux commerciaux");
						}
					}
				});
			});
		});
	</script>
</body>
<!-- END BODY -->
</html>
<?php
}
else if(isset($_SESSION['user']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}

?>