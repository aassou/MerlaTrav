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
    	//les sources
    	$idProjet = 0;
    	$projetManager = new ProjetManager($pdo);
		$clientManager = new ClientManager($pdo);
		$contratManager = new ContratManager($pdo);
		$locauxManager = new LocauxManager($pdo);
		$appartementManager = new AppartementManager($pdo);
		if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
			$contratNumber = $contratManager->getContratsNumberByIdProjet($idProjet);
			if($contratNumber != 0){
				$contratPerPage = 10;
		        $pageNumber = ceil($contratNumber/$contratPerPage);
		        $p = 1;
		        if(isset($_GET['p']) and ($_GET['p']>0 and $_GET['p']<=$pageNumber)){
		            $p = $_GET['p'];
		        }
		        else{
		            $p = 1;
		        }
		        $begin = ($p - 1) * $contratPerPage;
		        $pagination = paginate('contrats-list.php?idProjet='.$idProjet, '&p=', $pageNumber, $p);
				$contrats = $contratManager->getContratsByIdProjet($idProjet, $begin, $contratPerPage);	
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
							Gestion des contrats
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
							<li><a>Liste des contrats</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid add-portfolio">
							<div class="pull-left">
								<a href="projet-list.php" class="btn icn-only green"><i class="m-icon-swapleft m-icon-white"></i> Retour vers Liste des projets</a>
							</div>
						</div>
						<!-- BEGIN Terrain TABLE PORTLET-->
						<?php if(isset($_SESSION['pieces-add-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['pieces-add-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['pieces-add-success']);
						 ?>
						<?php if(isset($_SESSION['pieces-add-error'])){ ?>
							<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['pieces-add-error'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['pieces-add-error']);
						 ?>
						 <?php if(isset($_SESSION['contrat-delete-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-delete-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['contrat-delete-success']);
						 ?>
						 <?php if(isset($_SESSION['contrat-desister-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-desister-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['contrat-desister-success']);
						 ?>
						 <?php if(isset($_SESSION['contrat-activation-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-activation-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['contrat-activation-success']);
						 ?>
						 <?php if(isset($_SESSION['contrat-activation-error'])){ ?>
							<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['contrat-activation-error'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['contrat-activation-error']);
						 ?>
						<div class="portlet">
							<div class="portlet-title">
								<h4>Liste des contrats du projet : <strong><?= $projet->nom() ?></strong></h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th style="width:31%">Client</th>
											<th style="width:15%" class="hidden-phone">Type</th>
											<th style="width:5%">Bien</th>
											<th style="width:15%" class="hidden-phone">Echéance</th>
											<th style="width:8%" class="hidden-phone">Contrat</th>
											<th style="width:8%" class="hidden-phone">Fiche.Cli</th>
											<th style="width:8%">Paiements</th>
											<th style="width:5%" class="hidden-phone">Désister</th>
											<th style="width:5%" class="hidden-phone">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if($contratNumber != 0){
										foreach($contrats as $contrat){
											$bien = "";
											$typeBien = "";
											if($contrat->typeBien()=="appartement"){
												$bien = $appartementManager->getAppartementById($contrat->idBien());
												$typeBien = "Appartement";
											}
											else{
												$bien = $locauxManager->getLocauxById($contrat->idBien());
												$typeBien = "Local.Com";
											}
										?>		
										<tr>
											<td>
												<a href="contrat.php?codeContrat=<?= $contrat->code() ?>">
													<?= $clientManager->getClientById($contrat->idClient())->nom() ?>
												</a>
											</td>
											<td class="hidden-phone"><?= $typeBien ?></td>
											<td><?= $bien->nom() ?></td>
											<td class="hidden-phone"><?= number_format($contrat->echeance(), 2, ',', ' ') ?></td>
											</td>
											<td class="hidden-phone">
												<a class="btn mini blue" href="controller/ContratPrintController.php?idContrat=<?= $contrat->id() ?>" data-toggle="modal" data-id="">
													<i class="m-icon-white icon-print"></i>PDF
												</a>
											</td>
											<td class="hidden-phone">
												<a class="btn mini purple" href="controller/ClientFichePrintController.php?idContrat=<?= $contrat->id() ?>" data-toggle="modal" data-id="">
													<i class="m-icon-white icon-print"></i>PDF
												</a>
											</td>
											<td>
												<a class="btn mini red" href="operations.php?idContrat=<?= $contrat->id() ?>&idProjet=<?= $idProjet ?>" data-toggle="modal" data-id="">
													<i class="m-icon-white icon-folder-open"></i> Voir
												</a>
											</td>
											<td class="hidden-phone">
												<?php if($contrat->status()=="actif"){
												?>
												<a style="color:red" href="#desisterContrat<?= $contrat->id() ?>" data-toggle="modal" data-id="<?= $contrat->id() ?>">
													Désister
												</a>
												<?php 
												}
												else{
												?>	
												<a href="#activerContrat<?= $contrat->id() ?>" data-toggle="modal" data-id="<?= $contrat->id() ?>">
													Activer
												</a>	
												<?php	
												}
												?>
											</td>
											<td class="hidden-phone">
												<?php if($contrat->status()=="actif"){
													$status = "<a class=\"btn mini green\">Actif</a>";	
												}
												else{
													$status = "<a class=\"btn mini black\">Désisté</a>";	
												}
												echo $status;
												?>	
											</td>
										</tr>
										<!-- desistement box begin-->
										<div id="desisterContrat<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Désister le contrat </h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="controller/ContratDesistementController.php" method="post">
													<p>Êtes-vous sûr de vouloir désister le contrat <strong>N°<?= $contrat->id() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
														<input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- desistement box end -->
										<!-- activation box begin-->
										<div id="activerContrat<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Activer le contrat </h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="controller/ContratActivationController.php" method="post">
													<p>Êtes-vous sûr de vouloir activer le contrat <strong>N°<?= $contrat->id() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
														<input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- activation box end -->	
										<!-- delete box begin-->
										<div id="deleteContrat<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer le contrat </h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="controller/ContratDeleteController.php" method="post">
													<p>Êtes-vous sûr de vouloir supprimer le contrat <strong>N°<?= $contrat->id() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
														<input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- delete box end -->	
										<?php
										}//end of loop
										}//end of if
										?>
									</tbody>
									<?php
									if($contratNumber != 0){
										echo $pagination;	
									}
									?>
								</table>
							</div>
						</div>
						<!-- END Terrain TABLE PORTLET-->
					</div>
				</div>
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
			App.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
<?php
}
else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>