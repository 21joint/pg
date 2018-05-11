<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _Subcategory.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
echo "
	<li id='subcategory_id-label' style='display:none;' > 


		 <span >" . $this->translate('Subcategory') . "</span>


			<select name='subcategory_id' id='subcategory_id' onchange='subcate(this.value,0);' >
	
			</select>

	</li>";
?>
<?php
echo "
	<li id='subsubcategory_id-label' style='display:none;'>


		 <span >" . $this->translate('3%s Level Category', "<sup>rd</sup>") . "</span>


			<select name='subsubcategory_id' id='subsubcategory_id' onchange=\"javascript:$('subsubcategory').value=$('subsubcategory_id').value \">

			</select>

	</li>";
?>
<script type="text/javascript">
  function subcate(subcate, subsubcate) {

		clear('subsubcategory_id');
		$('subsubcategory_id').style.display = 'none';
		$('subsubcategory_id-label').style.display = 'none';

  	$('subcategory').value = subcate;
    changesubcategory(subcate, subsubcate);
  }

  if($('subcategory_id'))
    $('subcategory_id').style.display = 'none';
  if($('subcategory_id-label'))
    $('subcategory_id-label').style.display = 'none';

  if($('subsubcategory_id'))
    $('subsubcategory_id').style.display = 'none';
  if($('subsubcategory_id-label'))
    $('subsubcategory_id-label').style.display = 'none';
</script>