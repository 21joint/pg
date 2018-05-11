<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: process.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<?php if($this->isAllowedAmount): ?>
<div>
	<div>
   <center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/loading.gif" /></center>
 </div>
 <div id="LoadingImage" style="text-align:center;margin-top:15px;font-size:17px;">  
   <?php echo $this->translate("Processing Request. Please wait .....")?>
 </div>
</div>
<script type="text/javascript">
  window.addEvent('load', function(){

    var url = '<?php echo $this->transactionUrl ?>';
    var data = <?php echo Zend_Json::encode($this->transactionData) ?>;
    var request = new Request.Post({
      url : url,
      data : data
    });
    request.send();
  });
</script>
<?php else: ?>
    <div class="tip"><span> <?php echo $this->translate("Minimum amount for this type of transaction is %s", Engine_Api::_()->sitecredit()->getPriceWithCurrency($this->minimumAllowedAmount)); ?></span></div>
  <?php endif; ?>