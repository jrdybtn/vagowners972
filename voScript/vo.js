$('.loaderContent').css('display','flex');
var alertGood = "#08dc71";	//Couleur pour succès
	var alertBad = "#fb0e4d";	//Couleur pour échecs
	var alertWarn = "#f8b343";	//Couleur pour avertissement
	var connMdp = 0;			//Variables changement type input mot de passe
	var confirmId;				//Id sauvegarder lors de la confirmation
	var slideMev = 0;			//Variable pour modification d'évènement
	var slideM = 0;				//Variable d'affichage de transaction
	var slideU = 0;				//Variable d'affichage d'utilisateur
	var slideE = 0; 			//Variable d'affichage d'ajout d'évènement
	var slideMbr = 0;			//Variable d'affichage de modification de membre
$(document).ready(function($) {
	$('.loaderContent').css('display','none');
	

	// Lors du clique sur l'oeil (mot de passe)
	$('.eye').click(function(){
		if (connMdp == 0) 
		{
			$(this).siblings('input').attr('type','text');	//Changement en type texte
			connMdp = 1;
		}
		else if(connMdp == 1)
		{
			$(this).siblings('input').attr('type','password');	//Changement en type mot de passe
			connMdp = 0;
		}
	});
	// Clique sur mot de passe oublié
		$('.forget').click(function() {
			$('#loginConn').slideToggle();
			$('#loginMdpo').slideToggle();
			$('#loginMdpo').css('display','flex');
		});
	// Clique sur connexion(page: mot de passe oublié, affiche: connexion)
		$('.con').click(function() {
			$('#loginMdpo').slideToggle();
			$('#loginConn').slideToggle();
		});


// Envoie du formulaire de connexion
$('#loginConn').submit(function(e) {
	e.preventDefault();		//On empêche l'envoie de base du formulaire
	var form = $(this).serialize();		//On formate le formulaire pour l'envoie
	$.post('voPhp/action.php', form, function(data) {		//On envoie le formulaire vers "voPhp/action.php" grâce a la méthode POST (ajax) de jquery
		if (data == 0) //Si la page renvoie 0 (Compte non existant)
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Ce compte n\'existe pas');
		}

		else if (data == 2) //Si la page renvoie 2 (connexion réussi)
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').html('Connexion réussi !<br> <i>Redirection dans 3 secondes</i>');
			var i = 3;

			// On update le message toute les secondes
			setInterval(function(){
				i--;
				$('.alertContent').html('Connexion réussi !<br> <i>Redirection dans '+i+' secondes</i>');

				if (i == 0)		//Si le timer est tomber a 0, on recharge la page 
				{
					location.reload();
				}
			},1000)
		}
		else if (data == 3) 
		{
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Erreur');
			$('.alertContent').text('Mot de passe incorect');
		}

		else {		//Si la page renvoie autre chose ou rien (erreur)
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Erreur');
			$('.alertContent').text('Une erreur est survenue');
		}

		// Dans tout les cas, on affiche une alerte pendant 3 secondes (3000 milisecondes)
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
	});
});

$('#loginMdpo').submit(function(e) {
	e.preventDefault();
	var form = $(this).serialize();
	$.post('voPhp/action.php', form, function(data) {
		console.log(data);
		if (data == 1) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Cet email ne correspondant a aucun de nos comptes');
		}
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').html('Un e-mail contenant un code vous a été envoyé <br><i>Redirection dans 3 secondes</i>');
		}

		// Dans tout les cas, on affiche une alerte pendant 3 secondes (3000 milisecondes)
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
	});
});

// Au clique sur le bouton d'option
$('.optionButt').click(function(){
	$(this).siblings().slideToggle(400);
});

// Au clique sur déconnexion (envoie vers action.php)
$('#disconect').click(function(){
	$.post('voPhp/action.php', {disconnect: '1'}, function(data) {
		if (data == 1) 
		{
			location.reload();
		}
	});
});

     // Affichage du menu
	var setTimeout1;
	var setTimeout2;
$('.sideMenu').hover(function() {

	if("matchMedia" in window) { // Détection
    if(window.matchMedia("(min-width:800px)").matches) {
    	setTimeout1 = setTimeout(function() 
    		{
    			$('.sideTxt').show(400);
    		}, 450);
    	
}
  }
	
},function() {
	clearTimeout( setTimeout1 );
	setTimeout2 = setTimeout(function(){
		var isHover = $('.sideMenu').is(":hover");
		if (isHover !== true) 
		{
			$('.sideTxt').hide(400);
		}
	}, 350)
});

