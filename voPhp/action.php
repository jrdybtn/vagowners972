<?php 
include('need.php');
// Traitement de la connexion
if (isset($_POST['connectAct'])) {
	$pseudo = $_POST['pseudo'];
	$mdp = $_POST['mdp'];
	// On recherche l'existence de cette personne
	$req = $bdd->prepare('SELECT COUNT(*) as tot FROM users WHERE pseudo = :pseudo');
	$reqT = $bdd->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
	$reqT->execute(['pseudo'=>$pseudo]);
	$req->execute(['pseudo'=>$pseudo]);
	$test = $req->fetch();
	if($test['tot'] > 0)		//Si l'utilisateur existe
	{
		$return = 1;
	}
	else {		//Si il n'existe pas
	$return = 0;
	}

	if ($return == 1) {		//Si l'utilisateur existe, on compare les mot de passe
	$test = $reqT->fetch();
		if (password_verify($mdp,$test['mdp'])) {
			$_SESSION['voConnect'] = [
				'id' =>$test['id'],
				'nom' =>$test['nom'],
				'prenom' =>$test['prenom'],
				'pseudo' =>$test['pseudo'],
				'mail' =>$test['mail'],
				'type' =>$test['type']
			];
			if (isset($_POST['rem'])) {
				setcookie('vagOwnersData[id]', $test['id'], time()+(3600*24)*30,'/','',true,true);
				setcookie('vagOwnersData[nom]', $test['nom'], time()+(3600*24)*30,'/','',true,true);
				setcookie('vagOwnersData[prenom]', $test['prenom'], time()+(3600*24)*30,'/','',true,true);
				setcookie('vagOwnersData[pseudo]', $test['pseudo'], time()+(3600*24)*30,'/','',true,true);
				setcookie('vagOwnersData[mail]', $test['mail'], time()+(3600*24)*30,'/','',true,true);
				setcookie('vagOwnersData[type]', $test['type'], time()+(3600*24)*30,'/','',true,true);
				setcookie('vagOwnersData[mdp]', $test['mdp'], time()+(3600*24)*30,'/','',true,true);
			}
			$return = 2;
		}
		else {
			$return = 3;
		}
	}
	echo $return;
}

// Au clic sur déconnexion
if (isset($_POST['disconnect'])) {
	session_destroy();
	setcookie('vagOwnersData[id]', '', time()-3600, '/', '', false, false);
	setcookie('vagOwnersData[nom]', '', time()-3600, '/', '', false, false);
	setcookie('vagOwnersData[prenom]', '', time()-3600, '/', '', false, false);
	setcookie('vagOwnersData[pseudo]', '', time()-3600, '/', '', false, false);
	setcookie('vagOwnersData[mail]', '', time()-3600, '/', '', false, false);
	setcookie('vagOwnersData[type]', '', time()-3600, '/', '', false, false);
	setcookie('vagOwnersData[mdp]', '', time()-3600, '/', '', false, false);
	echo 1;
}

// Clic sur valider (mot de passe oublié)
if (isset($_POST['mdpoTr'])) {
	$rq = $bdd->prepare('SELECT COUNT(*) as tot FROM users WHERE mail = :mail');
	$rq->execute(['mail'=>$_POST['mail']]);
	$tst = $rq->fetch();
	if ($tst['tot'] > 0) {
		$return = 0;
		$i = 0;
		$code = "";
		while ($i < 5) {
			$code .= rand(0,9);
			$i++;
		}
		$rq = $bdd->prepare('UPDATE users SET mdpo = :code WHERE mail = :mail');
		if($rq->execute(['code'=>$code,'mail'=>$_POST['mail']])){

		
	}
	}
	else {  //Si le compte n'est pas trouvé	
	$return = 1;
	}
	echo $return;
}

