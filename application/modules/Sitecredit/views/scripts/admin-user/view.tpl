<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="transaction_view_popup">
  <p class="close_box">
    <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
      <strong><?php echo $this->translate("Close X") ?></strong></a>
    </p>
    <div class="transaction_view_popup_details">
      <table width="100%" class="table">
        <tr>
          <td valign="top"><?php echo $this->htmlLink($this->user->getOwner()->getHref(),$this->itemPhoto($this->user, 'thumb.icon'), array('title' => $this->user->getOwner()->getTitle(), 'target' => '_blank'));?></td>
          <td>
           <table width="100%" class="table">
            <tr>
              <td width="40%"><strong>User Name :</strong></td>
              <td width="60%"><?php echo $this->htmlLink($this->user->getOwner()->getHref(), $this->string()->stripTags($this->user->getOwner()->getTitle()), array('title' => $this->user->getOwner()->getTitle(), 'target' => '_blank'));?></td>
            </tr>
            <?php if($this->totalCredits) : ?>
              <tr>
                <td width="40%"><strong>Total Credits :</strong></td>
                <td width="60%"><?php echo $this->totalCredits; ?></td>
              </tr>
            <?php endif; ?>
            <?php if($this->validityDate) : ?>
              <tr>
                <td width="40%"><strong>Validity Date :</strong></td>
                <td width="60%"><?php echo date('dS F Y', strtotime($this->validityDate)); ?></td>
              </tr>
            <?php endif; ?>
            <?php if($this->validityDays) if($this->validityDays <= 60 ) : ?>
              <tr>
                <td width="40%"><strong>Validity will expire in :</strong></td>
                <td width="60%"><?php echo $this->validityDays." Days ." ; ?></td>
              </tr>
            <?php endif; ?>
            <?php if(count($this->result)) : ?>
              <tr bgcolor="#cccccc" >
              <td width="60%"><strong>Credit Type</strong></td>
              <td width="40%"><strong>Credit Amount</strong></td></tr>
              <?php foreach($this->result as $item) : ?>
                <tr bgcolor="#e6e6e6">                  
                  <td width="60%">
                    <?php if(!empty($this->creditTypeArray[$item->type])) 
                            echo $this->translate($this->creditTypeArray[$item->type]); 
                          else  
                            echo $item->type;?>
                  </td>
                  <td width="40%"><?php echo abs($item->credit); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </table>
        </td>
      </tr>
    </table>
  </div>
</div>

<?php if( @$this->closeSmoothbox ): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>
