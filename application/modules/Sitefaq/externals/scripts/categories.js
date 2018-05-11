/* $Id: categories.js 6590 2012-18-05 00:00:00Z SocialEngineAddOns Copyright 2011-2012 BigStep Technologies Pvt. Ltd. $ */

var field_id;
var subcategories = function(category_id, sub, thisObject)
{ 
	field_id = thisObject;
	if($('buttons-wrapper')) {
		$('buttons-wrapper').style.display = 'none';
	}
	
	$('sub'+field_id+'_backgroundimage').style.display = 'block';
	$('sub'+field_id).style.display = 'none';
	$('subsub'+field_id).style.display = 'none';
	if($('sub'+field_id+'-label'))
		$('sub'+field_id+'-label').style.display = 'none';
		$('sub'+field_id+'_backgroundimage').innerHTML = '<img src="application/modules/Seaocore/externals/images/core/loading.gif" alt="" />';
	if($('subsub'+field_id+'-label'))
		$('subsub'+field_id+'-label').style.display = 'none';       

	var request = new Request.JSON({
		url : sitefaq_subcategory_url,
		data : {
			format : 'json', 
			category_id_temp : category_id
		},
		onSuccess : function(responseJSON) {          
			if($('buttons-wrapper')) {
				$('buttons-wrapper').style.display = 'block';
			}
			$('sub'+field_id+'_backgroundimage').style.display = 'none';
			clear('sub'+field_id);
			var  subcatss = responseJSON.subcats;		

			addOption($('sub'+field_id)," ", '0');
			for (i=0; i< subcatss.length; i++) {
				addOption($('sub'+field_id), subcatss[i]['category_name'], subcatss[i]['category_id']);
				$('sub'+field_id).value = sub;
			}
		
			if(category_id == 0) {
				clear('sub'+field_id);
				$('sub'+field_id).style.display = 'none';
				if($('sub'+field_id+'-label'))
					$('sub'+field_id+'-label').style.display = 'none';
				if($('subsub'+field_id+'-label'))
					$('subsub'+field_id+'-label').style.display = 'none';
			}
		}
	});
	request.send();
};

function clear(ddName)
{ 
	for (var i = (document.getElementById(ddName).options.length-1); i >= 0; i--) 
	{ 
		document.getElementById(ddName).options[ i ]=null; 
	} 
}	

function addOption(selectbox,text,value)
{ 
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;

	if(optn.text != '' && optn.value != '') {
		$('sub'+field_id).style.display = 'block';
		if($('sub'+field_id+'-wrapper'))
			$('sub'+field_id+'-wrapper').style.display = 'block';
		if($('sub'+field_id+'-label'))
			$('sub'+field_id+'-label').style.display = 'block';
		selectbox.options.add(optn);
	} else {
		$('sub'+field_id).style.display = 'none';
		if($('sub'+field_id+'-wrapper'))
			$('sub'+field_id+'-wrapper').style.display = 'none';
		if($('sub'+field_id+'-label'))
			$('sub'+field_id+'-label').style.display = 'none';
		selectbox.options.add(optn);
	}
}

function addSubOption(selectbox,text,value)
{
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	if(optn.text != '' && optn.value != '') {
		$('subsub'+field_id).style.display = 'block';
			if($('subsub'+field_id+'-wrapper'))
			$('subsub'+field_id+'-wrapper').style.display = 'block';
			if($('subsub'+field_id+'-label'))
			$('subsub'+field_id+'-label').style.display = 'block';
		selectbox.options.add(optn);
	} else {
		$('subsub'+field_id).style.display = 'none';
			if($('subsub'+field_id+'-wrapper'))
			$('subsub'+field_id+'-wrapper').style.display = 'none';
			if($('subsub'+field_id+'-label'))
			$('subsub'+field_id+'-label').style.display = 'none';
		selectbox.options.add(optn);
	}
}

function changesubcategory(subcatid, thisObjectId, subsubcat_id) {

	var sub_field_id = thisObjectId.split('sub');
	field_id = sub_field_id[1]; 

	if($('buttons-wrapper')) {
		$('buttons-wrapper').style.display = 'none';
	}

	$('subsub'+field_id+'_backgroundimage').style.display = 'block';
	$('subsub'+field_id).style.display = 'none';
	if($('subsub'+field_id+'-label'))
		$('subsub'+field_id+'-label').style.display = 'none';

	$('subsub'+field_id+'_backgroundimage').innerHTML = '<img src="application/modules/Seaocore/externals/images/core/loading.gif" alt="" /></center>';

	var request = new Request.JSON({
		url : sitefaq_subsubcategory_url,
		data : {
			format : 'json',
			subcategory_id_temp : subcatid
		},
		onSuccess : function(responseJSON) {
			if($('buttons-wrapper')) {
				$('buttons-wrapper').style.display = 'block';
			}
			$('subsub'+field_id+'_backgroundimage').style.display = 'none';
			clear('subsub'+field_id);
			var subsubcatss = responseJSON.subsubcats;

			addSubOption($('subsub'+field_id)," ", '0');
			for (i=0; i< subsubcatss.length; i++) {
				addSubOption($('subsub'+field_id), subsubcatss[i]['category_name'], subsubcatss[i]['category_id']);
			}

			if(subsubcat_id)
				$('subsub'+field_id).value = subsubcat_id;
		}
	});
	request.send();
}