// Bouton d'affichage du menu(mobile)
var st = 0; 
$('.toggleMenu').click(function(){
	if (st == 0) 
	{
		$('.sideTxt').css('display','block');
		$('.sideMenu').css('left','0');
		st = 1;
	} else {
		$('.sideTxt').css('display','');
		$('.sideMenu').css('left','');
		st = 0;
	}
	
});
// Bouton d'annulation (formulaire)
$('.annule').click(function(){
	$(this).parent().parent().slideToggle();
});
// Bouton d'affichage d'ajout de membre
$('.addMbrs').click(function(){
	
	$('.adminTab').slideUp();
	if (slideU == 0) 
	{
		$('#addMbrsForm').slideDown();
		slideU = 1;
		slideMev = 0;
		slideM = 0;
		slideE = 0;
	} 
	else {
		$('#addMbrsForm').slideUp();
		slideU = 0;
	}
	
});

// Forms d'ajout d'évènements 
$('#addEvtForm').submit(function(e) {
	e.preventDefault();
	form = $(this).serialize();
	$('.loaderContent').css('display','flex');
	$.post('voPhp/action.php', form, function(data) {
		console.log(data);
		if (data == 1.1 || data == 1.2) 
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').html('Evènement ajouter avec succès <br><i>Redirection dans 3 secondes</i>');
			if (data == 1.1) 
			{
				$('#nbRasso').text(parseInt($('#nbRasso').text()) + 1);
			}
			else {
				$('#nbSrt').text(parseInt($('#nbSrt').text()) + 1);
			}
			var i = 3;
			setInterval(function(){
				i--;
				$('.alertContent').html('Evènement ajouter avec succès <br> <i>Redirection dans '+i+' secondes</i>');

				if (i == 0)		//Si le timer est tomber a 0, on recharge la page 
				{
					location.reload();
				}
			},1000)
		}
		else if (data == 1) {
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').html('Un évènement a cette date et cette heure existe déjà');
		}

		else if (data == 0) {
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Echec');
			$('.alertContent').html('L\'évènement n\'a pas pu être ajouté');
		}

		$('.alert').css('left','10px');
		$('.loaderContent').css('display','none');
		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
	});
});

// Formulaire d'ajout de genre
$('#addGenderForm').submit(function(e) {
	e.preventDefault();
	form = new FormData(this);
	$.ajaxSetup({
  	contentType: false,
    processData: false
});
	$('.loaderContent').css('display','flex');
	$.post('voPhp/action.php', form ,function(data){
		console.log(data);
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Ce genre existe déjà');
		}
		else if(data == 1) {
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').html('Genre ajouter avec succès <br><i>Redirection dans 3 secondes</i>');
			var i = 3;
			setInterval(function(){
				i--;
				$('.alertContent').html('Genre ajouter avec succès <br> <i>Redirection dans '+i+' secondes</i>');

				if (i == 0)		//Si le timer est tomber a 0, on recharge la page 
				{
					location.reload();
				}
			},1000)
		} else if(data == 2) {
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Erreur');
			$('.alertContent').text('Le genre a été ajouté mais l\'image n\'a pas pu être enregistrer');
		} else {
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Erreur');
			$('.alertContent').text('Une erreur a été détecter');
		}
		$('.loaderContent').css('display','none');
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
	});
});

// Formulaire d'ajout de membre
$('#addMbrsForm').submit(function(e) {
	e.preventDefault();
	form = new FormData(this);
	$.ajaxSetup({
  	contentType: false,
    processData: false
});
	$('.loaderContent').css('display','flex');
	// On envoie le formulaire vers la page action.php
	$.post('voPhp/action.php', form, function(data) {
		console.log(data);
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('L\'email ou le numéro de téléphone est déjà utilisé');
		}
		if (data == 1) {
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').text('Le membre a bien été ajouté');
			// On incrémente le total de membre
			$('#totalMember').text($('#totalMember').text()+1);
		}

		if (data == 2) {
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Le membre a bien été ajouté mais le mail n\'a pas pu être envoyer');
		}
		
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		$('.loaderContent').css('display','none');
	});
});

