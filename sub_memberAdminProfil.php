<?php
if (empty($_GET['prf'])) 
{
	header("Location: ?pg=1.11");
}
?>

<div class="back">
	<!-- Sidemenu -->
	<?php include "sub_menuAdmin.php"?>
	<!-- Sidemenu -->

	<form class="content modifyMbrSolo" enctype="multipart/form-data">
		<input type="hidden" name="formType" value="modifyMbr">
		<input type="hidden" name="id" value="<?=$profil['id']?>">
		<img src="voImg/menu.png" class="toggleMenu">
		<div class="bigTitle">Profil de membre</div>

		<div class="bigContentExtended">
			<input type="file" id="modifyPicProfile" name="pic" accept="image/*">
			<label for="modifyPicProfile" class="profilePicContain" style="<?=$shadow?>">
				<div class="picLoader"></div>
				<img src="voMbrs/mbr<?=$profil['id']?>/<?=$profil['pic']?>" alt="Photo de profil">
			</label>
			
			<div class="profilePicName" ><?=$profil['nom']?> <?=$profil['prenom']?>
				<span>@<?=$profil['surnom']?></span>
			</div>
		</div>
		<div class="bigContent">
			
			<div class="card">
				<div class="cardTitle">
					<span><strong>Autres</strong></span>
				</div>
				<div class="profilInputDiv">
					<label for="">Couleur préférée</label>
					<input type="text" name="color" value="<?=$profil['color']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Véhicule</label>
					<input type="text" name="car" value="<?=$profil['car']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Pseudo</label>
					<input type="text" name="pseudo" value="<?=$profil['pseudo']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Type de membre</label>
					<select name="type">
						<option value="0" <?php if( $profil['type'] == 0 ){ echo "selected"; }?>>Membre probatoire</option>
						<option value="1" <?php if( $profil['type'] == 1 ){ echo "selected"; }?>>Membre normal</option>
						<option value="2" <?php if( $profil['type'] == 2 ){ echo "selected"; }?>>Membre administrateur</option>
					</select>
				</div>
			</div>

			<div class="card">
				<div class="cardTitle">
					<span><strong>Adresse</strong></span>
				</div>
				<div class="profilInputDiv">
					<label for="">Adresse</label>
					<input type="text" name="adrss" value="<?=$profil['addrs']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Complément d'adresse</label>
					<input type="text" name="adrss2" value="<?=$profil['addrs2']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Ville</label>
					<input type="text" name="ville" value="<?=$profil['ville']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Code postal</label>
					<input type="number" name="cp" value="<?=$profil['cp']?>">
				</div>
			</div>

			<div class="card">
				<div class="cardTitle">
					<span><strong>Personnel</strong></span>
				</div>
				<div class="profilInputDiv">
					<label for="">Nom</label>
					<input type="text" name="nom" value="<?=$profil['nom']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Prénom</label>
					<input type="text" name="prenom" value="<?=$profil['prenom']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Surnom</label>
					<input type="text" name="surnom" value="<?=$profil['surnom']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Date de naissance</label>
					<input type="date" name="dateN" value="<?=$profil['dateN']?>">
				</div>
				
			</div>
			<div class="card">
				<div class="cardTitle">
					<span><strong>Personnel bis</strong></span>
				</div>
				<div class="profilInputDiv">
					<label for="">Date d'inscription</label>
					<input type="date" name="dateI" value="<?=$profil['dateE']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Taille vêtement</label>
					<select name="taille" required>
						<option value="XS" <?php if( $profil['tailleV'] == "XS" ){ echo "selected"; }?>>XS</option>
						<option value="S" <?php if( $profil['tailleV'] == "S" ){ echo "selected"; }?>>S</option>
						<option value="M" <?php if( $profil['tailleV'] == "M" ){ echo "selected"; }?>>M</option>
						<option value="L" <?php if( $profil['tailleV'] == "L" ){ echo "selected"; }?>>L</option>
						<option value="XL" <?php if( $profil['tailleV'] == "XL" ){ echo "selected"; }?>>XL</option>
						<option value="XXL" <?php if( $profil['tailleV'] == "XXL" ){ echo "selected"; }?>>XXL</option>
						<option value="XXXL" <?php if( $profil['tailleV'] == "XXXL" ){ echo "selected"; }?>>XXXL</option>
					</select>
				</div>
				<div class="profilInputDiv">
					<label for="">Civilité</label>
					<select name="civ" required>
						<option value="Mr" <?php if( $profil['civ'] == "Mr" ){ echo "selected"; }?>>Mr</option>
						<option value="Mme" <?php if( $profil['civ'] == "Mme" ){ echo "selected"; }?>>Mme</option>
						<option value="Autres" <?php if( $profil['civ'] == "Autres" ){ echo "selected"; }?>>Autres</option>
					</select>
				</div>
			</div>
			<div class="card">
				<div class="cardTitle">
					<span><strong>Contact</strong></span>
				</div>
				<div class="profilInputDiv">
					<label for="">E-mail</label>
					<input type="email" name="mail" value="<?=$profil['mail']?>">
				</div>
				<div class="profilInputDiv">
					<label for="">Téléphone</label>
					<input type="tel" name="phone" value="<?=$profil['phone']?>">
				</div>

			</div>
			
		</div>
		<div class="bigContent">
			<?php 
			if ($percentPres==0) {
				$circle = "border-right-color: transparent;
			border-left-color: transparent;
			border-top-color: transparent;
			border-bottom-color: transparent;
			";
			}
			else if($percentPres>0 && $percentPres<=25)
			{
				$circle = "border-right-color: transparent;
			border-left-color: transparent;
			border-bottom-color: transparent;
			";
		} else if($percentPres>25 && $percentPres<=50)
		{
			$circle = "border-left-color: transparent;
			border-bottom-color: transparent;";
		} else if($percentPres>50 && $percentPres<=75)
		{
			$circle = "border-left-color: transparent;";
		} else
		{
			$circle = "";
		}
		// On définit le pourcentage d'abscence
		$percentAbs = 100 - $percentPres;
			?>

			<!-- Pourcentage de présence -->
			<div class="card">
				<div class="cardTitle">
					<span>Présence</span>
				</div>
				<div class="cardNumber"><?=$percentPres?>%</div>
				<div class="cardPercentMonth <?=$MstylePres?>"><?=$Mpres?>% <span>ce mois</span></div>
				<div class="cardCircleDiv">
					<div class="cardCircle" style="<?=$circle?>">
						
					</div>
				</div>
			</div>
			<?php 
				if ($percentAbs==0) {
				$circle = "border-right-color: transparent;
			border-left-color: transparent;
			border-top-color: transparent;
			border-bottom-color: transparent;
			";
			}
			else if($percentAbs>0 && $percentAbs<=25)
			{
				$circle = "border-right-color: transparent;
			border-left-color: transparent;
			border-bottom-color: transparent;
			";
		} else if($percentAbs>25 && $percentAbs<=50)
		{
			$circle = "border-left-color: transparent;
			border-bottom-color: transparent;";
		} else if($percentAbs>50 && $percentAbs<=75)
		{
			$circle = "border-left-color: transparent;";
		} else
		{
			$circle = "";
		}
			?>

			<!-- Pourcentage d'abscence -->
			<div class="card">
				<div class="cardTitle">
					<span>Abscence</span>
				</div>
				<div class="cardNumber"><?=$percentAbs?>%</div>
				<div class="cardPercentMonth <?=$MstyleAbs?>"><?=$Mabs?>% <span>ce mois</span></div>
				<div class="cardCircleDiv">
					<div class="cardCircle abs" style="<?=$circle?>">
						
					</div>
				</div>
			</div>
			<?php  
				$circle2 = "";
				if ($percentCotis <= 25 && $percentCotis > 0) {
					$circle2 = "border-top-color: seagreen";
				}
				elseif ($percentCotis <= 50 && $percentCotis > 25) {
					$circle2 = "border-top-color: seagreen; border-right-color: seagreen;";
				} 
				elseif ($percentCotis <= 75 && $percentCotis > 50) {
					$circle2 = "border-top-color: seagreen; border-right-color: seagreen; border-bottom-color: seagreen;";
				} 
				elseif ($percentCotis <= 100 && $percentCotis > 75) {
					$circle2 = "border-top-color: seagreen; border-right-color: seagreen; border-bottom-color: seagreen; border-left-color: seagreen;";
				} 
			?>
			<div class="card">
				<div class="cardTitle" style="align-items: center;">
					<div class="cardLeftImg">
						<img src="voimg/wallet.png" alt="Wallet">
					</div>
					<div class="percContentCotis">
					</div>
				</div>
				<div class="cardSousTitle">Argent dépensé</div>
				<div class="cardNumber"><?=$totalDpsMbr?>€</div>
				<div class="cardSousTitle2">Depuis inscription</div>
			</div>

			<div class="card">
				<div class="cardTitle">
					<span>Présence</span>
				</div>
				<div class="cardScroll">
					<?php while ( $res = $presList->fetch() ) 
					{ 
						// On récupère l'évènement lié
						$event = $bdd->prepare('SELECT * FROM events INNER JOIN event_gender ON events.idGenre = event_gender.idGenre WHERE id = :id');
						$event->execute(['id' => $res['idEvent']]);
						$event = $event->fetch();
						if ($event['type'] == 0) 
						{
							$title = "Rasso ".$event['libelle'];
						}
						else
						{
							$title = "Sortie ".$event['libelle'];
						}

						if ($res['pres'] == 1) 
						{
							$presStyle = "background: rgba(70, 200, 80, .3);color: limegreen;";
							$presText = "Présent";
						}
						else
						{
							$presStyle = "background: rgba(200, 70, 80, .3);color: indianred;";
							$presText = "Absent";
						}
						$date = eventDate($event['date']);
						$date.= " ".explode('-', $event['date'])[0];
						?>
					<div class="profilPresenceDiv">
						<div class="profilPresenceTitle">
							<?=$title?>
							<span>
								<?=$date?>
							</span>
						</div>
						<div class="profilPresenceStatut">
							<span style="<?=$presStyle?>">
								<?=$presText?>
							</span>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
			<div class="card full">
				<div class="cardTitle">
					<span>Transaction</span>
				</div>
				<?php while ($res = $transacList->fetch()) 
				{ 
					if ($res['type'] == 2) 
					{
						$type = "Cotisation annuelle";
						$style = "background: rgba(70, 80, 200, .3);color: lightblue;";
					}
					elseif ($res['type'] == 0)
					{
						$type = "Cotisation évènement";
						$style = "background: rgba(70, 200, 80, .3);color: limegreen;";
					}
					elseif ($res['type'] == 1)
					{
						$type = "Remboursement";
						$style = "background: rgba(90, 90, 30, .3);color: yellowgreen;";
					}

					// Infos évènement si défini
					if ($res['idEvent'] != 0) 
					{
						$query = $bdd->prepare('SELECT * FROM events INNER JOIN event_gender ON events.idGenre = event_gender.idGenre WHERE id = :id');
						$query->execute(['id' => $res['idEvent']]);
						$event = $query->fetch();
						if ($event['type'] == 1) 
						{
							$eventTitle = "Sortie ".$event['libelle'];
						}
						elseif ($event['type'] == 0) 
						{
							$eventTitle = "Rassemblement ".$event['libelle'];
						}
					}

					// Date de la transaction
					$transacDate = eventDate($res['date']);
					$trYear = explode('-', $res['date'])[0];
					$transacDate .= ' '.$trYear;

					?>
				<div class="profilTransacDiv">
					<div class="profilTransacCol profilTransacPrice" <?php if( $res['type'] == 1 ){ echo 'style="color: seagreen ;"'; } ?>>
						<?=$res['montant']?>€
					</div>
					<div class="profilTransacCol profilTransacDate">
						<?=$transacDate?>
					</div>
					<div class="profilTransacCol profilTransacType">
						<?php if ($res['idEvent'] != 0) 
						{
							// On récupère la date en français
							$eventDate = eventDate($event['date']);
							$eventDate.= ' '.explode('-', $event['date'])[0];
						 ?>
							<div class="profilTransacEventShow">
								<div><?=$eventTitle?></div>
								<span><?=$eventDate?></span>
							</div>
						<?php } ?>
						<span style="<?=$style?>">
							<?=$type?>
						</span>
					</div>
				</div>
			<?php } ?>
				
			</div>
		</div>
		<div class="valideFloatButton">
			<button>Modifier</button>
		</div>
	</form>
</div>
<?php if ($cotisCheck == 1) 
{ ?>
<div class="adminTab" id="">
		
	<div class="adminTabTitle">
		Attention
	</div>
	<div style="font-weight: bold; font-size: 17px; letter-spacing: 1px; line-height: 30px;"> 
		La cotisation annuelle de ce membre n'as toujours pas été réglé pour l'année <?=$currentYear?>
	</div>
	<div class="adminTabButtons">
		<button type="button" class="annule">Fermer</button>
		<button class="valid" id="openCotisAnn">Régler</button>
	</div>
</div>
<?php } ?>