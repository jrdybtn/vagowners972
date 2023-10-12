<?php
// Fonction de gestion des dates
function eventDate($date) {
	// On divise les différentes partie de notre date
	$res = explode("-", $date);
	$year = $res['0'];
	$month = $res['1'];
	$day = $res['2'];
	// On convertie le mois en lettre
	if ($month == '01') {
		$month = 'Jan';
	}
	if ($month == '02') {
		$month = 'Fev';
	}
	if ($month == '03') {
		$month = 'Mars';
	}
	if ($month == '04') {
		$month = 'Avr';
	}
	if ($month == '05') {
		$month = 'Mai';
	}
	if ($month == '06') {
		$month = 'Juin';
	}
	if ($month == '07') {
		$month = 'Juil';
	}
	if ($month == '08') {
		$month = 'Août';
	}
	if ($month == '09') {
		$month = 'Sept';
	}
	if ($month == '10') {
		$month = 'Oct';
	}
	if ($month == '11') {
		$month = 'Nov';
	}
	if ($month == '12') {
		$month = 'Dec';
	}
	$newDate = $day." ".$month;
	return $newDate;
}

// Fonction d'affichage du mois uniquement
function giveMonth($month) {

// On convertie le mois en lettre
	if ($month == '1') {
		$month = 'Jan';
	}
	if ($month == '2') {
		$month = 'Fev';
	}
	if ($month == '3') {
		$month = 'Mars';
	}
	if ($month == '4') {
		$month = 'Avr';
	}
	if ($month == '5') {
		$month = 'Mai';
	}
	if ($month == '6') {
		$month = 'Juin';
	}
	if ($month == '7') {
		$month = 'Juil';
	}
	if ($month == '8') {
		$month = 'Août';
	}
	if ($month == '9') {
		$month = 'Sept';
	}
	if ($month == '10') {
		$month = 'Oct';
	}
	if ($month == '11') {
		$month = 'Nov';
	}
	if ($month == '12') {
		$month = 'Dec';
	}
	return $month;
}

// Fonction d'affichage du mois en entier
function giveMonthEntier($month) {

// On convertie le mois en lettre
	if ($month == '1') {
		$month = 'Janvier';
	}
	if ($month == '2') {
		$month = 'Fevrier';
	}
	if ($month == '3') {
		$month = 'Mars';
	}
	if ($month == '4') {
		$month = 'Avril';
	}
	if ($month == '5') {
		$month = 'Mai';
	}
	if ($month == '6') {
		$month = 'Juin';
	}
	if ($month == '7') {
		$month = 'Juillet';
	}
	if ($month == '8') {
		$month = 'Août';
	}
	if ($month == '9') {
		$month = 'Septembre';
	}
	if ($month == '10') {
		$month = 'Octobre';
	}
	if ($month == '11') {
		$month = 'Novembre';
	}
	if ($month == '12') {
		$month = 'Décembre';
	}
	return $month;
}

// Fonction de conversion d'heure au format FR sans SECONDES
function hourConvert($hour) {
	$res = explode(':',$hour);
	$newHour = $res['0']."h".$res['1'];
	return $newHour;
}

// Convertion en pourcentage
function percentPres($pres, $total) {
	$percent = ($pres*100)/$total;
	return $percent;
}

// Fonction de mise a jour des présences
function updateAllPres($user, $type, $date, $bdd) 
{

// Utilisateur uniquement
	if ($type == 0 || $type == 2) 
	{
		// Récupération de l'utilisateur
		$req = $bdd->prepare('SELECT * FROM users WHERE id = :id');
		$req->execute(['id' => $user]);
		$util = $req->fetch();

		// Total de présence
		$req = $bdd->prepare('SELECT COUNT(*) as total FROM eventpres WHERE idUsers = :us AND pres = 1');
		$req->execute(['us' => $user]);
		$totalPresence = $req->fetch();
		$totalPresence = $totalPresence['total'];

		// Total d'évènement
		$req = $bdd->prepare('SELECT COUNT(*) as total FROM events WHERE date >= :date');
		$req->execute(['date' => $util['dateE']]);
		$totalEvent = $req->fetch();
		$totalEvent = $totalEvent['total'];

		// Calcul du pourcentage
		if ($totalEvent == 0) {
			$percent = 100;
		}
		else {
			$percent = ($totalPresence * 100) / $totalEvent;
		}
		

		// Mise a jour de base de donnée
		$req = $bdd->prepare('UPDATE users SET pres = :perc WHERE id = :id');
		$req->execute(['perc' => $percent, 'id' => $user]);
	}

// Mois uniquement
	if ($type == 1 || $type == 2) 
	{
		// Récupération des évènements
		$events = $bdd->prepare('SELECT * FROM events WHERE MONTH( date ) = MONTH( :date ) AND YEAR( date ) = YEAR( :date )');
		$events->execute(['date' => $date]);
		$presence = 0;
		$total = 0;

		// Total membres
		$req = $bdd->prepare('SELECT COUNT(*) as total FROM users WHERE dateE <= :date');
		$req->execute(['date' => $date]);
		$members = $req->fetch();
		$members = $members['total'];

		// Boucle sur évènements
		while ($res = $events->fetch() ) 
		{
			// Liste présence d'évènement
			$req = $bdd->prepare('SELECT COUNT(*) as total FROM eventpres WHERE idEvent = :id AND pres = 1');
			$req->execute(['id' => $res['id']]);
			$totalPres = $req->fetch();
			$totalPres = $totalPres['total'];
			$presence += ( $totalPres * 100 ) / $members;
			$total++;
		}

		if ($total == 0) 
		{
			$presence = 100;
		}
		else 
		{
			$presence = $presence / $total;
		}

		// Vérification de l'existance du recap
		$req = $bdd->prepare('SELECT COUNT(*) as tot FROM monthrec WHERE month = MONTH( :date ) AND year = YEAR( :date )');
		$req->execute(['date' => $date]);
		$res = $req->fetch();

		if ($res['tot'] == 0) {
			$insert = $bdd->prepare('INSERT INTO monthrec (month,year,pres) VALUES(MONTH( :date ), YEAR( :date ), :pres)');
			$insert->execute(['date' => $date, 'pres' => $presence]);
		}
		else
		{
			$update = $bdd->prepare('UPDATE monthrec SET pres = :pres WHERE month = MONTH( :date ) AND year = YEAR( :date )');
			$update->execute(['pres' => $presence, 'date' => $date]);
		}
	}
}
?>