// Formulaire de modification d'évènement
$('.modifyEvtTab').submit(function(e) 
{
	e.preventDefault();
	var form = $(this).serialize();
	$('.loaderContent').css('display','flex');
	// On envoie le formulaire vers la page action.php
	$.post('voPhp/action.php', form, function(data) {
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Erreur');
			$('.alertContent').text('L\'évènement n\'a pas pu être modifier');
		}
		if (data == 1) {
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').html('L\'évènement a été modifier avec succès <br><i>Redirection dans 3 secondes</i>');
			var i = 3;
			setInterval(function(){
				i--;
				$('.alertContent').html('L\'évènement a été modifier avec succès <br> <i>Redirection dans '+i+' secondes</i>');

				if (i == 0)		//Si le timer est tomber a 0, on recharge la page 
				{
					location.reload();
				}
			},1000)
		}
		
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		$('.loaderContent').css('display','none');
	});
});

// Formulaire de modification de membres
$('.modifyMbrTab').submit(function(e) 
{
	e.preventDefault();
	var form = $(this).serialize();
	$('.loaderContent').css('display','flex');

	// Envoie du formulaire
	$.post('voPhp/action.php', form, function(data) 
	{
		console.log(data);
		if (data == 1) 
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').html('Le membre a bien été modifier');
		}
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Echec');
			$('.alertContent').html('Le membre n\'a pas pu être modifier');
		}
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		$('.loaderContent').css('display','none');
	});

});

$('.modifyMbrSolo').submit(function(e)
{
	e.preventDefault();

	$.ajaxSetup({
  	contentType: false,
    processData: false
	});
	var form = new FormData(this);
	if ($('#modifyPicProfile').val() !== "") 
	{
		$('.picLoader').show(100);
	}
	$('.loaderContent').css('display','flex');

	// Envoie du formulaire
	// $.post('voPhp/action.php', form, function(data) 
	// {
	// 	console.log(data);
	// 	if (data == 1) 
	// 	{
	// 		$('.alertTitle').css('color', alertGood);
	// 		$('.alertTitle').text('Succès');
	// 		$('.alertContent').html('Le membre a bien été modifier');
	// 	}
	// 	if (data == 0) 
	// 	{
	// 		$('.alertTitle').css('color', alertBad);
	// 		$('.alertTitle').text('Echec');
	// 		$('.alertContent').html('Le membre n\'a pas pu être modifier');
	// 	}
	// 	$('.alert').css('left','10px');

	// 	// Timeout des 3 secondes
	// 	setTimeout(function() {
	// 		$('.alert').css('left','-200vw');
	// 	}, 3000);
	// 	$('.loaderContent').css('display','none');
	// });
});

// Formulaire d'ajout de transaction
$('#addMoneyForm').submit(function(e) {
	e.preventDefault();
	var form = $(this).serialize();
	$('.loaderContent').css('display','flex');

	// On envoie le formulaire vers la page action.php
	$.post('voPhp/action.php', form, function(data) {
		console.log(data);
		if (data == 0.1) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Si un membre est défini, un évènement doit l\'être aussi');
		}
		if (data == 0.2) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Un membre doit être défini pour le type "cotisation annuelle"');
		}
		if (data == 0.3) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Un évènement ne peut être lié a une cotisation annuelle');
		}
		if (data == 0.4) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Ce membre a déjà réglé la cotisation annuelle');
		}
		if (data == 1)
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').text('La transaction a bien été ajouté');
		}

		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		$('.loaderContent').css('display','none');
	})
});

// Filtreur
$('.filterCard').submit(function(e) {
	e.preventDefault();
	var form = $(this).serialize();
	$('.loaderContent').css('display','flex');

	// On envoie le formulaire vers la page action.php
	$.post('voPhp/action.php', form, function(data) { 
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Vous devez définir un filtre');
		}
		else if (data == 1) 
		{
			$('.alertTitle').css('color', alertWarn);
			$('.alertTitle').text('Attention');
			$('.alertContent').text('Aucun résultat trouvez');
		}
		else
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Réussi');
			$('.alertContent').text('Résultat afficher');
			$('#membersList').html(data);
		}
		$('.loaderContent').css('display','none');

		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		$('.loaderContent').css('display','none');

	});

});

// --BOUTON D'AFFICHAGE DE FORMULAIRE--

