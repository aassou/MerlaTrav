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
		if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
			$appartementManager = new AppartementManager($pdo);
			$appartements = "";
			//test the appartement object number: if exists get appartement else do nothing
			$appartementNumber = $appartementManager->getAppartementNumberByIdProjet($idProjet);
			if($appartementNumber != 0){
				$appartementPerPage = 10;
		        $pageNumber = ceil($appartementNumber/$appartementPerPage);
		        $p = 1;
		        if(isset($_GET['p']) and ($_GET['p']>0 and $_GET['p']<=$pageNumber)){
		            $p = $_GET['p'];
		        }
		        else{
		            $p = 1;
		        }
		        $begin = ($p - 1) * $appartementPerPage;
		        $pagination = paginate('appartements.php?idProjet='.$idProjet, '&p=', $pageNumber, $p);
				$appartements = $appartementManager->getAppartementByIdProjet($idProjet, $begin, $appartementPerPage);	
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
							Gestion des Appartements
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
							<li><a>Gestion des appartements</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<?php if($idProjet!=0){ ?>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
							<div class="row-fluid add-portfolio">
								<div class="pull-left">
									<a href="projet-list.php" class="btn icn-only green"><i class="m-icon-swapleft m-icon-white"></i> Retour vers Liste des projets</a>
								</div>
							</div>
							<?php if(isset($_SESSION['appartement-add-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['appartement-add-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-add-success']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-update-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['appartement-update-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-update-success']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-delete-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['appartement-delete-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-delete-success']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['appartement-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-add-error']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-update-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['appartement-update-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-update-error']);
	                         ?>
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Ajouter une nouvelle appartement pour le projet : <strong><?= $projet->nom() ?></strong></h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="controller/AppartementAddController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="code">Code</label>
                                             <div class="controls">
                                                <input type="text" id="code" name="code" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="superficie">Supérficie</label>
                                             <div class="controls">
                                                <input type="text" id="superficie" name="superficie" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="facade">Façade</label>
                                             <div class="controls">
                                                <input type="text" id="facade" name="facade" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="nombrePiece">Nombre Pièces</label>
                                             <div class="controls">
                                                <input type="text" id="nombrePiece" name="nombrePiece" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="prix">Prix</label>
                                             <div class="controls">
                                                <input type="text" id="prix" name="prix" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="niveau">Niveau</label>
                                             <div class="controls">
                                             	<select style="width:150px" name="niveau" class="m-wrap">
                                             		<option value="RC">RC</option>
                                             		<option value="1">1</option>
                                             		<option value="2">2</option>
                                             		<option value="3">3</option>
                                             		<option value="4">4</option>
                                             		<option value="5">5</option>
                                             		<option value="6">6</option>
                                             		<option value="7">7</option>
                                             	</select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="status">Status</label>
                                             <div class="controls">
                                             	<select style="width:150px" name="status" class="m-wrap">
                                             		<option value="Non">Non réservé</option>
                                             		<option value="Oui">Réservé</option>
                                             	</select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="cave">Cave</label>
                                             <div class="controls">
                                             	<select style="width:150px" name="cave" class="m-wrap">
                                             		<option value="Avec">Avec</option>
                                             		<option value="Sans">Sans</option>
                                             	</select>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                    	<input type="hidden" id="idProjet" name="idProjet" value="<?= $idProjet ?>" class="m-wrap span12">
                                    	<button type="reset" class="btn red">Annuler</button>
                                       	<button type="submit" class="btn black">Ajouter <i class="icon-plus-sign"></i></button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
                        </div>
					</div>
				</div>
				<div class="row-fluid"> 
					<div class="span12">
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
                         <?php if(isset($_SESSION['pieces-delete-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['pieces-delete-success'] ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['pieces-delete-success']);
                         ?>
						<div class="portlet">
							<div class="portlet-title">
								<h4>Liste des appartements du projet : <strong><?= $projet->nom() ?></strong></h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-advance table-hover">
									<thead>
										<tr>
											<th>Code</th>
											<th>Superf</th>
											<th class="hidden-phone">Façade</th>
											<th>Prix</th>
											<th>Pièces</th>
											<th class="hidden-phone">Niveau</th>
											<th class="hidden-phone">Cave</th>
											<th>Réservé</th>
											<th class="hidden-phone">Docs</th>
											<th class="hidden-phone">Docs</th>
											<th class="hidden-phone">Modif</th>
											<th class="hidden-phone">Suppri</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if($appartementNumber != 0){
										foreach($appartements as $appartement){
										?>		
										<tr>
											<td>
												<a><?= $appartement->nom() ?></a>
											</td>
											<td><?= $appartement->superficie() ?></td>
											<td class="hidden-phone"><?= $appartement->facade() ?></td>
											<td><?= (int)$appartement->prix() ?></td>
											<td><?= $appartement->nombrePiece() ?></td>
											<td class="hidden-phone"><?= $appartement->niveau() ?></td>
											<td class="hidden-phone">
												<?php if($appartement->cave()=="Sans"){ ?><a class="btn mini black">Sans</a><?php } ?>
												<?php if($appartement->cave()=="Avec"){ ?><a class="btn mini blue">Avec</a><?php } ?>
											</td>
											<td>
												<?php if($appartement->status()=="Oui"){ ?><a class="btn mini red"><?= $appartement->status(); ?></a><?php } ?>
												<?php if($appartement->status()=="Non"){ ?><a class="btn mini green"><?= $appartement->status(); ?></a><?php } ?>
											</td>
											</td>
											<td class="hidden-phone">
												<a class="btn mini purple" href="#addPieces<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
													Ajouter
												</a>
											</td>
											<td class="hidden-phone">
												<a class="btn mini yellow" href="pieces-appartement.php?idProjet=<?= $idProjet ?>&idAppartement=<?= $appartement->id() ?>">
													Gérer
												</a>
											</td>
											<td class="hidden-phone">
												<a href="#updateAppartement<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
													Modif	
												</a>
											</td>
											<td class="hidden-phone">
												<a href="#deleteAppartement<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
													Suppri
												</a>
											</td>
										</tr>
										<!-- add file box begin-->
										<div id="addPieces<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Ajouter des pièces pour cette appartement</h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal" action="controller/AppartementPiecesAddController.php" method="post" enctype="multipart/form-data">
													<p>Êtes-vous sûr de vouloir ajouter des pièces pour cette appartement ?</p>
													<div class="control-group">
														<label class="right-label">Nom Pièce</label>
														<input type="text" name="nom" />
														<label class="right-label">Lien</label>
														<input type="file" name="url" />
														<input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
														<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
														<label class="right-label"></label>
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- add files box end -->	
										<!-- update box begin-->
										<div id="updateAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Modifier Infos Appartement <strong><?= $appartement->nom() ?></strong></h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal" action="controller/AppartementUpdateController.php" method="post">
													<p>Êtes-vous sûr de vouloir modifier les informations de l'appartement ?</p>
													<div class="control-group">
														<label class="right-label">Code</label>
														<input type="text" name="code" value="<?= $appartement->nom() ?>" />
														<label class="right-label">Supérficie</label>
														<input type="text" name="superficie" value="<?= $appartement->superficie() ?>" />
														<label class="right-label">Façade</label>
														<input type="text" name="facade" value="<?= $appartement->facade() ?>" />
														<label class="right-label">Niveau</label>
														<select name="niveau" class="m-wrap">
															<option value="<?= $appartement->niveau() ?>"><?= $appartement->niveau() ?></option>
															<option disabled="disabled">-------</option>
		                                             		<option value="RC">RC</option>
		                                             		<option value="1">1</option>
		                                             		<option value="2">2</option>
		                                             		<option value="3">3</option>
		                                             		<option value="4">4</option>
		                                             		<option value="5">5</option>
		                                             		<option value="6">6</option>
		                                             		<option value="7">7</option>
		                                             </select>
														<label class="right-label">Nombre Pièces</label>
														<input type="text" name="nombrePiece" value="<?= $appartement->nombrePiece() ?>" />
														<label class="right-label">Prix</label>
														<input type="text" name="prix" value="<?= $appartement->prix() ?>" />
														<label class="right-label">Status</label>
														<?php
														$statusReserve = "";
														$statusNonReserve = "";
														if($appartement->status()=="Oui"){
															$statusReserve = "selected";
															$statusNonReserve = "";		
														}
														if($appartement->status()=="Oui"){
															$statusReserve = "";
															$statusNonReserve = "selected";		
														}
														?>
														<select name="status" class="m-wrap">
															<option value="Non" <?php echo $statusReserve; echo $statusNonReserve ?> >
																Non réservé
															</option>
                                             				<option value="Oui" <?php echo $statusReserve; echo $statusNonReserve ?> >
                                             					Réservé
                                             				</option>
														</select>
														<label class="right-label">Cave</label>
														<?php
														$avecCave = "";
														$sansCave = "";
														if($appartement->cave()=="Avec"){
															$avecCave = "selected";
															$sansCave = "";		
														}
														if($appartement->status()=="Sans"){
															$avecCave = "";
															$sansCave = "selected";		
														}
														?>
														<select name="cave" class="m-wrap">
															<option value="Sans" <?php echo $avecCave; echo $sansCave; ?> >
																Sans
															</option>
                                             				<option value="Avec" <?php echo $avecCave; echo $sansCave; ?> >
                                             					Avec
                                             				</option>
														</select>
														<input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
														<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
														<label class="right-label"></label>
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- update box end -->	
										<!-- delete box begin-->
										<div id="deleteAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer Appartement <strong><?= $appartement->nom() ?></strong> </h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="controller/AppartementDeleteController.php" method="post">
													<p>Êtes-vous sûr de vouloir supprimer cette appartement <strong><?= $appartement->nom() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
														<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
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
									if($appartementNumber != 0){
										echo $pagination;	
									}
									?>
								</table>
							</div>
						</div>
						<!-- END Terrain TABLE PORTLET-->
					</div>
				</div>
				<?php }
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
else if(isset($_SESSION['user']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>