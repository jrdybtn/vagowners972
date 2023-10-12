<?php 
session_start();

// Connexion a la base de donnée
$user = "root";
$pass = "";
$bdd = new PDO('mysql:host=localhost;dbname=vo', $user, $pass);
// Notre page de fonctions
include "function.php";
// On vérifie les cookies de connexion
if (isset($_COOKIE['vagOwnersData'])) {
	$_SESSION['voConnect'] = [
				'nom' =>$_COOKIE['vagOwnersData']['nom'],
				'prenom' =>$_COOKIE['vagOwnersData']['prenom'],
				'pseudo' =>$_COOKIE['vagOwnersData']['pseudo'],
				'mail' =>$_COOKIE['vagOwnersData']['mail'],
				'type' =>$_COOKIE['vagOwnersData']['type']
			];
}

// Contenu div de confirmation 
$confirmDelCont = '
<div class="adminTabTitle">
			Confirmation
		</div>
		<div id="confirmPhrs"> 
			Voulez-vous réellement supprimer cet évènement ?
		</div>
		<div id="txtAreaConfirm"> 
			
		</div>


		<div class="adminTabButtons">
			<button type="button" class="annule">Annuler</button>
			<button class="valid" id="confirmButt">Supprimer</button>
			</div>
		';
// Contenu formulaire d'ajout de membre
$addMbrCont = '<input type="hidden" name="formType" value="addMbr">
		<div class="adminTabTitle">
			Ajout de membre
		</div>
		<div class="adminTabScroll">
			<div class="adminTabSection">
			<div class="adminTabSubTitle">
				Personnel
			</div>
			<div class="sectionPart">
				<div class="adminTabInput">
				<label for="">Nom</label>
				<input type="text" name="nom" required>
			</div>
			<div class="adminTabInput">
				<label for="">Prénom</label>
				<input type="text" name="prenom" required>
			</div>
			<div class="adminTabInput">
				<label for="">Surnom</label>
				<input type="text" name="surnom">
			</div>
			<div class="adminTabInput">
				<label for="">Date naissance</label>
				<input type="date" name="dateN" required>
			</div>
			<div class="adminTabInput">
				<label for="">Taille</label>
				<select name="taille" required>
					<option value="XS">XS</option>
					<option value="S">S</option>
					<option value="M">M</option>
					<option value="L">L</option>
					<option value="XL">XL</option>
					<option value="XXL">XXL</option>
					<option value="XXXL">XXXL</option>
				</select>
			</div>
			<div class="adminTabInput">
				<label for="">Date inscription</label>
				<input type="date" name="dateI">
			</div>
			<div class="adminTabInput">
				<label for="">Civilité</label>
				<select name="civ" required>
					<option value="Mr">Mr</option>
					<option value="Mme">Mme</option>
					<option value="Autres">Autres</option>
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
			<input type="text" name="adrss" required>
		</div>
		<div class="adminTabInput">
			<label for="">Complément d\'adresse</label>
			<input type="text" name="adrss2">
		</div>
		<div class="adminTabInput">
			<label for="">Ville</label>
			<input type="text" name="ville" required>
		</div>
		<div class="adminTabInput">
			<label for="">Code postal</label>
			<input type="number" name="cp" required> 
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
			<input type="mail" name="mail" required>
		</div>
		<div class="adminTabInput">
			<label for="">Téléphone</label>
			<input type="tel" name="phone" required>
		</div>
	</div>
	</div>
	<div class="adminTabSection">
			<div class="adminTabSubTitle">
				Autres
			</div>
			<div class="sectionPart">
		<div class="adminTabInput">
			<label for="">Photo du véhicule</label>
			<input type="file" name="pic" accept="image/*" required>
		</div>
		<div class="adminTabInput">
			<label for="">Couleur préférée</label>
			<input type="text" name="color">
		</div>
		<div class="adminTabInput">
			<label for="">Véhicule</label>
			<input type="text" name="car">
		</div>
		<div class="adminTabInput">
			<label for="">Type de membres</label>
			<select name="type">
				<option value="0">Membre probatoire</option>
				<option value="1">Membre normal</option>
				<option value="2">Membre administrateur</option>
			</select>
		</div>
	</div>
	</div>
	</div>
		<div class="adminTabButtons">
			<button type="button" class="annule">Annuler</button>
			<button class="valid">Valider</button>
		</div>';

		

		// On récupère les genres d'évènement
		$evReq = $bdd->query('SELECT * FROM event_gender');
		$option = "";
		// On insère tout les genre dans une variable
		while ($res = $evReq->fetch()) {
			$option .= '<option value="'.$res['idGenre'].'">'.$res['libelle'].'</option>';
		}

		
		// Contenu formulaire d'ajout d'évènement
		$addEvtCont = '<input type="hidden" name="formType" value="addEvt">
		<div class="adminTabTitle">
			Ajout d\'évènement
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
							'.$option.'
						</select>
					</div>
					<div class="adminTabInput">
						<label for="">Type</label>
						<select name="type" required>
							<option value="0">Rassemblement</option>
							<option value="1">Sortie</option>
						</select>
					</div>
					<div class="adminTabInput">
						<label for="">Date</label>
						<input type="date" name="date">
					</div>
					<div class="adminTabInput">
						<label for="">Heure du rendez-vous</label>
						<input type="time" name="heure" required>
					</div>
					<div class="adminTabInput">
						<label for="">Lieu du rendez-vous</label>
						<input type="text" name="lieu" required>
					</div>
				</div>
			</div>
			<div class="adminTabSection">
				<div class="adminTabSubTitle">
					Optionnel
				</div>
				<div class="sectionPart">
					<div class="adminTabInput">
						<label for="">Lieu d\'arrivé</label>
						<input type="text" name="lieu2">
					</div>
					<div class="adminTabInput">
						<label for="">Cotisation</label>
						<input type="number" step="0.01" name="cotis" value="0">
					</div>
				</div>
			</div>
		</div>
	<div class="adminTabButtons">
		<button type="button" class="annule">Annuler</button>
		<button class="valid">Valider</button>
	</div>
	';

	// Form d'ajout de genre d'évènement
	$addGendCont = '
	<input type="hidden" name="formType" value="addGend">
		<div class="adminTabTitle">
			Ajout de genre d\'évènement
		</div>
		<div class="adminTabScroll">
			<div class="adminTabSection">
				<div class="adminTabSubTitle">
					Texte
				</div>
				<div class="sectionPart">
					<div class="adminTabInput">
						<label for="">Libelle</label>
						<input type="text" name="libelle" required>
					</div>
				</div>
			</div>
			<div class="adminTabSection">
				<div class="adminTabSubTitle">
					Image
				</div>
				<div class="sectionPart">
					<div class="adminTabInput">
						<label for="">Logo du genre</label>
						<input type="file" name="logo" accept="image/*">
					</div>
				</div>
			</div>
		</div>
	<div class="adminTabButtons">
		<button type="button" class="annule">Annuler</button>
		<button class="valid">Valider</button>
	</div>
	';

	// On récupère les évènements ayant une cotisation
		$req = $bdd->query('SELECT * FROM events WHERE cotisation > 0 AND date > DATE( NOW() )');
		$eventOpt = "";

		while ( $res = $req->fetch() ) {
			if ($res['type'] == 0) {
				$type = "Rasso";
			}
			else {
				$type = "Sortie";
			}
			$date = eventDate($res['date']);

			$eventOpt .= '<option value="'.$res['id'].'">'.$type.' | '.$date.'</option>';
		}

		// On récupère les membres
		$req = $bdd->query('SELECT * FROM users ORDER BY prenom');
		$membersOpt = "";
		while ( $res = $req->fetch() ) {
			$membersOpt .= '<option value="'.$res['id'].'">'.$res['prenom'].' '.$res['nom'].' ('.$res['surnom'].')';
		}

	// Form d'ajout de transaction
	$addMoneyCont = '
	<input type="hidden" name="formType" value="addMoney">
		<div class="adminTabTitle">
			Ajout de transaction monétaire
		</div>
		<div class="adminTabScroll">
			<div class="adminTabSection">
				<div class="adminTabSubTitle">
					Obligatoire
				</div>
				<div class="sectionPart">
					<div class="adminTabInput">
						<label for="">Montant</label>
						<input type="number" step="0.01" name="montant" required>
					</div>
					<div class="adminTabInput">
						<label for="">Date</label>
						<input type="date" name="date" required>
					</div>
					<div class="adminTabInput">
						<label for="">Type</label>
						<select name="type" required>
							<option value="0">Ajout</option>
							<option value="1">Retrait</option>
							<option value="2">Cotisation annuelle</option>
						</select>
					</div>
				</div>
			</div>
			<div class="adminTabSection">
				<div class="adminTabSubTitle">
					Optionnel
				</div>
				<div class="sectionPart">
					<div class="adminTabInput">
						<label for="">Libellé</label>
						<input type="text" name="libelle">
					</div>
					<div class="adminTabInput">
						<label for="">Evènement</label>
						<select name="event">
							<option value="">-Evènement-</option>
							'.$eventOpt.'
							</select>
					</div>
					<div class="adminTabInput">
						<label for="">Membres</label>
						<select name="member">
							<option value="">-Membres-</option>
							'.$membersOpt.'
							</select>
					</div>
				</div>
			</div>
		</div>
	<div class="adminTabButtons">
		<button type="button" class="annule">Annuler</button>
		<button class="valid">Valider</button>
	</div>
	'
	;