// A l'appuie sur l'ajout d'évènement
$('#sideAddEv').click(function(){
	$('.adminTab').slideUp();
	if (slideE == 0) {
		$('#addEvtForm').slideDown();
		slideE = 1;
		slideMev = 0;
		slideM = 0;
		slideU = 0;
	}
	else {
		slideE = 0;
	}
	
});

// Bouton d'ajout de transaction
$('#sideAddMoney').click(function() {
	$('.adminTab').slideUp();
	if (slideM == 0) 
	{
		$('#addMoneyForm').slideDown();
		slideM = 1;
		slideE = 0;
		slideMev = 0;
		slideU = 0;
	}
	else {
		slideM = 0;
	}
	
});

// Ouverture du form de modification d'évènement
$('.modifOp').click(function() {
	$('.adminTab').slideUp();
	if (slideMev == 0) 
	{
		$('#'+$(this).parent().parent().parent().nextAll('.modifyEvtTab')[0].id).slideDown();
		slideMev = 1;
		slideM = 0;
		slideE = 0;
		slideU = 0;
	}
	else {
		$('#'+$(this).parent().parent().parent().nextAll('.modifyEvtTab')[0].id).slideUp();
		slideMev = 0;
	}
	
});

// Ouverture du form de modification de membre
$('.modifOp').click(function() {
	$('.adminTab').slideUp();
	if (slideMbr == 0) 
	{
		$('#'+$(this).parent().parent().parent().nextAll('.modifyEvtTab')[0].id).slideDown();
		slideMev = 0;
		slideM = 0;
		slideE = 0;
		slideU = 0;
	}
	else {
		$('#'+$(this).parent().parent().parent().nextAll('.modifyEvtTab')[0].id).slideUp();
		slideMbr = 0;
	}
	
});

// A l'appuie sur ajout de genre d'évènement
$('.addGenderButt').click(function(){
	$('#addGenderForm').slideToggle();
});

// Changement de src img au clic (présence)
$('.etatPres').click(function(e) {

	// On récupère l'id de l'utilisateur
	var id = e.currentTarget.id.split('-')[1];

	// On récupère l'id de l'évènement
	var idE = e.currentTarget.id.split('-')[0];
$('.loaderContent').css('display','flex');
	// On envoie les id a la page de traitement PHP
	$.post('voPhp/action.php', {formType: 'togglePres', id: id, idE: idE}, function(data) {
		console.log(data);
		if (data == 0) 
		{
			$('.alertTitle').css('color', alertBad);
			$('.alertTitle').text('Succès');
			$('.alertContent').text('Le membre a été marqué comme absent');
		}
		else if (data == 1) 
		{
			$('.alertTitle').css('color', alertGood);
			$('.alertTitle').text('Succès');
			$('.alertContent').text('Le membre a été marqué comme présent');
		}
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		$('.loaderContent').css('display','none');
	});
	var str = $(this).attr('src');
	var index = str.indexOf("absence");
	if (index !== -1) {
		$(this).attr('src','voImg/presence.png')
	}
		else {
			$(this).attr('src','voImg/absence.png')
		}
});

// Fermeture de fenêtre (croix)
$('.closeTable').click(function() {
	$(this).parent().slideToggle();
});

// Ouverture de la liste des présence
$('.presOp').click(function() {
	$('#'+$(this).parent().parent().parent().nextAll('.adminTabPresList')[0].id).slideToggle();
});


// Supression d'un évènement

$('.delEvent').click(function() {
	confirmId = 'event-'+$(this).parent()[0].id.split('-')[1];
	$('#confirmDiv').slideToggle();
	$('#confirmPhrs').text('Voulez-vous réellement supprimer cet évènement ?');
});

