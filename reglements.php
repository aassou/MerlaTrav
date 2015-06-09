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
    	//classManagers
    	$projetManager = new ProjetManager($pdo);
		$fournisseurManager = new FournisseurManager($pdo);
		$livraisonManager = new livraisonManager($pdo);
		$reglementsFournisseurManager = new ReglementFournisseurManager($pdo);
		//classes and vars
		$projets = $projetManager->getProjets();
		$fournisseurs = $fournisseurManager->getFournisseurs();
		$projet = $projetManager->getProjets();
		$reglementNumber = 0;
		$totalReglement = 0;
		$totalreglement = 0;
		$titrereglement ="Liste de toutes les reglements";
		if( isset($_POST['idFournisseur']) and 
		$fournisseurManager->getOneFournisseurBySearch($_POST['idFournisseur']>=1)){
			$fournisseur = $fournisseurManager->getOneFournisseurBySearch(htmlentities($_POST['idFournisseur']));
			$reglementNumber = $reglementsFournisseurManager->getReglementsNumberByIdFournisseurOnly($fournisseur);
			if($reglementNumber != 0){
				$reglementPerPage = 10;
		        $pageNumber = ceil($reglementNumber/$reglementPerPage);
		        $p = 1;
		        if(isset($_GET['p']) and ($_GET['p']>0 and $_GET['p']<=$pageNumber)){
		            $p = $_GET['p'];
		        }
		        else{
		            $p = 1;
		        }
		        $begin = ($p - 1) * $reglementPerPage;
		        $pagination = paginate('reglements.php', '?p=', $pageNumber, $p);
				$reglements = $reglementsFournisseurManager->getReglementsByIdFournisseurByLimits($fournisseur, $begin, $reglementPerPage);
				$titrereglement ="Liste des reglements du fournisseur <strong>".$fournisseurManager->getFournisseurById($fournisseur)->nom()."</strong>";	
				$totalReglement = $reglementsFournisseurManager->sommeReglementFournisseursByIdFournisseur($fournisseur);
				$totalreglement = $reglementsFournisseurManager->getTotalReglementsIdFournisseur($fournisseur);
			}
		}
		else {
			$reglementNumber = $reglementsFournisseurManager->getReglementNumber();
			if($reglementNumber != 0){
				$reglementPerPage = 10;
		        $pageNumber = ceil($reglementNumber/$reglementPerPage);
		        $p = 1;
		        if(isset($_GET['p']) and ($_GET['p']>0 and $_GET['p']<=$pageNumber)){
		            $p = $_GET['p'];
		        }
		        else{
		            $p = 1;
		        }
		        $begin = ($p - 1) * $reglementPerPage;
		        $pagination = paginate('reglements.php', '?p=', $pageNumber, $p);
				$reglements = $reglementsFournisseurManager->getReglementsByLimit($begin, $reglementPerPage);
				$titrereglement ="Liste de toutes les reglements";
				$totalReglement = $reglementsFournisseurManager->getTotalReglement();
				$totalreglement = $reglementsFournisseurManager->getTotalReglement();	
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
	<link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
	<link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
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
							Gestion des reglements
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a>Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<i class="icon-truck"></i>
								<a>Gestion des reglements</a>
								<i class="icon-angle-right"></i>
							</li>
							<li><a>Liste des reglements</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid add-portfolio">
							<div class="pull-left">
								<!--a href="reglement-add.php" class="btn icn-only blue"-->
								<a href="#addFournisseur" data-toggle="modal" class="btn blue">
									Ajouter Nouveau Fournisseur <i class="icon-plus-sign "></i>
								</a>
							</div>
							<div class="pull-right">
								<a href="#addReglement" data-toggle="modal" class="btn green">
									Ajouter Nouvelle reglement <i class="icon-plus-sign "></i>
								</a>
							</div>
						</div>
						<!-- addFournisseur box begin-->
						<div id="addFournisseur" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
								<h3>Ajouter un nouveau fournisseur </h3>
							</div>
							<div class="modal-body">
								<form class="form-horizontal" action="controller/FournisseurAddController.php?p=1" method="post">
									<div class="control-group">
										<label class="control-label">Nom</label>
										<div class="controls">
											<input type="text" name="nom" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Adresse</label>
										<div class="controls">
											<input type="text" name="adresse" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Tél.1</label>
										<div class="controls">
											<input type="text" name="telephone1" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Tél.2</label>
										<div class="controls">
											<input type="text" name="telephone2" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Fax</label>
										<div class="controls">
											<input type="text" name="fax" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Email</label>
										<div class="controls">
											<input type="text" name="email" value="" />
										</div>	
									</div>
									<div class="control-group">
										<div class="controls">	
											<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
											<button type="submit" class="btn red" aria-hidden="true">Oui</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!-- addFournisseur box end -->
						<!-- addreglement box begin-->
						<div id="addReglement" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
								<h3>Ajouter une nouvelle reglement </h3>
							</div>
							<div class="modal-body">
								<form class="form-horizontal" action="controller/ReglementAddController.php?p=99" method="post">
									<div class="control-group">
										<label class="control-label">Fournisseur</label>
										<div class="controls">
											<select name="idFournisseur">
                                            	<?php foreach($fournisseurs as $fournisseur){ ?>
                                            	<option value="<?= $fournisseur->id() ?>"><?= $fournisseur->nom() ?></option>
                                            	<?php } ?>
                                            </select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Projet</label>
										<div class="controls">
											<select name="idProjet">
                                            	<?php foreach($projets as $projet){ ?>
                                            	<option value="<?= $projet->id() ?>"><?= $projet->nom() ?></option>
                                            	<?php } ?>
                                            </select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Date reglement</label>
										<div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
		                                    <input name="dateReglement" id="dateReglement" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
		                                    <span class="add-on"><i class="icon-calendar"></i></span>
		                                 </div>
									</div>
									<div class="control-group">
										<label class="control-label">Libelle</label>
										<div class="controls">
											<input type="text" name="libelle" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Désignation</label>
										<div class="controls">
											<input type="text" name="designation" value="" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Prix Unitaire</label>
										<div class="controls">
											<input type="text" name="prixUnitaire" value="" />
										</div>	
									</div>
									<div class="control-group">
										<label class="control-label">Quantité</label>
										<div class="controls">
											<input type="text" name="quantite" value="" />
										</div>	
									</div>
									<div class="control-group">
										<div class="controls">	
											<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
											<button type="submit" class="btn red" aria-hidden="true">Oui</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!-- addreglement box end -->
						<div class="row-fluid">
							<form action="" method="post">
							    <div class="input-box autocomplet_container">
									<input class="m-wrap" name="recherche" id="nomFournisseur" type="text" onkeyup="autocompletFournisseur()" placeholder="Tapez votre recherche...">
										<ul id="fournisseurList"></ul>
									</input>
									<input name="idFournisseur" id="idFournisseur" type="hidden" />
									<button type="submit" class="btn red"><i class="icon-search"></i></button>
							    </div>
							</form>
						</div>
						<!-- BEGIN Terrain TABLE PORTLET-->
						<?php if(isset($_SESSION['fournisseur-add-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['fournisseur-add-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['fournisseur-add-success']);
						 ?>
						 <?php if(isset($_SESSION['fournisseur-add-error'])){ ?>
							<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['fournisseur-add-error'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['fournisseur-add-error']);
						 ?>
						 <?php if(isset($_SESSION['reglement-add-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['reglement-add-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['reglement-add-success']);
						 ?>
						 <?php if(isset($_SESSION['reglement-add-error'])){ ?>
							<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['reglement-add-error'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['reglement-add-error']);
						 ?>
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
						 <?php if(isset($_SESSION['reglement-delete-success'])){ ?>
							<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['reglement-delete-success'] ?>		
							</div>
						 <?php } 
							unset($_SESSION['reglement-delete-success']);
						 ?>
						<div class="portlet">
							<div class="portlet-title">
								<h4><?= $titrereglement ?></h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th>Fournisseur</th>
											<th class="hidden-phone">Date reglement</th>
											<th class="hidden-phone">Libelle</th>
											<th>Designat°</th>
											<th class="hidden-phone">Quantité</th>
											<th class="hidden-phone">Prix.Un</th>
											<th>Total</th>
											<!--th class="hidden-phone">Docs</th>
											<th class="hidden-phone">Docs</th>
											<th class="hidden-phone">Suppri</th-->
										</tr>
									</thead>
									<tbody>
										<?php
										if($reglementNumber != 0){
										foreach($reglements as $reglement){
										?>		
										<tr>
											<td>
												<div class="btn-group">
												    <a style="width: 100px" class="btn mini dropdown-toggle" href="#" data-toggle="dropdown">
												    	<?= $fournisseurManager->getFournisseurById($reglement->idFournisseur())->nom() ?> 
												        <i class="icon-angle-down"></i>
												    </a>
												    <ul class="dropdown-menu">
												        <li>
												        	<a href="reglement.php?codereglement=<?= $reglement->code() ?>">
												        		Fiche descriptif
												        	</a>
												        	<a href="#addPieces<?= $reglement->id() ?>" data-toggle="modal" data-id="<?= $reglement->id() ?>">
																Ajouter Document
															</a>
															<a href="reglement-pieces.php?idreglement=<?= $reglement->id() ?>" data-toggle="modal" data-id="<?= $reglement->id() ?>">
																Consulter les documents
															</a>																
												        	<!--a href="#updatereglement<?= $reglement->id();?>" data-toggle="modal" data-id="<?= $reglement->id(); ?>">
																Modifier
															</a-->
															<a href="#deletereglement<?= $reglement->id() ?>" data-toggle="modal" data-id="<?= $reglement->id() ?>">
																Supprimer
															</a>
												        </li>
												    </ul>
												</div>
											</td>
											<td class="hidden-phone"><?= date('d/m/Y', strtotime($reglement->datereglement())) ?></td>
											<td class="hidden-phone"><?= $reglement->libelle() ?></td>
											<td><?= $reglement->designation() ?></td>
											<td class="hidden-phone"><?= $reglement->quantite() ?></td>
											<td class="hidden-phone"><?= number_format($reglement->prixUnitaire(), 2, ',', ' ') ?></td>
											<td><a><?= number_format($reglement->prixUnitaire()*$reglement->quantite(), 2, ',', ' ') ?></a></td>
											<!--td class="hidden-phone">
												<a href="#addPieces<?= $reglement->id() ?>" class="btn mini purple" data-toggle="modal" data-id="<?= $reglement->id() ?>"> 
													Ajouter
												</a>
											</td>
											<td class="hidden-phone">
												<a href="reglement-pieces.php?idProjet=<?= $idProjet ?>&idreglement=<?= $reglement->id() ?>" class="btn mini yellow" data-toggle="modal" data-id="<?= $reglement->id() ?>">
													Gérer
												</a>
											</td>
											<td class="hidden-phone">
												<a href="#deletereglement<?= $reglement->id() ?>" data-toggle="modal" data-id="<?= $reglement->id() ?>">
													Supprimer
												</a>
											</td-->
										</tr>
										<!-- add file box begin-->
										<div id="addPieces<?= $reglement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Ajouter des pièces pour cette reglement</h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal" action="controller/reglementPiecesAddController.php" method="post" enctype="multipart/form-data">
													<p>Êtes-vous sûr de vouloir ajouter des pièces pour cette reglement ?</p>
													<div class="control-group">
														<label class="right-label">Nom Pièce</label>
														<input type="text" name="nom" />
														<label class="right-label">Lien</label>
														<input type="file" name="url" />
														<input type="hidden" name="idreglement" value="<?= $reglement->id() ?>" />
														<label class="right-label"></label>
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- add files box end -->	
										<!-- delete box begin-->
										<div id="deletereglement<?= $reglement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer la reglement </h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="controller/reglementDeleteController.php?p=1" method="post">
													<p>Êtes-vous sûr de vouloir supprimer la reglement <strong>N°<?= $reglement->id() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idreglement" value="<?= $reglement->id() ?>" />
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
									if($reglementNumber != 0){
										echo $pagination;	
									}
									?>
								</table>
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th><strong>Total reglements</strong></th>
											<td><strong><a><?= number_format($totalreglement, 2, ',', ' ') ?></a>&nbsp;DH</strong></td>
										</tr>
										<tr>
											<th><strong>Total Réglements</strong></th>
											<td><strong><a><?= number_format($totalReglement, 2, ',', ' ') ?></a>&nbsp;DH</strong></td>
										</tr>		
									</thead>
								</table>	
							</div>
						</div>
						<!-- END Terrain TABLE PORTLET-->
					</div>
				</div>
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
	<script type="text/javascript" src="script.js"></script>		
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