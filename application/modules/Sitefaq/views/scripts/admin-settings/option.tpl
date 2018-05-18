<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: option.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin')?></h2>

<div class='seaocore_admin_tabs'>
  <?php
  echo $this->navigation()
          ->menu()
          ->setContainer($this->navigation)
          ->render();
  ?>
</div>
<?php if( count($this->subNavigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->subNavigation)->render()
    ?>
  </div>
<?php endif; ?>

<?php echo $this->translate('This page lists all the reasons that users will be able to select when they do not find an FAQ helpful. Below, you can also hide/display selected reasons.');?>

<br/>
<br/>

<form id='multiadd_form' method="post" action="<?php echo $this->url(array('action'=>'multi-add'));?>" onSubmit="return multiAdd();">
  <table class='admin_table' style='width: 60%;'>
    <thead>
      <tr>
        <th class='admin_table_short' style='width: 3%;'>
        	<?php echo $this->translate('Hide / Display');?>
        </th>
        <th class='admin_table_short' style='width: 1%;'>
					<?php echo $this->translate("ID"); ?>
        </th>
        <th style='width: 20%;'>
        	<?php echo $this->translate("Reasons"); ?>
        </th>
        <th style='width: 5%;' class='admin_table_options'><?php echo $this->translate('Options'); ?></th>
      </tr>
    </thead>
    <tbody>
    	<?php foreach ($this->faqoptions as $item):?>
        <?php $sitefaq_reason = Engine_Api::_()->sitefaq()->truncateText($item->reason, 40);?>
        <tr>
          <td><input type='checkbox' name='add_<?php echo $item->option_id;?>' value='<?php echo $item->option_id ?>' class='checkbox' value="<?php echo $item->option_id ?>" <?php if($item->enable):?> Checked="true" <?php endif;?> /></td>
          <td><?php echo $item->option_id; ?></td>
          <td title="<?php echo $item->reason;?>"><?php echo $sitefaq_reason; ?></td>
          <td class='admin_table_options'>
				    <a class='smoothbox' href='<?php echo $this->url(array('action' => 'edit', 'id' => $item->option_id));?>'>
                <?php echo $this->translate("edit") ?>
            </a>
				  </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br />
  <div class='buttons'>
  	<button type='submit'><?php echo $this->translate("Submit") ?></button>
  </div>
</form>

<script type="text/javascript" >
 
  function multiAdd()
	{
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to display selected reasons?")) ?>');
	}

</script>

