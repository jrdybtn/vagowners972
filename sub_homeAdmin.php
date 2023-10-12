<div class="back">
	<!-- SideMenu -->
	<?php include "sub_menuAdmin.php"?>
	<!-- SideMenu -->

	<div class="content">
		<img src="voImg/menu.png" class="toggleMenu">
		<div class="bigTitle">Accueil</div>
		<!-- 1ère division de la page -->
		<div class="bigContent">

			<!-- Nombres de membres -->
			<div class="card">
				<div class="cardTitle">
					<span>Membres</span>
					<img src="voImg/membres.png" alt="">
				</div>
				<div class="cardNumber" id="totalMember"><?=$nbMbr?></div>
				<div class="cardPercentMonth">+<?=$nbMbrMonth?> <span>ce mois</span></div>
			</div>

			<!-- Argent restant -->
			<div class="card">
				<div class="cardTitle">
					<span>Monétaire</span>
					<img src="voImg/money.png" alt="">
				</div>
				<div class="cardNumber"><?=$solde?>€</div>
				<div class="cardPercentMonth <?php if ( $exSolde < 0 ) { echo "min"; }?>"><?=$exSolde?>€ <span>ce mois</span></div>
			</div>

			<!-- Nombre de rassemblement -->
			<div class="card">
				<div class="cardTitle">
					<span>Rassemblement</span>
					<img src="voImg/rasso.png" alt="">
				</div>
				<div class="cardNumber" id="nbRasso"><?=$nbRass?></div>
				<div class="cardPercentMonth <?php if($backMonthRass < 0){ echo "min"; }?>"><?=$backMonthRass?> <span>ce mois</span></div>
			</div>

			<!-- Nombre de sortie -->
			<div class="card">
				<div class="cardTitle">
					<span>Sortie</span>
					<img src="voImg/sortie.png" alt="">
				</div>
				<div class="cardNumber" id="nbSrt"><?=$nbSort?></div>
				<div class="cardPercentMonth <?php if($backMonthSrt < 0){ echo "min"; }?>"><?=$backMonthSrt?> <span>ce mois</span></div>
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

		<!-- 3éme division -->
		<div class="bigContent">
			<?php 
				$circle = "border-left-color: transparent;";
			?>

			<!-- Graphique transaction -->
			<div class="card full">
				<div class="cardTitle">
					<span>Argent sur l'année</span>
					<div class="cardSubTitle"><?=$currentYear?></div>
				</div>
				<div class="cardGraphCont">
					<?php while ($res = $graphContent->fetch()) { 

						// On défini le pourcentage
						if ($maxTransac != 0) {
							$perc = ($res['revenu'] * 100)/$maxTransac;
							$perc2 = ($res['depense'] * 100)/$maxTransac;
						}
						

						// On définit le mois a afficher
						$month = giveMonth($res['month']);
						?>
					
					<div class="cardGraphKeep">
						<div class="cardGraphFlex">
							<div class="cardGraphInfo">
								<span class="incomeTxt"><?=$res['revenu']?>€</span>
								<span class="outcomeTxt"><?=$res['depense']?>€</span>
							</div>
							<div class="cardGraphBack">
								<div class="cardGraph" style="height: <?=$perc?>%;"></div>
							</div>
							<div class="cardGraphBack">
								<div class="cardGraph outcome" style="height: <?=$perc2?>%;"></div>
							</div>
						</div>
						<span class="cardGraphMonth"><?=$month?></span>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php 
				$circle = "border-bottom-color: transparent; border-left-color: transparent;";
			?>

		</div>
		<!-- Division 4 -->
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
			<div class="card full">
				<!-- Evènement a venir -->
				<div class="cardTitle">
					<span>Evènement a venir</span>
				</div>
				<?php 

				while ($res = $nextEvents->fetch()) { 
					// On récupère les genres d'évènement
		$evReq = $bdd->prepare('SELECT * FROM event_gender order by CASE WHEN idGenre = :idGr THEN 0 ELSE idGenre END, libelle');
		$evReq->bindParam("idGr",$res['idGenre'], PDO::PARAM_INT);
		$evReq->execute();
		$option = "";
		// On insère tout les genre dans une variable
		while ($resul = $evReq->fetch()) {
			
			$option .= '<option value="'.$resul['idGenre'].'">'.$resul['libelle'].'</option>';
		}
					// On récupère la liste des présences
					$req = $bdd->prepare('SELECT * FROM eventpres WHERE idEvent = :id');
					$req->execute(['id' => $res['id']]);
					$date = eventDate($res['date']);
					$hour = hourConvert($res['heure']);

					// On construit le titre de l'évènement
					// On récupère le genre de l'évènement
					$typeSel1 = "";
					$typeSel2 = "";
					if ($res['type'] == 0) {
						$title = "Rassemblement ".$res['libelle'];
						$bk = "mediumpurple";
						$typeSel1 = "selected";
					} else {
						$bk = "indianred";
						$title = "Sortie ".$res['libelle'];
						$typeSel2 = "selected";
					}
					
					?>
				<div class="eventLine">
					<div class="eventLineSub">
					<div class="eventData" style="background: <?=$bk?>;">
						<div class="eventLineDate">
							<?=$date?>
						</div>
						<div class="eventLineHour">
							<?=$hour?>
						</div>
					</div>
					<div class="eventLineInfo">
						<div class="eventLineTitle">
							<?=$title?>
						</div>
						<div class="eventLineLieu">
							<?=$res['lieu']?> <br>
							<?=$res['lieuA']?>
						</div>
					</div>
					</div>
					<div class="profileOption absRight">
						<img src="voImg/vdots.png" class="optionButt" alt="option">
						<div class="optionShowSlide" id="eventLine-<?=$res['id']?>">
							<button class="closeOption"><img src="voImg/close.png" alt=""></button>
							<div class="modifOp">Modifier</div>
							<div class="delEvent">Supprimer</div>
							<div class="presOp">Présence</div>
						</div>
					</div>
				</div>
				
				<div class="adminTab adminTabPresList" id="presAct<?=$res['id']?>">
					<div class="adminTabTitle">
						Présence du <?=$date?>
					</div>
					<button class="closeTable"><img src="voImg/close.png" alt=""></button>
					<?php
					while ($resu = $req->fetch()) {
						// On défini l'image du bouton selon la présence déjà insérer
						if ($resu['pres'] == 0) {
							$presButt = "absence";
						} 
						else {
							$presButt = "presence";
						}
						// On récupère l'utilisateur 
						$r = $bdd->prepare('SELECT * FROM users WHERE id = :id');
						$r->execute(['id' => $resu['idUsers']]);
						$user = $r->fetch();
						?>
					<div class="presLine">
						<div class="presLinePic"><img src="voMbrs/mbr<?=$user['id']?>/<?=$user['pic']?>" alt="Utilisateur"></div>
						<div class="presLineName"><?=$user['nom']?> <?=$user['prenom']?><span><?=$user['surnom']?></span></div>
						<img src="voImg/<?=$presButt?>.png" alt="Etat" id="<?=$res['id']?>-<?=$user['id']?>" class="etatPres">
					</div>
				<?php } ?>
				</div>
				<form class="adminTab modifyEvtTab" method="POST" id="modifyEv<?=$res['id']?>">
					<input type="hidden" name="formType" value="modifyEvt">
					<input type="hidden" name="idEv" value="<?=$res['id']?>">
					<div class="adminTabTitle">
						Modification d'évènement
					</div>
					<div class="adminTabScroll">
						<div class="adminTabSection">
							<div class="adminTabSubTitle">
								Obligatoire
							</div>
								<div class="sectionPart">
									<div class="adminTabInput">
										<label for="">Genre <img src="voImg/add.png" alt="Ajout de genre" class="addGenderButt"></label>
										<select name="genre" required>
											<?=$option?>
										</select>
										
									</div>
									<div class="adminTabInput">
										<label for="">Type</label>
										<select name="type" required>
											<option value="0" <?=$typeSel1?>>Rassemblement</option>
											<option value="1" <?=$typeSel2?>>Sortie</option>
										</select>
									</div>
									<div class="adminTabInput">
										<label for="">Date</label>
										<input type="date" value="<?=$res['date']?>" name="date">
									</div>
									<div class="adminTabInput">
										<label for="">Heure du rendez-vous</label>
										<input type="time" name="heure" value="<?=$res['heure']?>" required>
									</div>
									<div class="adminTabInput">
										<label for="">Lieu du rendez-vous</label>
										<input type="text" name="lieu" value="<?=$res['lieu']?>" required>
									</div>
								</div>
						</div>
						<div class="adminTabSection">
							<div class="adminTabSubTitle">
								Optionnel
							</div>
							<div class="sectionPart">
								<div class="adminTabInput">
									<label for="">Lieu d'arrivé</label>
									<input type="text" name="lieu2" value="<?=$res['lieuA']?>">
								</div>
								<div class="adminTabInput">
									<label for="">Cotisation</label>
									<input type="text" name="cotis" value="<?=$res['cotisation']?>">
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
			</div>
			</div>
		</div>
	</div>
	
</div>