/* $Id: core.js 2010-11-04 9:40:21Z SocialEngineAddOns Copyright 2009-2010 BigStep Technologies Pvt. Ltd. $ */
var credit_box_main_main;
window.addEvent('domready',function() {

	var credit_element= $('gateway_id').getParent();
	var credit_box_main = new Element('div', {
		'id' : 'credit_code_form_main',
		'class' : 'code_form sitecredit_form_wrapper',
	}).inject(credit_element, 'top');

	var credit_destroyer = new Element('a', {
		'id' : 'credit_addTextBox',
		'class' : 'code_link sitecredit_form_link',
		'href' : 'javascript:void(0);',
	  'html' : en4.core.language.translate("Redeem <?php echo ucfirst($GLOBALS['credits'])?>"),
		'events' : {
			'click' : function() {
				switchCreditBox('credit_code_form_main_main');
			}
		}
	}).inject(credit_box_main);
	
/*	var credit_getcodeLink = new Element('a', {
		'id' : 'credit_getcodeLink',
		'class' : 'buttonlink item_icon_coupon fright',
		'style' : 'margin-bottom:3px;',
		'href' : 'javascript:void(0);',
		'html' : en4.core.language.translate('Redeem Credits'),
		'events' : {
			'click' : function() {
				preview('500', '500');
			}
		}
	}).inject(credit_box_main);*/

	new Element('img', {
		'id': 'credit_lar_png',
		'styles': {'display': 'none'},
		'src' : 'application/modules/Sitecredit/externals/images/lar.png',
	}).inject(credit_destroyer, 'top');
	
	new Element('img', {
		'id': 'credit_lab_png',
		'styles': {'display': 'inline-block'},
		'src' : 'application/modules/Sitecredit/externals/images/lab.png',
	}).inject(credit_destroyer, 'top');
	
	credit_box_main_main = new Element('div', {
		'id' : 'credit_code_form_main_main',
		'class' : 'code_form sitecredit_form',
		'styles': {'display': 'block'},
	}).inject(credit_box_main);

	var credit_box = new Element('div', {
		'id' : 'credit_code_form',
		'class' : 'code_form',
	}).inject(credit_box_main_main);
	
	new Element('p', {
    'id' : 'sitecredittest-translation',
		'html' : en4.core.language.translate("Enter <?php echo $GLOBALS['credits']?> to avail discount."),
  }).inject(credit_box);

    var credit_textElement = new Element('input', {
		'type': 'text',
		'lable' : en4.core.language.translate("Redeem <?php echo ucfirst($GLOBALS['credits'])?>"),
		'name': 'code',
		'class': 'codebox',
		'id': 'credit_code_boxid',
		'onkeypress': 'return isNumberKey(event)',
		'autocomplete' : 'off'
	}).inject(credit_box);
	
	var credit_textElement1 = new Element('input', {
		'type': 'hidden',
		'name': 'sitecredit_package_type',
		'id': 'sitecredit_package_type',
	}).inject(credit_box);

	var credit_buttonElement = new Element('button', {
		'id' : 'credit_code_button_id',
		'name': 'submit',
		'html' : en4.core.language.translate("Redeem <?php echo ucfirst($GLOBALS['credits'])?>"),
		'events' : {
			'click' : function() { 
				creditCodeCheck(credit_textElement.value);
				return false;
			}
		}
	}).inject(credit_box);
	
	var credit_loadingimg = new Element('div', {
		'id' : 'credit_loding_image',
		'class' : 'sitecredit_loding_image',
		'styles': {'display': 'none'},
	}).inject(credit_box);
	
	new Element('img', {
		'src' : 'application/modules/Core/externals/images/loading.gif',
	}).inject(credit_loadingimg);

	function switchCreditBox (id) {
		if($(id).style.display == 'block') {
			$(id).style.display = 'none';
			$('credit_lar_png').style.display = 'inline-block';
      $('credit_lab_png').style.display = 'none';
			if ($('credit_meassge_show')) {
				$('credit_meassge_show').style.display = 'none';
				$('credit_code_boxid').value = '';
			}
		} else {
      $('credit_lab_png').style.display = 'inline-block';
			$('credit_lar_png').style.display = 'none';
			$(id).style.display = 'block';
		}
	}

});

