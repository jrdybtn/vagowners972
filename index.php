<?php include('voPhp/need.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="voStyle/vo.css">
	<title>Vag Owners</title>
</head>
<body>
	<?php 
	$link1 = "";
	$link2 = "";
	$link3 = "";
	$link4 = "";
		if (!isset($_SESSION['voConnect'])) 
		{
			$pg = 0;
		}
		else 
		{
			if (!isset($_GET['pg']) && $_SESSION['voConnect']['type'] == 2) 
			{
				$pg = 1.1;
				$link1 = 'id="linkActive"';
			}
			elseif (!isset($_GET['pg']) && $_SESSION['voConnect']['type'] == 0 || $_SESSION['voConnect']['type'] == 1) 
			{
				$pg = 1.2;
			}

			// Si on clique sur un lien dans le menu
			elseif ( isset( $_GET['pg'] ) ) 
			{
				if ( $_SESSION['voConnect']['type'] == 2 && $_GET['pg'] == 1.1) 
				{
					$pg = 1.1;
					$link1 = 'id="linkActive"';
				}

				elseif ( $_SESSION['voConnect']['type'] == 2 && $_GET['pg'] == 1.11 ) 
				{
					$pg = 1.11;
					$link2 = 'id="linkActive"';
				}
				elseif ( $_SESSION['voConnect']['type'] == 2 && $_GET['pg'] == 1.111 ) 
				{
					$pg = 1.111;
					$link2 = 'id="linkActive"';
				}
				elseif( $_SESSION['voConnect']['type'] == 2 )
				{
					$pg = 1.1;
					$link1 = 'id="linkActive"';
				}
			}
		}

		switch ($pg) 
		{
			case 0: //Si personne n'est connecté
				include('sub_login.php');
				break;
			case 1.1:	//Si un admin est connecté (page d'accueil)
				include('sub_homeAdmin.php');
				break;
			case 1.11:	//Page membres (admin)
				include('sub_memberAdmin.php');
				break;
				case 1.111:	//Page profil membres (admin)
				include('sub_memberAdminProfil.php');
				break;
		}
	?>
	<div class="alert">
		<div class="alertTitle">Succès</div>
		<div class="alertContent">Vous avez bien été connecté</div>
	</div>

	<!-- Div d'ajout de membres -->
	<form class="adminTab" method="POST" id="addMbrsForm" enctype="multipart/form-data">
		<?=$addMbrCont?>
	</form>
	<!-- Div d'ajout d'évènements -->
	<form class="adminTab" method="POST" id="addEvtForm">
		<?=$addEvtCont?>
	</form>
	<!-- Div d'ajout de genre d'évènements -->
	<form class="adminTab" method="POST" id="addGenderForm" enctype="multipart/form-data">
		<?=$addGendCont?>
	</form>

	<!-- Div d'ajout de transaction -->
	<form class="adminTab" method="POST" id="addMoneyForm" enctype="multipart/form-data">
		<?=$addMoneyCont?>
	</form>
	
	<!-- Div de confirmation -->
	<div class="adminTab" id="confirmDiv">
		<?=$confirmDelCont?>
	</div>
	


	<!-- Loader -->
	<div class="loaderContent">
		<div class="loader"></div>
	</div>
</body>
<script src="voScript/jquery.js"></script>
<script src="voScript/vo.js"></script>
<script src="voScript/function.js"></script>
</html>