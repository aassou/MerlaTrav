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
		$fournisseurManager = new FournisseurManager($pdo);
		$livraisonManager = new LivraisonManager($pdo);
		if((isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId())
		and (isset($_GET['codeFournisseur']) and (bool)$fournisseurManager->getCodeFournisseur($_GET['codeFournisseur']) )){
			$idProjet = $_GET['idProjet'];
			$codeFournisseur = $_GET['codeFournisseur'];
			$projet = $projetManager->getProjetById($idProjet);
			$fournisseur = $fournisseurManager->getFournisseurByCode($codeFournisseur);
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
							Gestion des Livraisons
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
							<li><a>Gestion des livraisons</a></li>
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
	                         <?php if(isset($_SESSION['livraison-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['livraison-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['livraison-add-error']);
	                         ?>
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Nouveau Fournisseur/Livraison pour le projet : <strong><?= $projet->nom() ?></strong></h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="controller/LivraisonAddController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                    	<div class="span12">
                                    		<img src="assets/img/form_wizard_fournisseur_livraison_2.png">
                                    	</div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span12">
                                    		<div class="progress progress-striped progress-success">
												<div style="width: 100%;" class="bar"></div>
											</div>
                                    	</div>
                                    </div>
                                    <legend>Création de livraison pour le fournisseur : <?= $fournisseur->nom() ?></legend>
                                    <div class="row-fluid">
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="dateCreation">Date de livraison</label>
                                             <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
				                                    <input name="dateLivraison" id="dateLivraison" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
				                                    <span class="add-on"><i class="icon-calendar"></i></span>
				                                 </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="libelle">Libellé</label>
                                             <div class="controls">
                                                <input type="text" id="libelle" name="libelle" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="designation">Désignation</label>
                                             <div class="controls">
                                                <input type="text" id="designation" name="designation" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="prixUnitaire">Prix unitaire</label>
                                             <div class="controls">
                                                <input type="text" id="prixUnitaire" name="prixUnitaire" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="quantite">Quantité</label>
                                             <div class="controls">
                                                <input type="text" id="quantite" name="quantite" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="total">Total</label>
                                             <div class="controls">
                                                <input type="text" id="total" name="total" class="m-wrap span12" value="0">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                    	<input type="hidden" id="idProjet" name="idProjet" value="<?= $idProjet ?>" class="m-wrap span12">
                                    	<input type="hidden" id="idFournisseur" name="idFournisseur" value="<?= $fournisseur->id() ?>" class="m-wrap span12">
                                    	<input type="hidden" id="codeFournisseur" name="codeFournisseur" value="<?= $codeFournisseur ?>" class="m-wrap span12">
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
	$(function(){
        $('#prixUnitaire, #quantite').change(function(){
            var prixUnitaire = parseFloat($('#prixUnitaire').val());
            var quantite = parseFloat($('#quantite').val());
            var total = 0;
            total = prixUnitaire * quantite;
            $('#total').val(total);
            $('#paye').val(0);
            $('#reste').val(total);
        });
    });
    $(function(){
        $('#paye').change(function(){
            var paye = parseFloat($('#paye').val());
            var total = parseFloat($('#total').val());
            var reste = 0;
            reste = total - paye;
            $('#reste').val(reste);
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