// On calcul le nombre de membres total
$query = $bdd->query('SELECT COUNT(*) as total FROM users');
$query = $query->fetch();
$nbMbr = $query['total'];
// On sélectionne le nombre de membre s'étant inscris ce mois ci
$query = $bdd->query('SELECT COUNT(*) as total FROM users WHERE MONTH(DATE(NOW())) = MONTH(dateE) AND YEAR(DATE(NOW())) = YEAR(dateE)');
$query = $query->fetch();
$nbMbrMonth = $query['total'];

// On calcul le pourcentage de présence total
$query = $bdd->query('SELECT SUM(pres) as total FROM users');
$query = $query->fetch();
$percentPres = $query['total'] / $nbMbr;
$percentPres = round($percentPres, 2);

// On calcul le nombre total de rassemblement
$query = $bdd->query('SELECT COUNT(*) as total FROM events WHERE type = 0');
$query = $query->fetch();
$nbRass = $query['total'];

// On calcul le nombre total de sortie
$query = $bdd->query('SELECT COUNT(*) as total FROM events WHERE type = 1');
$query = $query->fetch();
$nbSort = $query['total'];

// On récupère le mois actuel
$month = date("m");
$year = date("Y");
$actuelMonth = $month;
$actuelYear = $year;

// On récupère le nombre de rasso de ce mois 
$req = $bdd->prepare('SELECT COUNT(*) as total FROM events WHERE type = 0 AND MONTH(date) = :mo AND YEAR(date) = :ye');
$req->execute(['mo'=>$month,'ye'=>$year]);
$req = $req->fetch();
$rassMonth = $req['total'];

