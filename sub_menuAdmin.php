	<div class="sideMenu">
		<div class="sideLogo">
			<img src="voImg/logo.png" alt="Logo">
			<div class="sideImgTitle sideTxt">Vag Owners 972</div>
		</div>
		<div class="sideAddDiv">
			<img src="voImg/addMbr.png" alt="Ajout membre" class="addMbrs" id="sideAddMbr" title="Ajout de membre">
			<img src="voImg/addEv.png" alt="Ajout évènement" class="addEvent" id="sideAddEv" title="Ajout d'évènement">
			<img src="voImg/addMoney.png" alt="Ajout monétaire" class="addEvent" id="sideAddMoney" title="Ajout monétaire">
		</div>
		<div class="sideNavLinks">
			<div class="sideLink" <?=$link1?> onclick="document.location.href='?pg=1.1'"><img src="voImg/home.png" alt="Accueil"><span class="sideTxt">Accueil</span></div>
			<div class="sideLink" <?=$link2?> onclick="document.location.href='?pg=1.11'"><img src="voImg/membres.png" alt="Membres"><span class="sideTxt">Membres</span></div>
			<div class="sideLink"><img src="voImg/event.png" alt="Evènements"><span class="sideTxt">Evènements</span></div>
			<div class="sideLink"><img src="voImg/money.png" alt="Argent"><span class="sideTxt">Monétaire</span></div>
			<div class="sideLink"><img src="voImg/notif.png" alt="Notifications"><span class="sideTxt">Notifications</span></div>
		</div>
		<div class="sideProfile">
			<div class="profilePic">
				<img src="voMbrs/mbr1/pic.jpg" alt="Photo">
			</div>
			<div class="profileName sideTxt">
				<div class="profileNom"><?=$_SESSION['voConnect']['nom']?> <?=$_SESSION['voConnect']['prenom']?></div>
				<div class="profileMail"><?=$_SESSION['voConnect']['mail']?></div>
			</div>
			<div class="profileOption sideTxt">
				<img src="voImg/vdots.png" class="optionButt" alt="option">
				<div class="optionShow">
					<div>Mon compte</div>
					<div id="disconect">Déconnexion</div>
				</div>
			</div>
		</div>
	</div>