<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
  	              . 'application/modules/Seaocore/externals/styles/styles.css');
?>
<script type="text/javascript">
  var pageAction =function(page){
    $('page').value = page;
    $('filter_form').submit();
  }
  var searchSitefaqs = function() {
    if( Browser.Engine.trident ) {
      document.getElementById('filter_form').submit();
    } else {  
      $('filter_form').submit();
    }
  }
  en4.core.runonce.add(function(){
    $$('#filter_form input[type=text]').each(function(f) {
      if (f.value == '' && f.id.match(/\min$/)) {
        new OverText(f, {'textOverride':'min','element':'span'});
        //f.set('class', 'integer_field_unselected');
      }
      if (f.value == '' && f.id.match(/\max$/)) {
        new OverText(f, {'textOverride':'max','element':'span'});
        //f.set('class', 'integer_field_unselected');
      }
    });
  });
  window.addEvent('onChangeFields', function() {
    var firstSep = $$('li.browse-separator-wrapper')[0];
    var lastSep;
    var nextEl = firstSep;
    var allHidden = true;
    do {
      nextEl = nextEl.getNext();
      if( nextEl.get('class') == 'browse-separator-wrapper' ) {
        lastSep = nextEl;
        nextEl = false;
      } else {
        allHidden = allHidden && ( nextEl.getStyle('display') == 'none' );
      }
    } while( nextEl );
    if( lastSep ) {
      lastSep.setStyle('display', (allHidden ? 'none' : ''));
    }
  });
</script>


<div class="seaocore_search_criteria">
	<?php echo $this->form->render($this) ?>
</div>
<?php $row = Engine_Api::_()->getDbTable('searchformsetting', 'seaocore')->getFieldsOptions('sitefaq', 'category_id');
if(!empty($row->display)):
?>