// On récupère le nombre de sortie de ce mois
$req = $bdd->prepare('SELECT COUNT(*) as total FROM events WHERE type = 1 AND MONTH(date) = :mo AND YEAR(date) = :ye');
$req->execute(['mo'=>$month,'ye'=>$year]);
$req = $req->fetch();
$srtMonth = $req['total'];

$month--;
if ($month == 0) {
	$month = 12;
	$year--;
} else if ($month < 10 && $month > 0) {
	$month = explode('0', $month)[0];
}
// On récupère le nombre de rasso du mois dernier
$query = $bdd->prepare('SELECT COUNT(*) as total FROM events WHERE type = 0 AND MONTH(date) = :mo AND YEAR(date) = :ye');
$query->execute(['mo'=>$month,'ye'=>$year]);
$query = $query->fetch();
$backMonthRass = $rassMonth - $query['total'];
if ($backMonthRass > 0) {
	$backMonthRass = '+'.$backMonthRass;
}

// On récupère le nombre de sortie du mois dernier
$query = $bdd->prepare('SELECT COUNT(*) as total FROM events WHERE type = 1 AND MONTH(date) = :mo AND YEAR(date) = :ye');
$query->execute(['mo'=>$month,'ye'=>$year]);
$query = $query->fetch();
$backMonthSrt = $srtMonth - $query['total'];
if ($backMonthSrt > 0) {
	$backMonthSrt = '+'.$backMonthSrt;
}

