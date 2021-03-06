//client section
function autocompletClient() {
	var min_length = 1; // min caracters to display the autocomplete
	var keyword = $('#nomClient').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'ajax-refresh-client.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#clientList').show();
				$('#clientList').html(data);
			}
		});
	} 
	else {
		$('#clientList').hide();
	}
}
// set_item client : this function will be executed when we select an item
function setItemClient(item1, item2, item3, item4, item5, item6, item7){
	// change input value
	$('#nomClient').val(item1);
	$('#cin').val(item2);
	$('#telephone1').val(item3);
	$('#telephone2').val(item4);
	$('#adresse').val(item5);
	$('#email').val(item6);
	$('#idClient').val(item7);
	// hide proposition list
	$('#clientList').hide();
}
///
//fournisseur section
function autocompletFournisseur() {
	var min_length = 1; // min caracters to display the autocomplete
	var keyword = $('#nomFournisseur').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'ajax-refresh-fournisseur.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#fournisseurList').show();
				$('#fournisseurList').html(data);
			}
		});
	} 
	else {
		$('#fournisseurList').hide();
	}
}
// set_item fournisseur : this function will be executed when we select an item
function setItemFournisseur(item1, item2, item3, item4, item5, item6, item7){
	// change input value
	$('#nomFournisseur').val(item1);
	$('#adresse').val(item2);
	$('#telephone1').val(item3);
	$('#telephone2').val(item4);
	$('#email').val(item5);
	$('#fax').val(item6);
	$('#idFournisseur').val(item7);
	// hide proposition list
	$('#fournisseurList').hide();
}
///
//employeProjet section
function autocompletEmployeProjet() {
	var min_length = 1; // min caracters to display the autocomplete
	var keyword = $('#nomEmployeProjet').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'ajax-refresh-employe-projet.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#employeProjetList').show();
				$('#employeProjetList').html(data);
			}
		});
	} 
	else {
		$('#employeProjetList').hide();
	}
}
// set_item employeProjet : this function will be executed when we select an item
function setItemEmployeProjet(item1){
	// change input value
	$('#nomEmployeProjet').val(item1);
	// hide proposition list
	$('#employeProjetList').hide();
}