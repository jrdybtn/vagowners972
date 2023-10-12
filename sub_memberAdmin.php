<div class="back">
	<!-- Sidemenu -->
	<?php include "sub_menuAdmin.php"?>
	<!-- Sidemenu -->

	<div class="content">
		<img src="voImg/menu.png" class="toggleMenu">
		<div class="bigTitle">Membres</div>

		<!-- 1ère division de la page -->
		<div class="bigContent">
			<?php 
				$circle = "";
				if ($percentCotisA <= 25 && $percentCotisA > 0) {
					$circle = "border-top-color: deeppink";
				}
				elseif ($percentCotisA <= 50 && $percentCotisA > 25) {
					$circle = "border-top-color: deeppink; border-right-color: deeppink;";
				} 
				elseif ($percentCotisA <= 75 && $percentCotisA > 50) {
					$circle = "border-top-color: deeppink; border-right-color: deeppink; border-bottom-color: deeppink;";
				} 
				elseif ($percentCotisA <= 100 && $percentCotisA > 75) {
					$circle = "border-top-color: deeppink; border-right-color: deeppink; border-bottom-color: deeppink; border-left-color: deeppink;";
				} 
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
						<div class="percCircleCotis" style="<?=$circle?>"><?=$percentCotisA?>%</div>
					</div>
				</div>
				<div class="cardSousTitle">Cotisation annuelle</div>
				<div class="cardNumber"><?=$cotisAnn?>€</div>
				<div class="cardSousTitle2">Courant <?=$currentYear?></div>
			</div>
			<div class="card">
				<div class="cardTitle" style="align-items: center;">
					<div class="cardLeftImg">
						<img src="voimg/wallet.png" alt="Wallet">
					</div>
					<div class="percContentCotis">
						<div class="percCircleCotis" style="<?=$circle2?>"><?=$percentCotis?>%</div>
					</div>
				</div>
				<div class="cardSousTitle">Cotisation récolté</div>
				<div class="cardNumber"><?=$cotisEvent?>€</div>
				<div class="cardSousTitle2">Courant <?=$currentYear?></div>
			</div>
			<!-- Nombres de membres -->
			<div class="card">
				<div class="cardTitle">
					<span>Membres</span>
					<img src="voImg/membres.png" alt="">
				</div>
				<div class="cardNumber" id="totalMember"><?=$nbMbr?></div>
				<div class="cardPercentMonth">+<?=$nbMbrMonth?> <span>ce mois</span></div>
			</div>

		</div>

		<!-- 2eme division de la page -->
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
		</div>

		<div class="bigContentExtended">

			<form class="filterCard">
				<input type="hidden" value="filter" name="formType">
				<div class="filterSub">
					Nom
					<select name="nom">
						<option value="">
							Tout
						</option>
						<option value="1">
							A-Z
						</option>
						<option value="2">
							Z-A
						</option>
					</select>
				</div>

				<div class="filterSub">
					Prénom
					<select name="prenom">
						<option value="">
							Tout
						</option>
						<option value="1">
							A-Z
						</option>
						<option value="2">Z-A</option>
					</select>
				</div>	

				<div class="filterSub">
					Civilité
					<select name="civ">
						<option value="">
							Tout
						</option>
						<option value="Mr">
							Mr
						</option>
						<option value="Mme">
							Mme
						</option>
						<option value="Autres">
							Autres
						</option>
					</select>
				</div>

				<div class="filterSub">
					Présence
					<select name="pres">
						<option value="">
							Tout
						</option>
						<option value="1">
							Croissant
						</option>
						<option value="2">
							Décroissant
						</option>
					</select>
				</div>

				
				<div class="filterSub">
					Mois d'entrée
					<input type="month" name="month">
				</div>

				<div class="filterSub">
					<label for="cp">Code postal
						<input type="checkbox" name="cp" id="cp">
						<span class="checkStyle"></span>
					</label>
					
				</div>

				<div class="filterSub">
					<label for="car">Voiture
						<input type="checkbox" name="car" id="car">
						<span class="checkStyle"></span>
					</label>
				</div>

				<div class="filterSubSend">
					<?php 
					if (!empty($_SESSION['voConnect']['id'])) {
						$delId = $_SESSION['voConnect']['id'];
					}
					else
					{
						$delId = 1;
					}
					?>
					<button class="annuler" onclick="deleteMemberCall('<?=$delId?>','1')" type="button">Tout supprimer</button>
					<button>
						Filtrer
					</button>
				</div>
			</form>
			
			<?php $i = 0;
			while ($res = $membersList2->fetch()) 
			{ ?>
				<form class="adminTab modifyMbrTab" method="POST" id="modifyMbr<?=$res['id']?>">
							<input type="hidden" name="formType" value="modifyMbr">
							<input type="hidden" name="id" value="<?=$res['id']?>">
							<div class="adminTabTitle">
								Modification de membre
							</div>
							<div class="adminTabScroll">
								<div class="adminTabSection">
								<div class="adminTabSubTitle">
									Personnel
								</div>
								<div class="sectionPart">
									<div class="adminTabInput">
									<label for="">Nom</label>
									<input type="text" name="nom" value="<?=$res['nom']?>">
								</div>
								<div class="adminTabInput">
									<label for="">Prénom</label>
									<input type="text" name="prenom" value="<?=$res['prenom']?>">
								</div>
								<div class="adminTabInput">
									<label for="">Surnom</label>
									<input type="text" name="surnom" value="<?=$res['surnom']?>">
								</div>
								<div class="adminTabInput">
									<label for="">Date naissance</label>
									<input type="date" name="dateN" value="<?=$res['dateN']?>">
								</div>
								<div class="adminTabInput">
									<label for="">Taille</label>
									<select name="taille" required>
										<option value="XS" <?php if( $res['tailleV'] == "XS" ){ echo "selected"; }?>>XS</option>
										<option value="S" <?php if( $res['tailleV'] == "S" ){ echo "selected"; }?>>S</option>
										<option value="M" <?php if( $res['tailleV'] == "M" ){ echo "selected"; }?>>M</option>
										<option value="L" <?php if( $res['tailleV'] == "L" ){ echo "selected"; }?>>L</option>
										<option value="XL" <?php if( $res['tailleV'] == "XL" ){ echo "selected"; }?>>XL</option>
										<option value="XXL" <?php if( $res['tailleV'] == "XXL" ){ echo "selected"; }?>>XXL</option>
										<option value="XXXL" <?php if( $res['tailleV'] == "XXXL" ){ echo "selected"; }?>>XXXL</option>
									</select>
								</div>
								<div class="adminTabInput">
									<label for="">Date inscription</label>
									<input type="date" name="dateI" value="<?=$res['dateE']?>">
								</div>
								<div class="adminTabInput">
									<label for="">Civilité</label>
									<select name="civ" required>
										<option value="Mr" <?php if( $res['civ'] == "Mr" ){ echo "selected"; }?>>Mr</option>
										<option value="Mme" <?php if( $res['civ'] == "Mme" ){ echo "selected"; }?>>Mme</option>
										<option value="Autres" <?php if( $res['civ'] == "Autres" ){ echo "selected"; }?>>Autres</option>
									</select>
								</div>
								</div>
							</div>
							<div class="adminTabSection">
								<div class="adminTabSubTitle">
									Adresse
								</div>
								<div class="sectionPart">
							<div class="adminTabInput">
								<label for="">Adresse</label>
								<input type="text" name="adrss" value="<?=$res['addrs']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Complément d'adresse</label>
								<input type="text" name="adrss2" value="<?=$res['addrs2']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Ville</label>
								<input type="text" name="ville" value="<?=$res['ville']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Code postal</label>
								<input type="number" name="cp" value="<?=$res['cp']?>"> 
							</div>
						</div>
						</div>
						<div class="adminTabSection">
								<div class="adminTabSubTitle">
									Contact
								</div>
								<div class="sectionPart">
							<div class="adminTabInput">
								<label for="">E-mail</label>
								<input type="mail" name="mail" value="<?=$res['mail']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Téléphone</label>
								<input type="tel" name="phone" value="<?=$res['phone']?>">
							</div>
						</div>
						</div>
						<div class="adminTabSection">
								<div class="adminTabSubTitle">
									Autres
								</div>
								<div class="sectionPart">
							<div class="adminTabInput">
								<label for="">Couleur préférée</label>
								<input type="text" name="color" value="<?=$res['color']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Véhicule</label>
								<input type="text" name="car" value="<?=$res['car']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Pseudo</label>
								<input type="text" name="pseudo" value="<?=$res['pseudo']?>">
							</div>
							<div class="adminTabInput">
								<label for="">Type de membres</label>
								<select name="type">
									<option value="0" <?php if( $res['type'] == 0 ){ echo "selected"; }?>>Membre probatoire</option>
									<option value="1" <?php if( $res['type'] == 1 ){ echo "selected"; }?>>Membre normal</option>
									<option value="2" <?php if( $res['type'] == 2 ){ echo "selected"; }?>>Membre administrateur</option>
								</select>
							</div>
						</div>
						</div>
						</div>
							<div class="adminTabButtons">
								<button type="button" class="annule">Annuler</button>
								<button class="valid">Modifier</button>
							</div>
				</form>
			<?php } ?>
			<form id="membersList">

			 <?php while ($res = $membersList->fetch()) 
			{ ?>
				

				<?php

					

				$dot = '';
				if ($res['pres'] <= 25) {
					$dot = 'red';
				}
				elseif ( $res['pres']> 25 && $res['pres'] <= 40 ) 
				{
					$dot = 'yellow';
				}
				else
				{
					$dot = 'green';
				}

				// Evènements précédent
				$prevTotal = 0;
				$prevPres = 0;
				$prevEvents = $bdd->prepare('SELECT * FROM events WHERE MONTH( date ) = :month AND YEAR( date ) = :year AND date>=:dateE');
				$prevEvents->execute(['month' => $evM, 'year' => $evY, 'dateE' => $res['dateE']]);
				while( $prevRes = $prevEvents->fetch() ) 
				{
					$req = $bdd->prepare('SELECT * FROM eventpres WHERE idEvent = :event AND idUsers = :user');
					$req->execute(['event' => $prevRes['id'], 'user' => $res['id']]);
					$resu = $req->fetch();
					if (!empty($resu)) {
						if ($resu['pres'] == 1) {
						$prevPres++;
					}
					}
					$prevTotal++;
				}
				// On calcul maintenant le pourcentage de présence
				if ($prevTotal == 0) {
					 $prevPercent = 0;
				}
				else {
					$prevPercent = round( ($prevPres * 100) / $prevTotal, 0 );
				}

				// Evènements actuel
				$actPres = 0;
				// Récupération des évènements de ce mois
				$actEvents = $bdd->prepare('SELECT * FROM events WHERE MONTH( date ) = :month AND YEAR( date ) = :year AND date>=:dateE');
				$actEvents->execute(['month' => $actuelMonth, 'year' => $actuelYear, 'dateE' => $res['dateE']]);
				$actTotal = 0;

				while( $actRes = $actEvents->fetch() ) 
				{
					$req = $bdd->prepare('SELECT * FROM eventpres WHERE idEvent = :event AND idUsers = :user');
					$req->execute(['event' => $actRes['id'], 'user' => $res['id']]);
					$resu = $req->fetch();
					if (!empty($resu)) {
						if ($resu['pres'] == 1) {
						$actPres++;
					}
					}
					$actTotal++;
				}

				// On calcul maintenant le pourcentage de présence
				if ($actTotal == 0) {
					 $actPercent = 0;
				}
				else {
					$actPercent = round( ($actPres * 100) / $actTotal, 0 );
				}

				$compPercent = $actPercent - $prevPercent;

				if ($compPercent >= 0) {
					$compPercent = "+".$compPercent;
					$color = "";
				}
				else
				{
					$color = "color: indianred";
				}

				// On définit l'ombre selon si ce membre a réglé sa cotisation annuelle
				$query = $bdd->prepare('SELECT COUNT(*) as total FROM transaction WHERE idUser = :id AND YEAR( date ) = :year AND type = 2');
				$query->execute(['year' => $actuelYear, 'id' => $res['id']]);
				$cotAnnRgl = $query->fetch();
				if ($cotAnnRgl['total'] == 0) 
				{
					$shadow = 'box-shadow: rgba(214, 20, 3, 0.3) 0px 0px 10px 2px';
				}
				else
				{
					$shadow = "";
				}

				
				?>
			
			<div class="memberCard" id="member-<?=$res['id']?>" style="<?=$shadow?>">
				<input type="checkbox" id="select-<?=$res['id']?>" class="memberCheckbox">
				<label for="select-<?=$res['id']?>" class="memberSelect"></label>
					<div class="memberPic">
						<img src="voMbrs/mbr<?=$res['id']?>/<?=$res['pic']?>" alt="Membre">
						<div class="memberDot" style="background: <?=$dot?>;">
							
						</div>
					</div>
					<div class="memberName">
						<?=$res['nom']?> <?=$res['prenom']?>
						<span class="memberSurname">
							@<?=$res['surnom']?>
						</span>
					</div>
					<div class="memberOption">
						<img src="voImg/edit.png" alt="Modifier" title="Modifier" onclick="editMbr('<?=$res['id']?>')">
						<img src="voImg/delete.png" alt="Supprimer" title="Supprimer" onclick="deleteMemberCall('<?=$res['id']?>', '0')">
					</div>
					<div class="memberPres">
						<div class="memberPresLeft">
							<span style="<?=$color?>"><?=$compPercent?>%</span> ce mois
						</div>
						<div class="memberPresRight">
							<?=round($res['pres'],0)?>%
							<span>Taux de présence</span>
						</div>
					</div>
					<button class="memberProfil" type="button" onclick="window.location.href='?pg=1.111&prf=<?=$res['id']?>'">
						Profil
					</button>
	
			</div>

			
			
		<?php
		 $i++; } ?>
		<button class="selectValide"><img src="voImg/delete.png" alt="" title="Supprimer la sélection"></button>
		</form>
		</div>

	</div>

</div>