function creditCodeCheck(code) {

	if ($('credit_meassge_show')) {
		$('credit_meassge_show').destroy();
	}
	$('credit_loding_image').style.display ='';
	var referenceNode = $('credit_addTextBox');
	var parent = referenceNode.parentNode;
	if (code == '') { 
		$('credit_loding_image').style.display ='none';
		return false;
	}
	else {
		new Request.JSON({
			url : en4.core.baseUrl + 'sitecredit/redeem/index',
			data : {
				format : 'json',
				code : code,
				package_type : $('sitecredit_package_type').value
			},
			onSuccess : function(responseJSON, responseHTML) {
				var newdiv = new Element('div',{
					'id' : 'credit_meassge_show'
				}).inject(credit_box_main_main, 'bottom');
				//parent.insertBefore(newdiv, referenceNode.nextSibling);
				if (responseJSON.status == true)  {
					newdiv.innerHTML = responseJSON.body+'<br/><br/>';
					$('credit_code_boxid').style.visibility = 'hidden';
					$('credit_code_boxid').style.display = 'none';
					$('sitecredittest-translation').style.display = 'none';
					$('credit_code_button_id').style.visibility = 'hidden';
					$('credit_code_button_id').style.display = 'none';
					$('credit_addTextBox').style.display = 'none';
					$('global_content').getElement('.global_form').action = en4.core.baseUrl + 'sitecredit/redeem/process/package_type/'+$('sitecredit_package_type').value;
					var credit_cancelbuttonElement = new Element('button', {
						'type':'button',
						'id' : 'credit_cancel_code_button_id',
						'name':'cancel_button',
						'html' : en4.core.language.translate("Cancel <?php echo ucfirst($GLOBALS['credits'])?>"),
						'events' : {
							'click' : function() { 
								cancelCreditcode();
							}
						}
					}).inject(newdiv);

				} else {
					newdiv.innerHTML = '<ul class="form-errors" style="margin:5px 0px 0px;"><li style="margin:0px;">' + responseJSON.credit_error_msg+ '</li></ul>';
				}
				$('credit_loding_image').style.display ='none';
			}
		}).send();
	}
}

function cancelCreditcode() {

	new Request.JSON({
		url: en4.core.baseUrl + 'sitecredit/redeem/cancel-payment-subscription',
        data : {
				format : 'json',
				package_type : $('sitecredit_package_type').value
		},
        onSuccess: function(responseJSON) {
        if(responseJSON.cart_credit_unset == true){
        		$('global_content').getElement('.global_form').action = en4.core.baseUrl + 'payment/subscription/process';
				$('credit_code_boxid').style.visibility = 'visible';
				$('credit_code_boxid').style.display = '';
				$('credit_code_boxid').value='';
				$('sitecredittest-translation').style.display = 'block';
				$('credit_code_button_id').style.visibility = 'visible';
				$('credit_code_button_id').style.display = '';
				$('credit_addTextBox').style.display = 'block';	
				if($('credit_meassge_show')){
					$('credit_meassge_show').style.display='none';
				}            	
        	}
        }		

	}).send();
}

function isNumberKey(evt) { 
    var charCode = (evt.charCode) ? evt.which : event.keyCode

    if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
        return false; 
             
    return true; 
} 
/*function preview(height, width) {
	var child_window = window.open (en4.core.baseUrl + 'sitecoupon/index/previewcoupon/package_type/' + $('sitecoupon_package_type').value,'mywindow','scrollbars=yes,width=500,height=600');
}*/