// On récupère les prochains évènements
$nextEvents = $bdd->query('SELECT * FROM events INNER JOIN event_gender ON event_gender.idGenre = events.idGenre WHERE DATE(NOW()) <= date ORDER BY date');

// On calcul le pourcentage de présence par rapport au mois dernier
$Mnow = date("m");
$Myear = date("Y");

// Si le mois est en dessous de 10
if ($Mnow < 10) {
	$Mnow = explode('0', $Mnow)[1];
}

// On récupère le mois précédent celui actuel
$Mprev = $Mnow - 1;

// Si le mois précédent est 0 on le défini a 12 et on diminue l'année de 1
if ($Mprev == 0) {
	$Mprev = 12;
	$Myear--;
}

// On récupère le récap de ce mois-ci
$Preq = $bdd->query('SELECT * FROM monthrec ORDER BY id DESC LIMIT 1');
$Pres = $Preq->fetch();

//On récupère le récap du mois correspondant 
$Mreq = $bdd->prepare('SELECT * FROM monthrec WHERE month = :month AND year = :year');
$Mreq->execute(['month' => $Mprev, 'year' => $Myear]);
$Mres = $Mreq->fetch();
if (empty( $Pres['pres'] ) || empty( $Mres['pres'] ) ) {
	$Mpres = 0;
	$Mabs = 0;
}
else {
	$Mpres = $Pres['pres'] - $Mres['pres'];
	$Mabs = (100 - $Pres['pres']) - (100 - $Mres['pres']);
}



if ($Mabs < 0) {
	$MstyleAbs = "";
}
else {
	$Mabs = "+".$Mabs;
	$MstyleAbs = "min";
}

if ($Mpres >= 0) {
	$Mpres = "+".$Mpres;
	$MstylePres = "";
}

else {
	$MstylePres = "min";
}

// On calcul l'argent actuellement entré
$query = $bdd->query('SELECT * FROM transaction');
$entre = 0;
$sortie = 0;
while ($res = $query->fetch()) {
	if ($res['type'] == 0 || $res['type'] == 2) {
		$entre += $res['montant'];
	}
	else {
		$sortie += $res['montant'];
	}
}

// On défini le solde actuel
$solde = $entre - $sortie;
$currentYear = date('Y');
$currentMonth = date('m');
// On récupère le montant des cotisations annuelle récolté durant l'année actuel
$query = $bdd->query('SELECT SUM(montant) as total FROM transaction WHERE YEAR( date ) = YEAR( DATE( NOW() ) ) AND type = 2');
$cotisAnn = $query->fetch();
$cotisAnn = $cotisAnn['total'];
if ( empty($cotisAnn) ) {
	$cotisAnn = 0;
}

// On récupère le montant des cotisations de sortie récolté
$query = $bdd->query('SELECT SUM(montant) as total FROM transaction WHERE YEAR( date ) = YEAR( DATE( NOW() ) ) AND idEvent != 0');
$cotisEvent = $query->fetch();
$cotisEvent = $cotisEvent['total'];
if ( empty($cotisEvent) ) {
	$cotisEvent = 0;
}

// On prépare les données du graphique monétaire
$query = $bdd->prepare('SELECT MAX(revenu) as totR, MAX(depense) as totD FROM monthrec WHERE year = :year');
$query->execute(['year' => $currentYear]);
$maxTransac = $query->fetch();
// On défini la valeur maximal (100%)
$maxTransac = max( $maxTransac['totR'], $maxTransac['totD'] );

// On récupère le récap de chaque mois de cette année
$graphContent = $bdd->prepare('SELECT * FROM monthrec WHERE year = :year ORDER BY month');
$graphContent->execute(['year' => $currentYear]);

