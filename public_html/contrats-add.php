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
		if((isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId())
		and (isset($_GET['codeClient']) and (bool)$clientManager->getCodeClient($_GET['codeClient']) )){
			$idProjet = $_GET['idProjet'];
			$codeClient = $_GET['codeClient'];
			$projet = $projetManager->getProjetById($idProjet);
			$client = $clientManager->getClientByCode($codeClient);
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
							Gestion des Contrats
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
							<li><a>Gestion des contrats</a></li>
							<?php 
								$clientManager = new ClientManager($pdo);
							?>
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
	                         <?php if(isset($_SESSION['contrat-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['contrat-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['contrat-add-error']);
	                         ?>
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Nouveau Client/Contrat pour le projet : <strong><?= $projet->nom() ?></strong></h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="controller/ContratAddController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                    	<div class="span12">
                                    		<img src="assets/img/form_wizard_client_contrat_2.png">
                                    	</div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span12">
                                    		<div class="progress progress-striped progress-success">
												<div style="width: 100%;" class="bar"></div>
											</div>
                                    	</div>
                                    </div>
                                    <legend>Création du Contrat pour le Client : <?= $client->nom() ?></legend>
                                    <div class="row-fluid">
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="dateCreation">Date de création</label>
                                             <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
				                                    <input name="dateCreation" id="dateCreation" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
				                                    <span class="add-on"><i class="icon-calendar"></i></span>
				                                 </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label">Type du bien</label>
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
                                       </div>
                                       <div class="span4 hidenBlock">
                                          <div class="control-group">
                                             <div class="controls">
                                             	<label class="control-label" for="" id="nomBienLabel"></label>
                                             	<select class="m-wrap" name="bien" id="bien">
                                             	</select>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="prixNegocie">Prix négocié</label>
                                             <div class="controls">
                                                <input type="text" id="prixNegocie" name="prixNegocie" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    	<div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="avance">Avance</label>
                                             <div class="controls">
                                                <input type="text" id="avance" name="avance" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="dureePaiement">Durée de paiement</label>
                                             <div class="controls">
                                             	<input type="text" id="dureePaiement" name="dureePaiement" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    	<div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="echeance">Echéance</label>
                                             <div class="controls">
                                             	<input type="text" id="echeance" name="echeance" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span6">
                                          <div class="control-group">
                                             <label class="control-label" for="modePaiement">Mode de paiement</label>
                                             <div class="controls">
                                                <div class="controls">
													<select name="modePaiement">
														<option value="Espèces">Espèces</option>
														<option value="Chèque">Chèque</option>
														<option value="Versement">Versement</option>
													</select>
												</div>
                                             </div>
                                          </div>
                                       </div>
                                    	<div class="span6">
                                          <div class="control-group">
                                             <label class="control-label" for="note">Note client</label>
                                             <div class="controls">
                                                <textarea id="note" name="note"></textarea>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                    	<input type="hidden" id="idProjet" name="idProjet" value="<?= $idProjet ?>" class="m-wrap span12">
                                    	<input type="hidden" id="idClient" name="idClient" value="<?= $client->id() ?>" class="m-wrap span12">
                                    	<input type="hidden" id="codeClient" name="codeClient" value="<?= $codeClient ?>" class="m-wrap span12">
                                       <button type="submit" class="btn black">Terminer <i class="icon-ok m-icon-white"></i></button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
                        </div>
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
				var idProjet = <?= $idProjet ?>;
				var data = 'typeBien='+typeBien+'&idProjet='+idProjet;
				$.ajax({
					type: "POST",
					url: "types-biens.php",
					data: data,
					cache: false,
					success: function(html){
						$('#bien').html(html);
						if(typeBien=="appartement"){
							$('#nomBienLabel').text("Les appartements");	
						}
						else{
							$('#nomBienLabel').text("Les locaux commerciaux");
						}
					}
				});
			});
			$('#dureePaiement').change(function(){
				var dureePaiement = $(this).val();
				var prixNegocie = $('#prixNegocie').val();
				var avance = $('#avance').val();
				var echeance = Math.round( ( prixNegocie - avance ) / dureePaiement );
				$('#echeance').val(echeance);
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