<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="add-from-store">
  <div id="sitereaction_store_list">
    <div class="store-top">
        <h1><i class="fa fa-cart-arrow-down" aria-hidden="true"></i><?php echo $this->translate("Sticker Store") ?> </h1>
        <p><?php echo $this->translate("Add these new stickers to make your comments interesting.") ?></p>
         <button  onclick="javascript:SmoothboxSEAO.close()"><?php echo $this->translate("x"); ?></button>
    </div>
    <div class="store-bottom">
      <?php $page = 1 ?>
      <?php foreach ($this->paginator as $item): ?>
        <div class="store_collection_row">
          <div class="store_collection_thumb">
            <?php echo $this->itemPhoto($item, '', '', array('class' => 'sitereaction_store_colleaction_preview_action', 'data-page'=> $page)) ?>
             <div class="store_collection_stickers">
              <?php $count = 0; ?>
              <?php foreach ($item->getStickers() as $sticker): ?>
                <span class="sitereaction_store_icon sitereaction_store_colleaction_preview_action" data-page="<?php echo $page ?>" >
                  <i style="background-image: url('<?php echo $sticker->getPhotoUrl() ?>')"> </i>
                </span>
                <?php if (++$count === 8): break;
                endif;
                ?>
  <?php endforeach; ?>
            </div>
          </div>
          <div class="store_collection_info">
            <div class="store_collection_info_title">
              <?php echo $item->getTitle() ?>
            </div>
            
            <?php if (!$item->include): ?>
              <div class='store_collection_action'>
                <button class="sitereaction_store_colleaction_preview sitereaction_store_colleaction_preview_action" data-page="<?php echo $page++ ?>"> <?php echo $this->translate("Preview"); ?></button>
                <button id="sitereaction_add_remove_<?php echo $item->getGuid() ?>" class="sitereaction_store_add_remove" data-collection-id="<?php echo $item->getIdentity() ?>" data-action-remove="<?php echo $this->translate("Remove") ?>" data-action-add="<?php echo $this->translate("Add") ?>" data-added="<?php echo (int) in_array($item->getIdentity(), $this->collectionIds) ?>">
                  <?php echo in_array($item->getIdentity(), $this->collectionIds) ? $this->translate("Remove") : $this->translate("Add"); ?>
                </button>
              </div>
            <?php endif; ?>
           
          </div>
        </div>
<?php endforeach; ?>
    </div>
  </div>
  <div id="sitereaction_store_content_block" class="dnone">
    <div id="sitereaction_store_back_link">
        <?php echo $this->translate("Stickers Store"); ?>
    </div>
    <div id="sitereaction_store_list_loader" class="dnone">
      <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/loading.gif' alt="Loading" />
    </div>
    <div id="sitereaction_store_content">
    </div>

  </div>
</div>
<script>
  en4.core.runonce.add(function () {
      var anchor = $('sitereaction_store_content');
      $('sitereaction_store_back_link').removeEvents('click').addEvent('click', function () {
          $('sitereaction_store_list').removeClass('dnone');
          $('sitereaction_store_content_block').addClass('dnone');
          $('sitereaction_store_list_loader').addClass('dnone');
      });

      $$('.sitereaction_store_colleaction_preview_action').removeEvents('click').addEvent('click', function () {
          $('sitereaction_store_list').addClass('dnone');
          anchor.empty();
          $('sitereaction_store_content_block').removeClass('dnone');
          $('sitereaction_store_list_loader').removeClass('dnone');
          var req = new Request.HTML({
              url: en4.core.baseUrl + 'sitereaction/store',
              data: {
                  format: 'html',
                  page: this.get('data-page')
              }
          });
          req.addEvent('success', function () {
              $('sitereaction_store_list_loader').addClass('dnone');
          });
          en4.core.request.send(req, {
              'element': anchor,
              'force': true,
          })
      });
      $$('.sitereaction_store_add_remove').removeEvents('click').addEvent('click', function () {
          var urlAdd = '<?php echo $this->url(array('action' => 'add')) ?>';
          var urlRemove = '<?php echo $this->url(array('action' => 'remove')) ?>';
          var self = this;

          if (self.get('data-added') == 1) {
              self.set('html', self.get('data-action-add'));
          } else {
              self.set('html', self.get('data-action-remove'));
          }
          var req = new Request.JSON({
              url: self.get('data-added') == 1 ? urlRemove : urlAdd,
              data: {
                  format: 'json',
                  collection_id: self.get('data-collection-id'),
              }
          });
          req.addEvent('success', function (response) {
              self.removeClass('dnone');
              if ($('stickers_layer_dummy')) {
                  var el = new Element('div', {
                      html: response.collectionBody
                  });
                  $('stickers_layer_dummy').set('html', el.getElement('.stickers_layer').get('html'));
              }
          });
          self.set('data-added', self.get('data-added') == 1 ? 0 : 1);
          en4.core.request.send(req, {
              'force': true
          })
      });
  });

</script>

