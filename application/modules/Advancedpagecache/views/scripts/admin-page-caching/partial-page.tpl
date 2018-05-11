<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: partial-page.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Advancedpagecache/externals/styles/admin/main.css');
?>

<?php include APPLICATION_PATH . "/application/modules/Advancedpagecache/views/scripts/admin_head.tpl";?>
<h3>
    <?php echo $this->translate("Manage Multiple Users Caching") ?>
</h3>

<br/>

<div class="mbot10">
    <p>
        Multiple Users Caching will cache the page on the basis of different configuration settings. For example: when any user will open a page whose URL is added for Multiple users caching on the basis of member level, that page will be cached. Hence benefiting the other users with the same member levels. The first time loading of that page will become faster for them. (for an idea you can refer default added URLs).
    </p>
</div>
<br/>
<div class="mbot10">
    <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'page-caching', 'action' => 'add-partial-url'), $this->translate('Add URLs'), array('class' => 'smoothbox link_button'));
    ?>
</div>
<br/>
<?php if( empty($this->currentCache['partialUrl']) ): ?>

	<div class='tip'>
 		<span> No Url is added.</span>
 	</div>
<?php else: ?>
<div class="cache_urls">
	    <table class='admin_table cache_table' width="100%" align="center">
	        <thead>
				<tr>
					<th width="55%">Url</th>
					<th align="center" width="30%" style="text-align:left;">Cache Based On</th>
					<th width="15%" style="align-content: center;">Options</th>
				</tr>
			</thead>
	    	<tbody>    
	    	<?php foreach($this->currentCache['partialUrl'] as $key => $value): ?>
	    		<tr>
	    			<td>
	    				<?php echo $key;?>
	    			</td>
	    			<td>
	    				<?php echo $this->optionArray[$value];?>
	    			</td>
	    			<td>
	    			<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'page-caching', 'action' => 'edit','key'=>urlencode($key),'basedon'=>$value),$this->translate('edit'), array('class' => 'smoothbox')).'   '.$this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'page-caching', 'action' => 'remove-url','key'=>urlencode($key)),$this->translate('delete'), array('class' => 'smoothbox')); ?>
	    			</td>
	    	</tr>
    		<?php endforeach; ?>
    		</tbody>
    </table>
    </div>
<?php endif; ?>