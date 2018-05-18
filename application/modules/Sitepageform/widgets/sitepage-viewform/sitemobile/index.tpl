<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript" >
  var submitformajax = 1;
  var page_url = '<?php echo $this->page_url;?>';
</script>

<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php
	  /* Include the common user-end field switching javascript */
	  echo $this->partial('_jsSwitch.tpl', 'fields', array(
	      'topLevelId' => (int) @$this->topLevelId,
	      'topLevelValue' => (int) @$this->topLevelValue
	    ))
	?>
<div id="show_tab_content_child"> 
	<?php 
		echo $this->form->setAttrib('data-ajax', 'true')->render($this) ;
	?>
</div>