// Formulaire d'ajout, modification et suppression
if (isset($_POST['formType'])) {
	
	// Ajout de genre d'évènement
	if ($_POST['formType'] == "addGend") {
		// On vérifie que le genre n'existe pas 
		$query = $bdd->prepare('SELECT COUNT(*) as total FROM event_gender WHERE libelle = :lib');
		$query->execute(['lib'=>$_POST['libelle']]);
		$query = $query->fetch();
		// Si on détecte un genre correspondant
		if ($query['total'] > 0) {
			$respond = 0;
		}
		// Sinon
		else {
			// On récupère le nouvel ID du genre
			$req = $bdd->query('SELECT MAX(idGenre) as maxid FROM event_gender');
			$req = $req->fetch();
			$newid = $req['maxid'] + 1;
			// On récupère la photo lié au genre
			$tmpName = $_FILES['logo']['tmp_name'];
			$name = $_FILES['logo']['name'];
			$size = $_FILES['logo']['size'];
			$ext = explode(".", $name)[1];
			$name = $newid.'.'.$ext;
			$query = $bdd->prepare('INSERT INTO event_gender (libelle, img) VALUES(:lib, :link)');

			// Si genre bien ajouter dans la BDD
			if($query->execute(['lib'=>$_POST['libelle'], 'link'=>$name]))
			{
				$respond = 1;
				// Si la photo n'est pas ajouter
				if(!move_uploaded_file($tmpName, '../voGender/'.$name)) {
					$respond = 2;
				}

			}
		}
		echo $respond;
	}

	// Ajout d'évènement
	if ($_POST['formType'] == "addEvt") {
		// On vérifie qu'aucun évènement avec la même date et heure existe
		$req = $bdd->prepare('SELECT COUNT(*) as total FROM events WHERE date = :dt AND heure = :hr');
		$req->execute(['dt'=>$_POST['date'],'hr'=>$_POST['heure']]);
		$req = $req->fetch();
		$tst = $req['total'];

		// Si un évènement trouver
		if ($tst>0) {
			$respond = 1;
		}

		// Sinon
		else {
		$query = $bdd->prepare('INSERT INTO events (idGenre, type, heure, date, lieu, lieuA, cotisation) VALUES(:idG, :type, :hr, :dt, :lieu, :lieuA, :cotis)');

		// Si l'ajout a bien été exécuté
		if ($query->execute(['idG'=>$_POST['genre'], 'type'=>$_POST['type'],'hr'=>$_POST['heure'],'dt'=>$_POST['date'],'lieu'=>$_POST['lieu'],'lieuA'=>$_POST['lieu2'], 'cotis' => $_POST['cotis'] ]))
		{
			$newId = $bdd->lastInsertId();
			// Si c'est un rasso qui a été ajouté
			if ($_POST['type'] == 0) {
				$respond = 1.1;
			}

			// Si c'est une sortie qui a été ajouté
			else 
			{
				$respond = 1.2;
		 	}

		 	// On crée la liste des présence
		 	$query = $bdd->prepare('SELECT * FROM users WHERE dateE <= :date');

		 	$query->execute(['date' => $_POST['date']]);

		 	// On récupère l'ID du dernier évènement ajouter
		 	

		 	// On fait une boucle sur les users trouvé
		 	while ($res = $query->fetch()) {
		 		
		 		// On insère l'utilisateur a la liste
		 		$req = $bdd->prepare('INSERT INTO eventpres (idEvent, idUsers, pres) VALUES(:idEv, :idUs, "0")');
		 		$req->execute(['idEv' => $newId, 'idUs' => $res['id']]);

		 		// On met a jour son pourcentage de présence

		 		updateAllPres($res['id'], 2, $_POST['date'], $bdd);
		 	}
		}

		// Si la requête n'a pas pu être exécuté
		else {
			$respond = 0;
		}
		}
		
		echo $respond;
	}

	// Ajout de membre
	if ($_POST['formType'] == "addMbr") 
	{
		// On vérifie d'abord que ce membre n'existe pas grâce a son e-mail ou son numéro
		$query = $bdd->prepare('SELECT COUNT(*) as total FROM users WHERE mail = :mail OR phone = :phone');
		$query->execute(['mail'=>$_POST['mail'], 'phone'=>$_POST['phone']]);
		$query =  $query->fetch();
		$test = $query['total'];
		// Si un membre est trouvé
		if ($test > 0) {
			$respond = 0;
		}
		// Si on ne trouve aucun membre avec ce numéro et/ou cette adresse mail
		else
		{
			// On crée le pseudo a partir du prénom et du nom
			$pseudo = strtolower($_POST['prenom']).''.$_POST['nom']['0'];
			// On crée une chaine de caractère contenant tout les caractère pour créer un mot de passe
			$mdpSelect = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			// On crée un mot de passe de façon aléatoire sur 6 caractère
			$i = 0;
			$long = 6;
			$pass = '';
			$caracLen = strlen($mdpSelect) - 1;
			while($i < 6) {
				$num = rand(0, $caracLen);
				$pass .= $mdpSelect[$num];
				$i++;
			}
			$passW = password_hash($pass, PASSWORD_DEFAULT);
			$addrs = addslashes($_POST['adrss']);
			$addrs2 = addslashes($_POST['adrss2']);
			if (empty($_POST['dateI'])) {
				$dateE = date("Y-m-d");
			} else {
				$dateE = $_POST['dateI'];
			}

			// On récupère le prochaine ID users
			$req = $bdd->query('SELECT MAX(id) as total FROM users');
			$req = $req->fetch();
			$newId = $req['total'] + 1;

			// Gestion de la photo
			$tmpName = $_FILES['pic']['tmp_name'];
			$name = $_FILES['pic']['name'];
			$size = $_FILES['pic']['size'];
			$ext = explode(".", $name)[1];
			$name = 'pic.'.$ext;
			$rep = "../voMbrs/mbr".$newId;
			// On vérifie que le répertoire lié a cet utilisateur existe ou non
			if(!file_exists($rep)){
				// On crée le dossier
				mkdir($rep);
			}
			move_uploaded_file($tmpName, '../voMbrs/mbr'.$newId.'/'.$name);
			$query = $bdd->prepare('INSERT INTO users (id,type,nom,prenom,civ,dateN,addrs,addrs2,cp,ville,mail,surnom,tailleV,color,pseudo,mdp,dateE,phone,pic,car) VALUES(:id,:type,:nom,:prenom,:civ,:dateN,:addrs,:addrs2,:cp,:ville,:mail,:surnom,:tailleV,:color,:pseudo,:mdp,:dateE,:phone,:pic,:car)');

			$query->execute(['id'=>$newId,'type'=>$_POST['type'],'nom'=>$_POST['nom'],'prenom'=>$_POST['prenom'],'civ'=>$_POST['civ'],'dateN'=>$_POST['dateN'],'addrs'=>$addrs,'addrs2'=>$addrs2,'cp'=>$_POST['cp'],'ville'=>$_POST['ville'],'mail'=>$_POST['mail'],'surnom'=>$_POST['surnom'],'tailleV'=>$_POST['taille'],'color'=>$_POST['color'],'pseudo'=>$pseudo,'mdp'=>$passW,'dateE'=>$dateE,'phone'=>$_POST['phone'],'pic'=>$name, 'car' => $_POST['car']]);
			if ($query) {
				$respond = 1;

				// On envoie un mail avec le login et le mot de passe
			$nom = $_POST['nom'];
			$prenom = $_POST['prenom'];
			if ($_POST['civ'] == "Autres") {
				$civ = "";
			}
			else
			{
				$civ = $_POST['civ'];
			}

			// On enregistre le fichier loginMdpMail.php dans une variable
			ob_start();
			include "../voModel/loginMdpMail.php";
			$content = ob_get_contents();
			ob_get_clean();

			$entetes="From: Vag Owners 972 <jordy.btn@gmail.com> \r \n";
  			$entetes.="Content-Type: text/html; charset=utf-8 \n";
  			$entetes.="X-Priority : 3\n";
  			if(mail($_POST['mail'], "Bienvenue au VAG Owners 972 !", $content, $entetes)){
  				$respond = 1;
  			}
  			else {
  				$respond = 2;
  			}

  			// On ajoute le membre au évènement ultérieur a sa date d'inscription
  			$req = $bdd->prepare('SELECT * FROM events WHERE date >= :date');
  			$req->execute(['date' => $dateE]);

  			// On fait un boucle pour ajouter le membre a ces évènements
  			while ($res = $req->fetch()) {
  				$r = $bdd->prepare('INSERT INTO eventpres (idEvent, idUsers, pres) VALUES(:idE, :idU, "0")');
  				$r->execute(['idE' => $res['id'],'idU' => $newId]);
  			}
			}

		}
		echo $respond;
	}

	// Gestion des présences
	if ($_POST['formType'] == "togglePres") {
		// On récupère la ligne dans la liste des présence correspondant a l'id UTILISATEUR
		$query = $bdd->prepare('SELECT * FROM eventpres WHERE idUsers = :id AND idEvent = :idE');
		$query->execute(['id' => $_POST['id'],'idE' => $_POST['idE']]);
		$res = $query->fetch();
		// On agit selon le chiffre de présence (0 = abs, 1 = pres)
		if ($res['pres'] == 0) {
			$pres = 1;
		}
		else {
			$pres = 0;
		}
		// Si la req est bien exécuté, la valeur retourner est la valeur de la présence
		$req = $bdd->prepare('UPDATE eventpres SET pres = :pres WHERE idUsers = :idU AND idEvent = :idE');
		if($req->execute(['pres' => $pres,'idU' => $_POST['id'],'idE' => $_POST['idE']])) {
			$return = $pres;
		}
		// Sinnon on retourne 2 (erreur)
		else {
			$return = 2;
		}
		// On recalcule le pourcentage de présence du membre
		$re = $bdd->prepare('SELECT COUNT(*) as total FROM eventpres WHERE idUsers = :id');
		$re->execute(['id' => $_POST['id']]);
		$re = $re->fetch();

		// Total d'évènement ou le membre aurait pu participer
		$total = $re['total'];

		// On compte le nombre de rasso ou le membre a été présent
		$re = $bdd->prepare('SELECT COUNT(*) as total FROM eventpres WHERE idUsers = :id AND pres = 1');
		$re->execute(['id' => $_POST['id']]);
		$re = $re->fetch();

		// Nombre d'évènement présent
		$presence = $re['total'];
		$percent = percentPres($presence, $total);
		
		// On met a jour l'utilisateur
		$go = $bdd->prepare('UPDATE users SET pres = :pres WHERE id = :id');
		$go->execute(['pres' => $percent, 'id' => $_POST['id']]);

		// On met a jour le pourcentage de présence du mois

		// On récupère la dernière présence ayant été ajouter
		$query = $bdd->query('SELECT * FROM eventpres ORDER BY id DESC');
		$query = $query->fetch();
		$lastEvent = $query['idEvent'];
		// On récupère l'évènement lié a celle-ci
		$req = $bdd->prepare('SELECT MONTH(date) as month, YEAR(date) as year FROM events WHERE id = :id');
		$req->execute(['id' => $lastEvent]);
		$req = $req->fetch();
		$month = $req['month'];
		$year = $req['year'];

		// On sélectionne tout les évènements fait dans le mois et l'année correspondante
		$re = $bdd->prepare('SELECT id FROM events WHERE MONTH(date) = :month AND YEAR(date) = :year');
		$re->execute(['month' => $month, 'year' => $year]);
		// On fait une boucle pour effectuer la mise a jour du pourcentage de présence
		$total = 0;
		$pres = 0;
		while ($res = $re->fetch()) {

			$query = $bdd->prepare('SELECT COUNT(*) as tot FROM eventpres WHERE idEvent = :id');
			$query->execute(['id' => $res['id']]);
			$res1 = $query->fetch();

			$req = $bdd->prepare('SELECT COUNT(*) as tot FROM eventpres WHERE idEvent = :id AND pres = 1');
			$req->execute(['id' => $res['id']]);
			$res2 = $req->fetch();
			$total += $res1['tot'];
			$pres += $res2['tot'];
		}
		// On calcul le pourcentage de présence
		$percent = percentPres($pres, $total);
		// On vérifie que le mois concerné est déjà dans la base de données
		$req = $bdd->prepare('SELECT COUNT(*) as tot FROM monthrec WHERE month = :month AND year = :year');
		$req->execute(['month' => $month,'year' => $year]);
		$res = $req->fetch();
		if ($res['tot'] == 0) {
			$insert = $bdd->prepare('INSERT INTO monthrec (month,year,pres) VALUES(:month, :year, :pres)');
			$insert->execute(['month' => $month, 'year' => $year, 'pres' => $percent]);
		}
		else
		{
			$update = $bdd->prepare('UPDATE monthrec SET pres = :pres WHERE month = :month AND year = :year');
			$update->execute(['pres' => $percent, 'month' => $month, 'year' => $year]);
		}

		echo $return;
	}

	// Supression d'évènement
	if ($_POST['formType'] == 'delEvent') {
		$query = $bdd->prepare('DELETE FROM events WHERE id = :id');

		// On récupère la date de l'évènement a supprimer
		$dateDel = $bdd->prepare('SELECT date FROM events WHERE id = :id');
		$dateDel->execute(['id' => $_POST['id']]);
		$delDate = $dateDel->fetch();

		if ($query->execute(['id' => $_POST['id']])) {
			$return = 1;
			// On sélectionne tout les membres
			$req = $bdd->query('SELECT * FROM users');
			while ( $res = $req->fetch() ) {
				updateAllPres($res['id'], 2, $delDate['date'], $bdd);
			}
		}
		
		else
		{
			$return = 0;
		}
		echo $return;
	}

	// Supression de membre
	if ($_POST['formType'] == 'delMember') 
	{
		

		// On récupère les infos du membres pour les ajouter a une liste de membres supprimer
		$query = $bdd->prepare('SELECT * FROM users WHERE id = :id');
		$query->execute(['id' => $_POST['id']]);
		$user = $query->fetch();

		// On ajout le membre a la liste noire
		$query = $bdd->prepare('INSERT INTO userdel (nom, prenom, phone, mail, pres, obs) VALUES(:nom, :prenom, :phone, :mail, :pres, :obs) ');
		$query->execute(['nom' => $user['nom'], 'prenom' => $user['prenom'], 'phone' => $user['phone'], 'mail' => $user['mail'], 'pres' => $user['pres'], 'obs' => $_POST['obs']]);

		$query = $bdd->prepare('DELETE FROM users WHERE id = :id');
		if ($query->execute(['id' => $_POST['id']])) 
		{
			$return = 1;

			if(unlink('../voMbrs/mbr'.$_POST['id'].'/'.$user['pic']))
			{
				rmdir('../voMbrs/mbr'.$_POST['id']);
			}
		}

		else
		{
			$return = 0;
		}
		echo $return;
	}

	// Suppression de tout les membres
	if ($_POST['formType'] == "delAllMember") {
		$query = $bdd->prepare('DELETE FROM users WHERE id != :id');
		if ($query->execute(['id' => $_POST['id']])) {
			$return = 1;
		}
		else 
		{
			$return = 0;
		}

		echo $return;
	}

	// Suppression d'une sélection de membres
	if ($_POST['formType'] == "delSelectMember") 
	{
		$tab = $_POST['allId'];
		$length = count($tab) - 1;
		$i = 0;
		while ($i <= $length) 
		{
			$query = $bdd->prepare('DELETE FROM users WHERE id = :id');
			if ($query->execute(['id' => $tab[$i]])) 
			{
				$return = 1;
			}
			
			$i++;
		}
		echo $return;
	}

	// Modification de membres
	if ($_POST['formType'] == 'modifyMbr') 
	{
		$query = $bdd->prepare('UPDATE users SET type = :type, nom = :nom, prenom = :prenom, civ = :civ, dateN = :dateN, addrs = :addrs, addrs2 = :addrs2, cp = :cp, ville = :ville, mail = :mail, surnom = :surnom, tailleV = :tailleV, color = :color, pseudo = :pseudo, car = :car, dateE = :dateE, phone = :phone WHERE id = :id');
		if ($query->execute(['type' => $_POST['type'], 'nom' => $_POST['nom'], 'prenom' => $_POST['prenom'], 'civ' => $_POST['civ'], 'dateN' => $_POST['dateN'], 'addrs' => $_POST['adrss'], 'addrs2' => $_POST['adrss2'], 'cp' => $_POST['cp'], 'ville' => $_POST['ville'], 'mail' => $_POST['mail'], 'surnom' => $_POST['surnom'], 'tailleV' => $_POST['taille'], 'color' => $_POST['color'], 'pseudo' => $_POST['pseudo'], 'car' => $_POST['car'], 'dateE' => $_POST['dateI'], 'phone' => $_POST['phone'], 'id' => $_POST['id'] ]) ) 
		{
			$return = 1;
		}
		else
		{
			$return = 0;
		}
		echo $return;
	}

	// Modification d'évènements
	if ($_POST['formType'] =='modifyEvt') 
	{
		$query = $bdd->prepare('UPDATE events SET idGenre = :idGr, type = :type, date = :date, heure = :heure, lieu = :lieu, lieuA = :lieuA, cotisation = :cotis WHERE id = :id');
		if ($query->execute(['idGr' => $_POST['genre'], 'type' => $_POST['type'], 'date' => $_POST['date'], 'heure' => $_POST['heure'],'lieu' => $_POST['lieu'], 'lieuA' => $_POST['lieu2'], 'id' => $_POST['idEv'], 'cotis' => $_POST['cotis'] ])) 
		{
			$return = 1;
		}
		else
		{
			$return = 0;
		}
		echo $return;
	}

	// Ajout de transaction
	if ($_POST['formType'] == "addMoney") 
	{
		
		// On vérifie les erreurs éventuelles
		$membre = $_POST['member'];
		$event = $_POST['event'];
		$type = $_POST['type'];

		if ( $type != 2 && !empty( $event ) && empty( $membre ) || $type != 2 && empty( $event ) && !empty( $membre ) || $type == 2 && empty( $event ) && empty( $membre ) || $type == 2 && !empty( $event ) ) 
		{

			if ( $type !== 2 && !empty( $event ) && empty( $membre ) || $type !== 2 && empty( $event ) && !empty( $membre ) ) 
			{
				$return = 0.1;
			}
			else if ( $type == 2 && empty( $event ) && empty( $membre ) ) 
			{
				$return = 0.2;
			}
			else if ( $type == 2 && !empty( $event ) )
			{
				$return = 0.3;
			}
			
		}
		else if ( $type == 2 ) 
		{
			// On vérifie qu'aucune cotisation annuelle n'a été réglé pour l'année entrée
			$req = $bdd->prepare('SELECT COUNT(*) as total FROM transaction WHERE idUser = :us AND type = 2 AND YEAR( date ) = YEAR( :date )');
			$req->execute(['us' => $_POST['member'], 'date' => $_POST['date']]);
			$res = $req->fetch();

			// Si une cotisation a été trouvé
			if ( $res['total'] > 0 ) {
				$return = 0.4;
			}
			else {
				$return = 0;
			}
		}
		else
		{
			$return = 0;
		}

		// Si les bonnes conditions sont rempli
		if ($return == 0) {
			$query = $bdd->prepare('INSERT INTO transaction (montant, date, objet, type, idEvent, idUser) VALUES(:mont, :date, :obj, :type, :idEv, :idUs)');

			// Si la requête est bien exécuté
			if ($query->execute(['mont' => $_POST['montant'], 'date' => $_POST['date'], 'obj' => $_POST['libelle'], 'type' => $_POST['type'], 'idEv' => $_POST['event'], 'idUs' => $_POST['member']])) 
			{
				// On vérifie si le recap mensuel est déjà défini dans la base de donnée
				$req = $bdd->prepare('SELECT COUNT(*) as total FROM monthrec WHERE month = MONTH( :date ) AND year = YEAR( :date2 )');
				$req->execute([ 'date' => $_POST['date'], 'date2' => $_POST['date'] ]);
				$res = $req->fetch();
				$res = $res['total'];

				// Si le recap est bien défini
				if ( $res > 0 ) {
					if ( $_POST['type'] == 0 || $_POST['type'] == 2 ) {
						$update = $bdd->prepare('UPDATE monthrec SET revenu = revenu + :montant WHERE month = MONTH( :date ) AND year = YEAR( :date2 ) ');
					}
					else {
						$update = $bdd->prepare('UPDATE monthrec SET depense = depense + :montant WHERE month = MONTH( :date ) AND year = YEAR( :date2 )');
					}

					if ( $update->execute( [ 'montant' => $_POST['montant'], 'date' => $_POST['date'], 'date2' => $_POST['date'] ] ) )
					{
						$return = 1;
					}
					else {
						$return = 2;
					}
					
				}
				else 
				{
					if ( $_POST['type'] == 0 || $_POST['type'] == 2 )
					{
						$create = $bdd->prepare('INSERT INTO monthrec ( month,year,revenu ) VALUES( MONTH( :month ),YEAR( :year ),:revenu )');	
					}
					else 
					{
						$create = $bdd->prepare('INSERT INTO monthrec ( month,year,depense ) VALUES( MONTH( :month ),YEAR( :year ),:revenu )');
					}

					if ( $create->execute(['month' => $_POST['date'], 'year' => $_POST['date'],'revenu' => $_POST['montant'] ]) )
					{
						$return = 1;
					}
					else 
					{
						$return = 2;
					}
				}
			}
			
			
		}
		echo $return;
	}

	// Filtre membres
	if ($_POST['formType'] == "filter") 
	{
		$return = 0;
		$query = "";
		$test = 1;
		// 1 filtre

		// Nom
		if ( !empty($_POST['nom']) ) 
		{
			$return = 1;

			// 2 filtres

			// Nom, prénom
			if ( !empty($_POST['prenom']) ) 
			{
				// 3 filtres

				// Nom, prénom, civilité
				if ( !empty($_POST['civ']) ) 
				{
					// 4 filtres

					// Nom, prénom, civilité, présence
					if (!empty($_POST['pres'])) 
					{
						//  5 filtres

						// Nom, prénom, civilité, présence, mois
						if(!empty( $_POST['month'] )) 
						{
							//  6 filtres

							// Nom, prénom, civilité, présence, mois, cp
							if (!empty( $_POST['cp'] )) 
							{
								// 7 filtres

								// Nom, prénom, civilité, présence, mois, cp, voiture
								if (!empty( $_POST['car'] )) 
								{
									$month = explode("-", $_POST['month'])[1];
									$year = explode("-", $_POST['month'])[0];

									if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC, pres ASC, cp ASC, car ASC');
									}
									elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom ASC, pres ASC, cp ASC, car ASC');
									}
									elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC, pres ASC, cp ASC, car ASC');
									}
									elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC, pres DESC, cp ASC, car ASC');
									}
									elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC, pres ASC, cp ASC, car ASC');
									}
									elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC, pres DESC, cp ASC, car ASC');
									}
									elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC, pres DESC, cp ASC, car ASC');
									}
									$query->execute([ 'civ' => $_POST['civ'],'month' => $month,'year' => $year ]);
								}
								else 
								{
									$month = explode("-", $_POST['month'])[1];
									$year = explode("-", $_POST['month'])[0];

									if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC, pres ASC, cp ASC');
									}
									elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom ASC, pres ASC, cp ASC');
									}
									elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC, pres ASC, cp ASC');
									}
									elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC, pres DESC, cp ASC');
									}
									elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC, pres ASC, cp ASC');
									}
									elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC, pres DESC, cp ASC');
									}
									elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
									{
										$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC, pres DESC, cp ASC');
									}
									$query->execute([ 'civ' => $_POST['civ'],'month' => $month,'year' => $year ]);
								}
							}

						else 
						{
							$month = explode("-", $_POST['month'])[1];
								$year = explode("-", $_POST['month'])[0];

								if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC, pres ASC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom ASC, pres ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC, pres ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC, pres DESC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC, pres ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC, pres DESC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC, pres DESC');
								}
								$query->execute([ 'civ' => $_POST['civ'],'month' => $month,'year' => $year ]);	
						}
						}

						else 
						{
							if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, prenom ASC, pres ASC');
							}
							elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, prenom ASC, pres DESC');
							}
							elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, prenom DESC, pres ASC');
							}
							elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, prenom ASC, pres ASC');
							}
							elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, prenom DESC, pres DESC');
							}
							elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, prenom DESC, pres ASC');
							}
							elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, prenom ASC, pres DESC');
							}
							elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, prenom DESC, pres DESC');
							}
							$query->execute(['civ' => $_POST['civ']]);
						}
					}

					// Nom,prénom,civilité,mois
					elseif (!empty($_POST['month'])) 
					{
						$month = explode("-", $_POST['month'])[1];
						$year = explode("-", $_POST['month'])[0];
						
						// Nom,prénom,civilité,mois,code postal
						if (!empty($_POST['cp'])) 
						{
							// Nom,prénom,civilité,mois,code postal,voiture
							if (!empty($_POST['car'])) 
							{
								if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC,prenom ASC, cp, car');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC,prenom DESC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC,prenom ASC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC,prenom DESC, cp, car');
								}
								$query->execute(['civ' => $_POST['civ'],'month' => $month,'year' => $year]);
							}
							else
							{
								if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC,prenom ASC, cp');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC,prenom DESC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC,prenom ASC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC,prenom DESC, cp');
								}
								$query->execute(['civ' => $_POST['civ'],'month' => $month,'year' => $year]);
							}
							
						}
						else
						{
								if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC,prenom ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC,prenom DESC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC,prenom ASC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC,prenom DESC');
								}
								$query->execute(['civ' => $_POST['civ'],'month' => $month,'year' => $year]);
						}
					}

					else 
					{
						if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, prenom ASC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, prenom DESC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, prenom ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, prenom DESC');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
				}

				// Nom, prénom, présence
				elseif ( !empty($_POST['pres']) ) 
				{
					// Nom, prénom, présence, mois
					if (!empty($_POST['month'])) 
					{
						$month = explode("-", $_POST['month'])[1];
						$year = explode("-", $_POST['month'])[0];
						
						// Nom, prénom, présence, mois, cp
						if (!empty($_POST['cp'])) 
						{
							// Nom, prénom, présence, mois, cp, car
							if (!empty($_POST['car'])) 
							{
											if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom ASC, pres ASC, cp, car');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom ASC, pres DESC, cp, car');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom DESC, pres ASC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom ASC, pres ASC, cp, car');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom DESC, pres DESC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom DESC, pres ASC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom ASC, pres DESC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom DESC, pres DESC, cp, car');
								}
								$query->execute(['month' => $month, 'year' => $year]);
							}
							else
							{
								if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom ASC, pres ASC, cp');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom ASC, pres DESC, cp');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom DESC, pres ASC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom ASC, pres ASC, cp');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom DESC, pres DESC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom DESC, pres ASC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom ASC, pres DESC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom DESC, pres DESC, cp');
								}
								$query->execute(['month' => $month, 'year' => $year]);
							}
						}
						else
						{
							if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom ASC, pres ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom ASC, pres DESC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom DESC, pres ASC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom ASC, pres ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, prenom DESC, pres DESC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom DESC, pres ASC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom ASC, pres DESC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, prenom DESC, pres DESC');
								}
								$query->execute(['month' => $month, 'year' => $year]);
						}
					}
					else
					{
							if ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom ASC, pres ASC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom ASC, pres DESC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom DESC, pres ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom ASC, pres ASC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom DESC, pres DESC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom DESC, pres ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom ASC, pres DESC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom DESC, pres DESC');
						}
						$query->execute();
					}
					
				}

				// Nom, prénom, mois
				elseif ( !empty($_POST['month']) ) 
				{
					$month = explode("-", $_POST['month'])[1];
					$year = explode("-", $_POST['month'])[0];

					if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom ASC');
					}
					elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, prenom DESC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, prenom DESC');
					}
					$query->execute(['month' => $month, 'year' => $year]);
				}

				// Nom, prénom, code postal
				elseif ( !empty($_POST['cp']) ) 
				{
					if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom ASC, cp ASC');
					}
					elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom DESC, cp ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom ASC, cp ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom DESC, cp ASC');
					}
					$query->execute();
				}

				// Nom, prénom, voiture
				elseif ( !empty($_POST['car']) ) 
				{
					if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom ASC, car ASC');
					}
					elseif ($_POST['nom'] == 1 && $_POST['prenom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom DESC, car ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom ASC, car ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['prenom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom DESC, car ASC');
					}
					$query->execute();
				}

				// Nom et prénom
				else
				{
					if ($_POST['nom'] == 1 && $_POST['prenom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom ASC');
					}
					elseif($_POST['nom'] == 2 && $_POST['prenom'] == 1){
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom ASC');
					}
					elseif($_POST['nom'] == 1 && $_POST['prenom'] == 2){
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, prenom DESC');
					}
					elseif($_POST['nom'] == 2 && $_POST['prenom'] == 2){
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, prenom DESC');
					}
					
					$query->execute();
				}
			}

			// Nom et civilité
			elseif( !empty($_POST['civ']) )
			{
				// Nom, civilité, présence
				if ( !empty($_POST['pres']) ) 
				{
					// Nom, civilité, présence, mois
					if (!empty($_POST['month'])) 
					{
						$month = explode("-", $_POST['month'])[1];
						$year = explode("-", $_POST['month'])[0];

						// Nom, civilité, présence, mois, cp
						if (!empty($_POST['cp'])) 
						{
							// Nom, civilité, présence, mois, cp, voiture
							if (!empty($_POST['car'])) 
							{
								if ($_POST['nom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, pres ASC, cp, car');
								}
								elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, pres DESC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, pres ASC, cp, car');
								}
								elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, pres DESC, cp, car');
								}
								$query->execute(['civ' => $_POST['civ'],'month' => $month, 'year' => $year]);
							}
							else
							{
								if ($_POST['nom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, pres ASC, cp');
								}
								elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, pres DESC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, pres ASC, cp');
								}
								elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, pres DESC, cp');
								}
								$query->execute(['civ' => $_POST['civ'],'month' => $month, 'year' => $year]);
							}
						}
						else
						{
							if ($_POST['nom'] == 1 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, pres ASC');
								}
								elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom ASC, pres DESC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, pres ASC');
								}
								elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) 
								{
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY nom DESC, pres DESC');
								}
								$query->execute(['civ' => $_POST['civ'],'month' => $month, 'year' => $year]);
						}
					}
					else
					{
						if ($_POST['nom'] == 1 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, pres ASC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, pres DESC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, pres ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, pres DESC');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
				}

				// Nom, civilité, mois
				elseif ( !empty($_POST['month']) ) 
				{
					$month = explode("-", $_POST['month'])[1];
					$year = explode("-", $_POST['month'])[0];

					// Nom, civilité, mois, code postal
					if (!empty($_POST['cp'])) 
					{

						// Nom, civilité, mois, code postal, voiture
						if (!empty($_POST['car'])) 
						{
							if ($_POST['nom'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, cp, car');
							}
							elseif ($_POST['nom'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, cp, car');
							}
							$query->execute(['civ' => $_POST['civ'],'month' => $month, 'year' => $year]);
						}
						else
						{
							if ($_POST['nom'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, cp');
							}
							elseif ($_POST['nom'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, cp');
							}
							$query->execute(['civ' => $_POST['civ'],'month' => $month, 'year' => $year]);
						}
					}
					else
					{
						if ($_POST['nom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC');
						}
						elseif ($_POST['nom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC');
						}
						$query->execute(['civ' => $_POST['civ'],'month' => $month, 'year' => $year]);
					}
				}

				// Nom, civilité, code postal
				elseif ( !empty($_POST['cp']) ) 
				{

					// Nom, civilité, code postal, voiture
					if (!empty($_POST['car'])) 
					{
						if ($_POST['nom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, cp ASC, car');
						}
						elseif ($_POST['nom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, cp ASC, car');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
					else
					{
						if ($_POST['nom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, cp ASC');
						}
						elseif ($_POST['nom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, cp ASC');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
				}

				// Nom, civilité, voiture
				elseif ( !empty($_POST['car']) ) 
				{
					if ($_POST['nom'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC, car ASC');
					}
					elseif ($_POST['nom'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC, car ASC');
					}
					$query->execute(['civ' => $_POST['civ']]);
				}

				// Nom et civilité
				else 
				{
					if ($_POST['nom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom ASC');
					}
					elseif ($_POST['nom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY nom DESC');
					}
					$query->execute(['civ' => $_POST['civ']]);
				}

			}

			// Nom, présence
			elseif( !empty($_POST['pres']) )
			{

				// Nom, présence, mois
				if ( !empty($_POST['month']) ) 
				{
					$month = explode("-", $_POST['month'])[1];
					$year = explode("-", $_POST['month'])[0];

					// Nom, présence, mois, code postal
					if (!empty($_POST['cp'])) 
					{
						// Nom, présence, mois, code postal, voiture
						if (!empty($_POST['car'])) 
						{
								if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, pres ASC,cp,car');
							}
							elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, pres DESC,cp,car');
							}
							elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, pres ASC,cp,car');
							}
							elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, pres DESC,cp,car');
							}
							$query->execute(['month' => $month, 'year' => $year]);
						}
						else
						{
								if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, pres ASC,cp');
							}
							elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, pres DESC,cp');
							}
							elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, pres ASC,cp');
							}
							elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, pres DESC,cp');
							}
							$query->execute(['month' => $month, 'year' => $year]);
						}
					}
					else
					{
						if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, pres ASC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, pres DESC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, pres ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, pres DESC');
						}
						$query->execute(['month' => $month, 'year' => $year]);
					}
					
				}

				// Nom, présence, code postal
				elseif ( !empty($_POST['cp']) ) 
				{
					// Nom, présence, code postal, voiture
					if (!empty($_POST['car'])) 
					{
						if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres ASC, cp ASC, car');
						}
						elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres DESC, cp ASC, car');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres ASC, cp ASC, car');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres DESC, cp ASC, car');
						}
						$query->execute();
					}
					else
					{
						if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres ASC, cp ASC');
						}
						elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres DESC, cp ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres ASC, cp ASC');
						}
						elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres DESC, cp ASC');
						}
						$query->execute();
					}
				}

				// Nom, présence, voiture
				elseif ( !empty($_POST['car']) ) 
				{
					if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres ASC, car ASC');
					}
					elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres DESC, car ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres ASC, car ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres DESC, car ASC');
					}
					$query->execute();
				}

				// Nom et présence
				else
				{
					if ($_POST['nom'] == 1 && $_POST['pres'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres ASC');
					}
					elseif ($_POST['nom'] == 1 && $_POST['pres'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, pres DESC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['pres'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres ASC');
					}
					elseif ($_POST['nom'] == 2 && $_POST['pres'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, pres DESC');
					}
					$query->execute();
				}
			}

			// Nom, mois
			elseif( !empty($_POST['month']) )
			{
				$month = explode("-", $_POST['month'])[1];
				$year = explode("-", $_POST['month'])[0];

				// Nom, mois, code postal
				if ( !empty($_POST['cp']) ) 
				{
					// Nom, mois, code postal, voiture
					if (!empty($_POST['car'])) 
					{
						if ($_POST['nom'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, cp ASC, car');
						}
						elseif ($_POST['nom'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, cp ASC, car');
						}
						$query->execute(['month' => $month, 'year' => $year]);
					}
					else
					{
						if ($_POST['nom'] == 1) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, cp ASC');
						}
						elseif ($_POST['nom'] == 2) {
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, cp ASC');
						}
						$query->execute(['month' => $month, 'year' => $year]);
					}
				}

				// Nom, mois, voiture
				elseif ( !empty($_POST['car']) ) 
				{
					if ($_POST['nom'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC, car ASC');
					}
					elseif ($_POST['nom'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC, car ASC');
					}
					$query->execute(['month' => $month, 'year' => $year]);
				}

				// Nom et mois
				else
				{
					if ($_POST['nom'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom ASC');
					}
					elseif ($_POST['nom'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY nom DESC');
					}
					$query->execute( ['month' => $month, 'year' => $year] );
				}
			}

			// Nom et code postal
			elseif( !empty($_POST['cp']) )
			{
				// Nom, code postal, voiture
				if (!empty($_POST['car'])) 
				{
						if ($_POST['nom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, cp, car');
					}
					elseif ($_POST['nom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, cp, car');
					}
					$query->execute();
				}
				else
				{
						if ($_POST['nom'] == 1) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, cp');
					}
					elseif ($_POST['nom'] == 2) {
						$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, cp');
					}
					$query->execute();
				}
			}

			// Nom et véhicule
			elseif( !empty($_POST['car']) )
			{
				if ($_POST['nom'] == 1) {
					$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC, car');
				}
				elseif ($_POST['nom'] == 2) {
					$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC, car');
				}
				$query->execute();
			}

			// Nom uniquement
			else
			{

				if ($_POST['nom'] == 1) 
				{
					$query = $bdd->prepare('SELECT * FROM users ORDER BY nom ASC');
				}

				elseif ($_POST['nom'] == 2) 
				{
					$query = $bdd->prepare('SELECT * FROM users ORDER BY nom DESC');
				}
				$query->execute();
			}

		}

		// Prénom
		elseif (!empty($_POST['prenom'])) 
		{
			$return = 1;
			// 2 filtres

			// prenom, civilité
			if (!empty($_POST['civ'])) 
			{
				// 3 filtres

				// Prénom, civilité, présence
				if (!empty($_POST['pres'])) 
				{
					// 4 filtres

					// Prénom, civilité, présence, mois
					if (!empty($_POST['month'])) 
					{
						$month = explode("-", $_POST['month'])[1];
						$year = explode("-", $_POST['month'])[0];

						// 5 filtres

						// Prénom, civilité, présence, mois, code postal
						if ( !empty( $_POST['cp'] ) ) 
						{
							// 6 filtres

							// Prénom, civilité, présence, mois, code postal, voiture
							if ( !empty( $_POST['car'] ) ) 
							{
								if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC, cp, car');
								}
								elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC, cp, car');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC, cp, car');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC, cp, car');
								}
								$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
							}
							else 
							{
								if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC, cp');
								}
								elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC, cp');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC, cp');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC, cp');
								}
								$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
							}
						}

						// Prénom, civilité, présence, mois, voiture
						elseif ( !empty( $_POST['car']) ) 
						{
							

							if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC, car');
								}
								elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC, car');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC, car');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC, car');
								}
								$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
						}
						else
						{
							if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC');
								}
								elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC');
								}
								elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) {
									$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC');
								}
								$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
						}
					}
					else 
					{
						if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom ASC, pres ASC');
						}
						elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom ASC, pres DESC');
						}
						elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom DESC, pres ASC');
						}
						elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom DESC, pres DESC');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
				}

				// Prénom, civilité, mois
				elseif ( !empty($_POST['month']) ) 
				{
					$month = explode("-", $_POST['month'])[1];
					$year = explode("-", $_POST['month'])[0];

					// 4 filtres

					// Prénom, civilité, mois, code postal
					if (!empty($_POST['cp'])) 
					{
						// 5 filtres

						// Prénom, civilité, mois, code postal, voiture
						if (!empty($_POST['car'])) 
						{
							if ($_POST['prenom'] == 1) {
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom ASC, cp, car');
							}
							elseif ($_POST['prenom'] == 2) {
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom DESC, cp, car');
							}
							$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
						}
						else 
						{
							if ($_POST['prenom'] == 1) {
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom ASC, cp');
							}
							elseif ($_POST['prenom'] == 2) {
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom DESC, cp');
							}
							$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
						}
					}
					else
					{
						if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year AND civ = :civ ORDER BY prenom ASC');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year AND civ = :civ ORDER BY prenom DESC');
						}
						$query->execute(['month' => $month, 'year' => $year, 'civ' => $_POST['civ']]);
					}
				}

				// Prénom, civilité, code postal
				elseif ( !empty($_POST['cp']) ) 
				{
					// Prénom, civilité, code postal, voiture
					if (!empty($_POST['car'])) 
					{
						if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom ASC, cp, car');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom DESC, cp, car');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}

					else
					{
						if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom ASC, cp');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom DESC, cp');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
				}

				// Prénom, civilité, voiture
				elseif ( !empty( $_POST['car'] ) ) 
				{
					if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom ASC, car');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom DESC, car');
						}
						$query->execute(['civ' => $_POST['civ']]);
				}

				//Prénom et civilité
				else
				{
					if ($_POST['prenom'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom ASC');
					}
					elseif ($_POST['prenom'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY prenom DESC');
					}
					$query->execute(['civ' => $_POST['civ']]);
				}
			}

			// Prénom et présence
			elseif ( !empty( $_POST['pres'] ) )
			{
				// 3 filtres

				// Prénom, présence, mois
				if (!empty( $_POST['month'] )) 
				{

					$month = explode("-", $_POST['month'])[1];
					$year = explode("-", $_POST['month'])[0];

					// 4 filtres

					// Prénom, présence, mois, code postal
					if ( !empty( $_POST['cp'] ) ) 
					{
						// 5 filtres

						// Prénom, présence, mois, code postal, voiture
						if (!empty( $_POST['car'] )) 
						{
							if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC, cp ASC, car ASC');
							}
							elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC, cp ASC, car ASC');
							}
							elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC, cp ASC, car ASC');
							}
							elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC, cp ASC, car ASC');
							}
							$query->execute(['month' => $month, 'year' => $year]);
						}
						else 
						{
						
							if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC, cp ASC');
							}
							elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC, cp ASC');
							}
							elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC, cp ASC');
							}
							elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC, cp ASC');
							}
							$query->execute(['month' => $month, 'year' => $year]);
						}
					}

					// Prénom, présence, mois, voiture
					elseif ( !empty( $_POST['car'] ) ) 
					{
						if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC, car ASC');
							}
							elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC, car ASC');
							}
							elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC, car ASC');
							}
							elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC, car ASC');
							}
							$query->execute(['month' => $month, 'year' => $year]);
					}

					else
					{
							if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres ASC');
						}
						elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom ASC, pres DESC');
						}
						elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres ASC');
						}
						elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY prenom DESC, pres DESC');
						}
						$query->execute(['month' => $month, 'year' => $year]);
					}
				}

				else 
				{
					if ($_POST['prenom'] == 1 && $_POST['pres'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom ASC, pres ASC');
					}
					elseif ($_POST['prenom'] == 1 && $_POST['pres'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom ASC, pres DESC');
					}
					elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom DESC, pres ASC');
					}
					elseif ($_POST['prenom'] == 2 && $_POST['pres'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom DESC, pres DESC');
					}
					$query->execute();
				}
			}

			// Prénom, mois
			elseif (!empty($_POST['month'])) 
			{

				$month = explode("-", $_POST['month'])[1];
				$year = explode("-", $_POST['month'])[0];

				// Prénom, mois, code postal
				if (!empty($_POST['cp'])) 
				{
					// Prénom, mois, code postal, voiture
					if (!empty($_POST['car'])) 
					{
						if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom ASC, cp, car');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom DESC, cp, car');
						}
						$query->execute(['month' => $month,'year' => $year]);
					}

					else
					{
						if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom ASC, cp');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom DESC, cp');
						}
						$query->execute(['month' => $month,'year' => $year]);
					}
				}
				else
				{
					if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom ASC, cp');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY prenom DESC, cp');
						}
						$query->execute(['month' => $month,'year' => $year]);
				}
			}

			// Prénom, code postal
			elseif (!empty($_POST['cp'])) 
			{
				// Prénom, code postal, voiture
				if (!empty($_POST['car'])) 
				{
					if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom ASC, cp, car');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom DESC, cp, car');
						}
						$query->execute();
				}
				else
				{
					if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom ASC, cp');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom DESC, cp');
						}
						$query->execute();
				}
			}

			// Prénom, voiture
			elseif (!empty($_POST['car'])) 
			{
				if ($_POST['prenom'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom ASC, car');
						}
						elseif ($_POST['prenom'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom DESC, car');
						}
						$query->execute();
			}

			else
			{
				if ($_POST['prenom'] == 1) 
				{
					$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom ASC');
				}
				elseif ($_POST['prenom'] == 2) 
				{
					$query = $bdd->prepare('SELECT * FROM users ORDER BY prenom DESC');
				}
				$query->execute();
			}
		}

		// Civilité
		elseif (!empty($_POST['civ'])) 
		{
			$return = 1;

			// Civilité, présence
			if (!empty($_POST['pres'])) 
			{
				// Civilité, présence, mois
				if (!empty($_POST['month'])) 
				{
					$month = explode("-", $_POST['month'])[1];
					$year = explode("-", $_POST['month'])[0];

					// Civilité,présence,mois,code postal
					if (!empty($_POST['cp'])) 
					{
						// Civilité,présence,mois,code postal,voiture
						if (!empty($_POST['car'])) 
						{
							if ($_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY pres ASC, cp, car');
							}
							elseif ($_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY pres DESC, cp, car');
							}
							$query->execute(['civ' => $_POST['civ'], 'month' => $month,'year'=>$year]);
							
						}
						else
						{
							if ($_POST['pres'] == 1) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY pres ASC, cp');
							}
							elseif ($_POST['pres'] == 2) 
							{
								$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH( dateE ) = :month AND YEAR( dateE ) = :year ORDER BY pres DESC, cp');
							}
							$query->execute(['civ' => $_POST['civ'], 'month' => $month,'year'=>$year]);
						}
					}

					// Civilité,présence,mois,voiture
					elseif(!empty($_POST['car'])) 
					{
						if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres ASC, car');
						}
						elseif($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres DESC, car');
						}
						$query->execute(['civ' => $_POST['civ'],'month' => $month,'year' => $year]);
					}
					else
					{
						if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres ASC');
						}
						elseif($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres DESC');
						}
						$query->execute(['civ' => $_POST['civ'],'month' => $month,'year' => $year]);
					}
				}

				// Civilité,présence,code postal
				elseif (!empty($_POST['cp'])) 
				{
					// Civilité,présence,code postal, voiture
					if (!empty($_POST['car'])) 
					{
						
						if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres ASC, cp, car ');
						}
						elseif ($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres DESC, cp, car ');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
					else
					{
						if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres ASC, cp ');
						}
						elseif ($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres DESC, cp ');
						}
						$query->execute(['civ' => $_POST['civ']]);
					}
				}

				// Civilité,présence,voiture
				elseif (!empty($_POST['car'])) 
				{
					if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres ASC, car ');
						}
						elseif ($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres DESC, car ');
						}
						$query->execute(['civ' => $_POST['civ']]);
				}
				else
				{
					if ($_POST['pres'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres ASC');
					}
					elseif ($_POST['pres'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY pres DESC');
					}
					$query->execute(['civ' => $_POST['civ']]);
				}
			}

			// Civilité, mois
			elseif (!empty($_POST['month'])) 
			{
				$month = explode("-", $_POST['month'])[1];
				$year = explode("-", $_POST['month'])[0];

				// Civilité, mois, code postal
				if (!empty($_POST['cp'])) 
				{
					// Civlité,mois,code postal,voiture
					if (!empty($_POST['car'])) 
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY cp, car');
						$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
					}
					else
					{
						$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY cp');
						$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
					}
					
				}
				else
				{
					$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ AND MONTH(dateE) = :month AND YEAR(dateE) = :year');
					$query->execute(['civ' => $_POST['civ'], 'month' => $month, 'year' => $year]);
				}
			}

			// Civilité, code postal
			elseif (!empty($_POST['cp'])) 
			{
				// Civilité, code postal, voiture
				if (!empty($_POST['car'])) 
				{
					$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY cp, car');
					$query->execute(['civ' => $_POST['civ']]);
				}
				else
				{
					$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY cp');
					$query->execute(['civ' => $_POST['civ']]);
				}
			}

			// Civilité, voiture
			elseif (!empty($_POST['car'])) 
			{
				$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ ORDER BY car');
					$query->execute(['civ' => $_POST['civ']]);
			}
			else
			{
				$query = $bdd->prepare('SELECT * FROM users WHERE civ = :civ');
				$query->execute(['civ' => $_POST['civ']]);
			}
		}

		// Présence
		elseif (!empty($_POST['pres'])) 
		{
			$return = 1;

			// Présence, mois
			if (!empty($_POST['month'])) 
			{
				$month = explode("-", $_POST['month'])[1];
				$year = explode("-", $_POST['month'])[0];

				// Présence, mois, code postal
				if (!empty($_POST['cp'])) 
				{
					// Présence, mois, code postal, voiture
					if (!empty($_POST['car'])) 
					{
						if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres ASC, cp, car');
						}
						elseif ($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres DESC, cp, car');
						}
						$query->execute(['month' => $month,'year' => $year]);
					}
					else
					{
						if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres ASC, cp');
						}
						elseif ($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres DESC, cp');
						}
						$query->execute(['month' => $month, 'year' => $year]);
					}
				}
				else
				{
					if ($_POST['pres'] == 1) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres ASC');
						}
						elseif ($_POST['pres'] == 2) 
						{
							$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY pres DESC');
						}
						$query->execute(['month' => $month, 'year' => $year]);
				}
			}

			// Présence, code postal
			elseif (!empty($_POST['cp'])) 
			{
				// Présence, code postal, voiture
				if (!empty($_POST['car'])) 
				{
					if ($_POST['pres'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY pres ASC, cp, car');
					}
					elseif ($_POST['pres'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY pres DESC, cp, car');
					}
					$query->execute();
				}
				else
				{
					if ($_POST['pres'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY pres ASC, cp');
					}
					elseif ($_POST['pres'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY pres DESC, cp');
					}
					$query->execute();
				}
			}

			// Présence, voiture
			elseif (!empty($_POST['car'])) 
			{
				if ($_POST['pres'] == 1) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY pres ASC, car');
					}
					elseif ($_POST['pres'] == 2) 
					{
						$query = $bdd->prepare('SELECT * FROM users ORDER BY pres DESC, car');
					}
					$query->execute();
			}
			else
			{
				if ($_POST['pres'] == 1) 
				{
					$query = $bdd->prepare('SELECT * FROM users ORDER BY pres ASC');
				}
				elseif ($_POST['pres'] == 2) 
				{
					$query = $bdd->prepare('SELECT * FROM users ORDER BY pres DESC');
				}
				$query->execute();
			}
		}

		// Mois
		elseif (!empty($_POST['month'])) 
		{
			$return = 1;
			$month = explode("-", $_POST['month'])[1];
			$year = explode("-", $_POST['month'])[0];

			// Mois, code postal
			if (!empty($_POST['cp'])) 
			{
				// Mois, code postal, voiture
				if (!empty($_POST['car'])) 
				{
					$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY cp, car');
					$query->execute(['month' => $month, 'year' => $year]);
				}
				else
				{
					$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY cp');
					$query->execute(['month' => $month, 'year' => $year]);
				}
			}

			// Mois, voiture
			elseif (!empty($_POST['car'])) 
			{
				$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ORDER BY car ');
				$query->execute(['month' => $month, 'year' => $year]);
			}

			else
			{
				$query = $bdd->prepare('SELECT * FROM users WHERE MONTH(dateE) = :month AND YEAR(dateE) = :year ');
				$query->execute(['month' => $month, 'year' => $year]);
			}
		}

		// Code postal
		elseif (!empty($_POST['cp'])) 
		{
			$return = 1;
			// code postal, voiture
			if (!empty($_POST['car'])) 
			{
				$query = $bdd->query('SELECT * FROM users ORDER BY cp, car');
				$query->execute();
			}
			else
			{
				$query = $bdd->query('SELECT * FROM users ORDER BY cp');
				$query->execute();
			}
		}

		// Voiture
		elseif (!empty($_POST['car'])) 
		{
			$return = 1;

				$query = $bdd->query('SELECT * FROM users ORDER BY car');
				$query->execute();
		}
		// Aucun filtre
		else
		{
			$return = 1;
			$query = $bdd->query('SELECT * FROM users');
		}

		// Après définition des filtres
		if ($return == 1) 
		{
		$return = "";
		$butt = 0;
		
		while ($res = $query->fetch()) 
		{
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


			$return .= '
			<div class="memberCard" id="member-'.$res['id'].'">
			<input type="checkbox" onchange="deleteMemberMultipleCall()" id="select-'.$res['id'].'" class="memberCheckbox">
			<label  for="select-'.$res['id'].'" class="memberSelect"></label>
					<div class="memberPic">
						<img src="voMbrs/mbr'.$res['id'].'/'.$res['pic'].'" alt="Membre">
						<div class="memberDot" style="background: '.$dot.';">
							
						</div>
					</div>
					<div class="memberName">
						'.$res['nom'].' '.$res['prenom'].'
						<span class="memberSurname">
							@'.$res['surnom'].'
						</span>
					</div>
					<div class="memberOption">
						<img src="voImg/edit.png" alt="Modifier" title="Modifier" onclick="editMbr(\''.$res['id'].'\')">
						<img src="voImg/delete.png" alt="Supprimer" title="Supprimer" onclick="deleteMemberCall(\''.$res['id'].'\', \'0\')">
					</div>
					<div class="memberPres">
						<div class="memberPresLeft">
							<span style="'.$color.'">'.$compPercent.'%</span> ce mois
						</div>
						<div class="memberPresRight">
							'.round($res['pres'],0).'%
							<span>Taux de présence</span>
						</div>
					</div>
					<button class="memberProfil" type="button">
						Profil
					</button>
			</div>
			';
			$test = 0;
			$butt = 1;
		}
	}
	if ($test == 1) {
		$return = 1;
	}
	$return .= '<button class="selectValide"><img src="voImg/delete.png" alt="" title="Supprimer la sélection"></button>';
		echo $return;
	}
}
?>