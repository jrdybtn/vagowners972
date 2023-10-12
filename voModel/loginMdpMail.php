<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
	body{
		font-family: 'Inter', sans-serif;
		width: 100%;
		margin: auto;
		height: 100%;
		background: #11121e;
		padding: 10px;
		display: flex;
		color: white;
		flex-direction: column;
	}

	.title
	{
		font-weight: bold;
		font-size: 25px;
		display: flex;
		align-items: center;
		margin-bottom: 30px;
	}

	.title span
	{
		letter-spacing: 2px;
	}

	.title img 
	{
		height: 4em;
	}

	.name
	{
		margin-left: 70px;
		letter-spacing: 1px;
		margin-bottom: 30px;
	}

	.content
	{
		margin-left: 50px;
		letter-spacing: 1px;
		line-height: 25px;
	}

	.infos
	{
		margin-left: 50px;
	}

	.right
	{
		float: right;
		margin-top: 50px;
	}

</style>
<body>
	<div class="title">
		<img src="../voImg/logo.png" alt="Logo">
		<span>Vag Owners 972</span>
	</div>
	<div class="name">Bienvenue au <i>Vag Owners 972</i>, <?=$civ?> <?=$nom?> <?=$prenom?> !</div>
	<div class="content">Nous vous souhaitons la bienvenue chez nous !<br>Pour le bon fonctionnement de notre club, nous vous avons créer un compte sur notre site web !<br>
		Ce dernier vous permettra, entre autre, de connaître vos statistique au sein du club, répondre présent aux différentes activitées proposer...<br><br>
		Pour vous connecter, nous vous fournissons ci-dessous un pseudo et un mot de passe (<i>temporaire</i>).<br><br>
		<strong>Identifiant :</strong> <span class="infos"><i><?=$pseudo?></i></span><br>
		<strong>Mot de passe :</strong> <span class="infos"><i><?=$pass?></i></span><br><br>
		<i>Votre mot de passe est confidentiel, merci de ne pas le communiquer.</i><br><br>
		Lien du site : <a href="">www.vagowners972.com</a>.

		<div class="right">Cordialement,<br>
			<i>Vag Owners 972</i>
		</div>
	</div>
</body>
</html>