// On calcul le pourcentage de cotisation annuel réglé

// On compte le nombre de personne ayant réglé leurs cotisation
$query = $bdd->query('SELECT COUNT(*) as total FROM transaction WHERE type = 2 AND YEAR( date ) = YEAR( DATE( NOW() ) )');
$totalCotisA = $query->fetch();
$percentCotisA = round( ($totalCotisA['total'] * 100) / $nbMbr , 1 );

// On calcul l'argent récolté (cotisation) comparer a l'année précédente
$query = $bdd->query('SELECT COUNT(montant) as total FROM transaction WHERE idUser != 0 AND idEvent != 0 AND YEAR( date ) = YEAR( DATE( NOW() ) )-1');
$cotisYear = $query->fetch();
if ($cotisYear['total'] == 0) {
	$percentCotis = "+".$cotisEvent;
}
else
{
	$percentCotis = (($cotisEvent - $cotisYear['total']) / $cotisYear['total']) * 100;
}

// Calcul du solde comparé au mois dernier

if ( $currentMonth != 01 ) {
	$query = $bdd->query('SELECT SUM(depense) as dep, SUM(revenu) as rev FROM monthrec WHERE year = YEAR( DATE( NOW() ) ) AND month = MONTH( DATE( NOW() ) ) - 1');
}
else
{
	$query = $bdd->query('SELECT SUM(depense) as dep, SUM(revenu) as rev FROM monthrec WHERE year = YEAR( DATE( NOW() ) ) - 1 AND month = 12');
}

$exSolde = $query->fetch();
$exSolde = $exSolde['rev'] - $exSolde['dep'];
if ($exSolde >= 0) {
	$exSolde = "+".$exSolde;
}

// Affichage de tout les membres (page membre)
$membersList = $bdd->query('SELECT * FROM users');

$membersList2 = $bdd->query('SELECT * FROM users');

$evM = $actuelMonth - 1;
$evY = $actuelYear;
if ($evM < 10) {
	$evM = explode('0', $evM)[0];
}

if ($evM == 0) {
	$evY = $actuelYear - 1;
}

// Page profil de membre
if (isset($_GET['prf'])) 
{
	$profilId = $_GET['prf'];
	// On récupère les infos du membres en question 
	$query = $bdd->prepare('SELECT * FROM users WHERE id = :id');
	$query->execute(['id' => $profilId]);
	$profil = $query->fetch();
	// Pourcentage de présence du membre
	$percentPres = round($profil['pres'], 1);

	// On définit l'ombre selon si ce membre a réglé sa cotisation annuelle
				$query = $bdd->prepare('SELECT COUNT(*) as total FROM transaction WHERE idUser = :id AND YEAR( date ) = :year AND type = 2');
				$query->execute(['year' => $actuelYear, 'id' => $profil['id']]);
				$cotAnnRgl = $query->fetch();
				if ($cotAnnRgl['total'] == 0) 
				{
					$shadow = 'box-shadow: rgba(214, 20, 3, 0.3) 0px 0px 10px 10px';
					$cotisCheck = 1;
				}
				else
				{
					$shadow = "";
					$cotisCheck = 0;
				}
	// Liste des transaction effectuer par ce membre
	$transacList = $bdd->prepare('SELECT * FROM transaction WHERE idUser = :id ORDER BY date DESC');
	$transacList->execute(['id' => $profilId]);

	// Liste de présence du membre
$presList = $bdd->prepare('SELECT * FROM eventpres WHERE idUsers = :id');
$presList->execute(['id' => $profilId]);

// On récupère les dépense totals du membre
$query = $bdd->prepare('SELECT * FROM transaction WHERE idUser = :id');
$query->execute(['id' => $profilId]);
$totalDpsMbr = 0;
while ( $res = $query->fetch() ) 
{
	if ($res['type'] == 0 || $res['type'] == 2) 
	{
		$totalDpsMbr += $res['montant'];
	}
	else
	{
		$totalDpsMbr -= $res['montant'];
	}
}
}







?>