// Après avoir confirmer
$('#confirmButt').click(function() {
	var type = confirmId.split('-')[0];
	var id = confirmId.split('-')[1];
	$('.loaderContent').css('display','flex');
	// Si on veut supprimer un évènement
	if (type == 'event') 
	{

		$.post('voPhp/action.php', {formType: 'delEvent', id: id}, function(data) {
			console.log(data);
			if (data == 1) 
			{
				$('.alertTitle').css('color', alertGood);
				$('.alertTitle').text('Succès');
				$('.alertContent').html('L\'évènement a bien été supprimer <br><i>Redirection dans 3 secondes</i>');
			var i = 3;
			setInterval(function(){
				i--;
				$('.alertContent').html('L\'évènement a bien été supprimer <br> <i>Redirection dans '+i+' secondes</i>');

				if (i == 0)		//Si le timer est tomber a 0, on recharge la page 
				{
					location.reload();
				}
			},1000)
			}
			else if(data == 0)
			{
				$('.alertTitle').css('color', alertBad);
				$('.alertTitle').text('Echec');
				$('.alertContent').text('L\'évènement n\'a pas pu être supprimer');
			}
			$('.loaderContent').css('display','none');
		$('.alert').css('left','10px');

		// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		});
	}
	else if( type == "member" )
	{
		var obs = $('#txtarea-'+id).val();
		console.log(obs);

		$.post('voPhp/action.php', {formType: 'delMember', id: id, obs: obs}, function(data) {
			console.log(data);
			if (data == 1) 
			{
				$('.alertTitle').css('color', alertGood);
				$('.alertTitle').text('Succès');
				$('.alertContent').html('Le membre a bien été supprimer');
				$('#member-'+id).slideToggle();
				$('#totalMember').text(parseInt($('#totalMember').text()) - 1);
				$('#confirmDiv').slideToggle();
			}
			else if (data == 0) 
			{
				$('.alertTitle').css('color', alertBad);
				$('.alertTitle').text('Echec');
				$('.alertContent').html('Le membre n\'a pas pu être supprimer');
			}
			$('.loaderContent').css('display','none');
			$('.alert').css('left','10px');

			// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		});
	}
	else if (type == "allMembers")
	{
		$.post('voPhp/action.php', {formType: 'delAllMember', id: id}, function(data) 
		{
			if (data == 1) 
			{
				$('.alertTitle').css('color', alertGood);
				$('.alertTitle').text('Succès');
				$('.alertContent').html('Tout les membres ont bien été supprimer');
				$('.memberCard').slideUp();
				$('#member-'+id).slideDown();
				$('#totalMember').text(parseInt('1'));
				$('#confirmDiv').slideToggle();
			}
			else if (data == 0) 
			{
				$('.alertTitle').css('color', alertBad);
				$('.alertTitle').text('Echec');
				$('.alertContent').html('Les membres n\'ont pas pu être supprimer');
			}

			$('.loaderContent').css('display','none');
			$('.alert').css('left','10px');

			// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
		});
	}
});

// Bouton de fermeture des options (mobile)
$('.closeOption').click(function() {
	$(this).parent().slideToggle();
});

// Affichage du bouton de suppression sélectif (membres)
$('.memberCheckbox').click(function()
{
	
	var length = $('#membersList input').length - 1;
	var i = 0;
	var verif = 0;
	while( i <= length )
	{
		// On vérifie si une membre a été sélectionné
		if ($('#membersList input')[i].checked) 
		{
			verif = 1;
			i = length + 1;
		}
		else
		{
			i++;
		}
		
	}
	if (verif == 1) 
	{
		$('.selectValide').css('bottom','0px');
	}
	else
	{
		$('.selectValide').css('bottom','');
	}
});

// Formulaire de suppression de membres (multiples)
$('#membersList').submit(function(e) 
{
	e.preventDefault();
	var form = $('#membersList input');
	var length = form.length - 1;
	var tab = new Array();
	var i = 0;
	var result = "";
	while(i <= length)
	{
		if (form[i].checked) 
		{
			var id = form[i].id.split("-")[1];
			tab.push(id);
		}
		i++;
	}
	// On envoie les données a la page action
	$.post('voPhp/action.php', {'allId[]': tab, formType: 'delSelectMember'}, function(data) 
	{
		console.log(data);
		if (data == 1) 
		{
			$('.alertTitle').css('color', alertGood);
				$('.alertTitle').text('Succès');
				$('.alertContent').html('Les membres sélectionnés ont été supprimer');

			i = 0;
			length = tab.length - 1;
			while( i <= length )
			{
				$('#member-'+tab[i]).slideUp();
				$('#totalMember').text(parseInt($('#totalMember').text()) - 1);
				i++;
			}
			$('.selectValide').css('bottom','');
		}
		$('.loaderContent').css('display','none');
			$('.alert').css('left','10px');

			// Timeout des 3 secondes
		setTimeout(function() {
			$('.alert').css('left','-200vw');
		}, 3000);
	});
});

// Affichage de l'évènement lié a une transaction (profil)
$('.profilTransacType').hover(function() 
{
	$(this).children('.profilTransacEventShow').show(200);
}, function() {
	$(this).children('.profilTransacEventShow').hide(200);
});

});