<script type="text/javascript">

  var form;

	if($('filter_form')) {
		var form = document.getElementById('filter_form');
	}

  var subcategories = function(category_id, sub, subcatname)
  { 

		clear('subcategory_id');
		clear('subsubcategory_id');
		$('subcategory_id').style.display = 'none';
		$('subcategory_id-label').style.display = 'none';
		$('subsubcategory_id').style.display = 'none';
		$('subsubcategory_id-label').style.display = 'none';

    if($('category_id') && form.elements['category_id']){
      form.elements['category_id'].value = '<?php echo $this->category_id?>';
    }
    if($('subcategory_id') && form.elements['subcategory_id']){
      form.elements['subcategory_id'].value = '<?php echo $this->subcategory_id?>';
    }
    if($('subsubcategory_id') && form.elements['subsubcategory_id']){
      form.elements['subsubcategory_id'].value = '<?php echo $this->subsubcategory_id?>';
    }
    if(category_id != '' && form.elements['category_id']){
      form.elements['category_id'].value = category_id;
    }

    if(category_id != 0) {
      if(sub == '')
     subsubcat = 0;
      changesubcategory(sub, subsubcat);
    }
    
  	var url = '<?php echo $this->url(array('action' => 'sub-category'), 'sitefaq_category', true);?>';
    en4.core.request.send(new Request.JSON({      	
      url : url,
      data : {
        format : 'json',
        category_id_temp : category_id
      },
      onSuccess : function(responseJSON) {
      	clear('subcategory_id');
        var  subcatss = responseJSON.subcats;        
        addOption($('subcategory_id')," ", '0');
        for (i=0; i< subcatss.length; i++) {
          addOption($('subcategory_id'), subcatss[i]['category_name'], subcatss[i]['category_id']);  
          $('subcategory_id').value = sub;
          form.elements['subcategory'].value = $('subcategory_id').value;
        	form.elements['categoryname'].value = subcatss[i]['categoryname_temp'];
          form.elements['category'].value = category_id;
          form.elements['subcategory_id'].value = $('subcategory_id').value;
          if(form.elements['subsubcategory'])
          form.elements['subsubcategory'].value = subsubcat;
          if(form.elements['subsubcategory_id'])
          form.elements['subsubcategory_id'].value = subsubcat;
        }

        if(subcatss.length == 0) {
	      	form.elements['categoryname'].value = 0;
        }
        
        if(category_id == 0) {
          clear('subcategory_id');
          clear('subsubcategory_id');
          $('subcategory_id').style.display = 'none';
          $('subcategory_id-label').style.display = 'none';
          $('subsubcategory_id').style.display = 'none';
          $('subsubcategory_id-label').style.display = 'none';
        }
      }
    }));    
  };

  function clear(ddName)
  { 
    for (var i = (document.getElementById(ddName).options.length-1); i >= 0; i--) 
    { 
      document.getElementById(ddName).options[ i ]=null; 	      
    } 
  }

  function addOption(selectbox,text,value )
  {
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;			
    if(optn.text != '' && optn.value != '') {
      $('subcategory_id').style.display = 'block';
      $('subcategory_id-label').style.display = 'block';
      selectbox.options.add(optn);
    } 
    else {
      $('subcategory_id').style.display = 'none';
      $('subcategory_id-label').style.display = 'none';
      selectbox.options.add(optn);
    }
  }
  
	var changesubcategory = function(subcatid, subsubcat)
	{
		var url = '<?php echo $this->url(array('action' => 'subsub-category'), 'sitefaq_category', true);?>';
		var request = new Request.JSON({
			url : url,
			data : {
				format : 'json',
				subcategory_id_temp : subcatid
			},
			onSuccess : function(responseJSON) {
				clear('subsubcategory_id');
				var  subsubcatss = responseJSON.subsubcats;
				addSubOption($('subsubcategory_id')," ", '0');
				for (i=0; i< subsubcatss.length; i++) {
					addSubOption($('subsubcategory_id'), subsubcatss[i]['category_name'], subsubcatss[i]['category_id']);
					$('subsubcategory_id').value = subsubcat;
					if(form.elements[' subsubcategory_id'])
					form.elements[' subsubcategory_id'].value = $('subsubcategory_id').value;
					if(form.elements[' subsubcategory'])
					form.elements['subsubcategory'].value = $('subsubcategory_id').value;
					if($('subsubcategory_id')) {
						$('subsubcategory_id').value = subsubcat;
					}
				}

				if(subcatid == 0) {
					clear('subsubcategory_id');
					if($('subsubcategory_id-label'))
					$('subsubcategory_id-label').style.display = 'none';
				}
			}
		});
		request.send();
	};

	function addSubOption(selectbox,text,value )
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
		if(optn.text != '' && optn.value != '') {
			$('subsubcategory_id').style.display = 'block';
				if($('subsubcategory_id-wrapper'))
				$('subsubcategory_id-wrapper').style.display = 'block';
				if($('subsubcategory_id-label'))
				$('subsubcategory_id-label').style.display = 'block';
			selectbox.options.add(optn);
		} else {
			$('subsubcategory_id').style.display = 'none';
				if($('subsubcategory_id-wrapper'))
				$('subsubcategory_id-wrapper').style.display = 'none';
				if($('subsubcategory_id-label'))
				$('subsubcategory_id-label').style.display = 'none';
			selectbox.options.add(optn);
		}
	}

  var cat = '<?php echo $this->category_id ?>';

  if(cat != '' && cat != 0) {
    var sub = '<?php echo $this->subcategory_id; ?>';
    var subcatname = '<?php echo $this->subcategory_name; ?>';
    var subsubcat = '<?php echo $this->subsubcategory_id; ?>';
    subcategories(cat, sub, subcatname,subsubcat);
  }
  
  function show_subcat(cat_id) 
  {		
    if(document.getElementById('subcat_' + cat_id)) {
      if(document.getElementById('subcat_' + cat_id).style.display == 'block') {		
        document.getElementById('subcat_' + cat_id).style.display = 'none';
        document.getElementById('img_' + cat_id).src = '<?php echo $this->layout()->staticBaseUrl;?>application/modules/Sitefaq/externals/images/bullet-right.png';
      } 
      else if(document.getElementById('subcat_' + cat_id).style.display == '') {			
        document.getElementById('subcat_' + cat_id).style.display = 'none';
        document.getElementById('img_' + cat_id).src = '<?php echo $this->layout()->staticBaseUrl;?>application/modules/Sitefaq/externals/images/bullet-right.png';
      }
      else {			
        document.getElementById('subcat_' + cat_id).style.display = 'block';
        document.getElementById('img_' + cat_id).src = '<?php echo $this->layout()->staticBaseUrl;?>application/modules/Sitefaq/externals/images/bullet-bottom.png';
      }		
    }
  }
</script>
<?php endif; ?>