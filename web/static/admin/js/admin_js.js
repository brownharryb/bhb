
$(document).ready(function(){
	enable_model_view_form_actions();
	enable_date_form();
});




function enable_model_view_form_actions(){
	var $select_all_check_box = $('#select_all_check_box');
	$select_all_check_box.click(function(){
		if (this.checked){
			toggle_all_checkboxes(true);
		}
		else{
			toggle_all_checkboxes(false);
		}
	});
}

function toggle_all_checkboxes(state){
	if(state==true){
		$('.each_item_checkbox').prop('checked',true);
	}
	else if(state==false){
		$('.each_item_checkbox').prop('checked',false);
	}

}

function enable_date_form(){
	$('input[type="date"]').datepicker();
}