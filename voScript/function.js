// Fonction javascript

function deleteMemberCall(idMember, type) {
	if (type == 0) 
	{
		$('#confirmPhrs').text('Voulez-vous réellement supprimer ce membre ?');
		$('#txtAreaConfirm').html('<textarea name="obs" id="txtarea-'+idMember+'" cols="50%" rows="5" placeholder="Observation..."></textarea>');
		confirmId = "member-"+idMember;
	}
	else if (type == 1) 
	{
		console.log(idMember);
		$('#confirmPhrs').text('Voulez-vous réellement supprimer tout les membres ?');
		$('#txtAreaConfirm').html('');
		confirmId = "allMembers-"+idMember;
	}
	$('#confirmDiv').slideToggle();
	
};

function deleteMemberMultipleCall()
{
	var length = $('#membersList input').length - 1;
	var i = 0;
	verif = 0;
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
		console.log(verif);
		$('.selectValide').css('bottom','0px');
	}
	else
	{
		$('.selectValide').css('bottom','-100%');
	}
};

// Clique sur modification d'évènement

function editMbr( id )
{
	console.log();
	if ($('#modifyMbr'+id).css('display') == "none") 
	{
		$('.adminTab').slideUp();
		$('#modifyMbr'+id).slideDown();
		slideMev = 0;
		slideM = 0;
		slideE = 0;
		slideU = 0;

	}
	else
	{
		$('#modifyMbr'+id).slideUp();
	}
	
}