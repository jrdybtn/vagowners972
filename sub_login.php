<!-- Formulaire de connexion -->
	<form action="POST" class="loginForm" id="loginConn">
		<div class="connectTitle">
			<img src="voImg/logo.png" alt="Logo">
			Connexion
		</div>
		<div class="inputCont">
			<input type="text" id="pseudo" name="pseudo" placeholder="Pseudo..." required>
		</div>
		<div class="inputCont">
			<input type="password" id="mdp" name="mdp" placeholder="Mot de passe..." required> <img src="voImg/eye.png" alt="" class="eye">
		</div>
		<div class="checkCont">
			<input type="checkbox" class="remember" name="rem" id="rem">
			<label for="rem">Se souvenir de moi</label>
		</div>
		<button class="connect">Connexion</button>
		<div class="forget">Mot de passe oublié</div>
		<!-- Input caché de détection (traitement dans action.php) -->
		<input type="hidden" value="1" name="connectAct">
	</form>

	<!-- Formulaire de mot de passe oublié -->
	<form action="" method="POST" class="loginForm"  id="loginMdpo">
		<div class="connectTitle">
			<img src="voImg/logo.png" alt="Logo">
			Mot de passe oublié
		</div>
		<div class="inputConn">
			<input type="mail" name="mail" placeholder="Email..." required="required">
		</div>
		<button class="connect" name="mdpoBut">Valider</button>
			<div class="con">Connexion</div>
			<!-- Input caché de détection (traitement dans action.php) -->
		<input type="hidden" value="1" name="mdpoTr">
	</form>