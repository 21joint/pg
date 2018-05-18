<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div>
  <div class="sitereaction_store_popup_top">
    <div id="topfix">
      <button  onclick="javascript:SmoothboxSEAO.close()"><?php echo $this->translate("x"); ?></button>
        <div id="sitereaction_store_colleaction_info">
            <?php foreach ($this->paginator as $item): ?>
              <div class="store_collection">
                <div class="store_collection_row">
                  <div class="store_collection_thumb">
                    <?php echo $this->itemPhoto($item) ?>
                  </div>
                  <div class="store_collection_info">
                    <div class="store_collection_info_title">
                      <?php echo $item->getTitle() ?>
                    </div>
                    <div class='store_collection_info_body'>
                      <?php echo $this->string()->stripTags($item->body) ?>
                    </div>
                    <?php if (!$item->include): ?>
                    <div class='store_collection_action'>
                      <button id="sitereaction_store_add_remove"><?php echo $this->isAdded ? $this->translate("Remove") : $this->translate("Add"); ?></button>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
            <?php endforeach; ?>
          </div>
      </div>
    </div>
  <div class="sitereaction_store_popup">
    <div class="sitereaction_store_popup_content">
      <div id="sitereaction_store_loader" class="sitereaction_store_popup_loader dnone">
        <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/loading.gif' alt="Loading" />
      </div>
      <div id="sitereaction_store_colleaction">
        <?php foreach ($this->paginator as $item): ?>
          <div class="store_collection">
            <div class="store_collection_stickers">
              <?php foreach ($item->getStickers() as $sticker): ?>
                <span class="sitereaction_store_icon">
                  <i style="background-image: url('<?php echo $sticker->getPhotoUrl() ?>')"> </i>
                </span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="sitereaction_store_popup_bottom">
 
         <div id="sitereaction_store_previous" class="paginator_previous">
          <?php
          echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
            'onclick' => '',
            'class' => 'buttonlink icon_previous'
          ));
          ?>
        </div>
        <div id="sitereaction_store_next" class="paginator_next">
          <?php
          echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
            'onclick' => '',
            'class' => 'buttonlink_right icon_next'
          ));
          ?>
        </div>
  </div>
</div>
<script>
  en4.core.runonce.add(function() {
    var anchor = $('sitereaction_store_content');
    $('sitereaction_store_previous').style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
    $('sitereaction_store_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';
    $('sitereaction_store_previous').removeEvents('click').addEvent('click', function() {
      $('sitereaction_store_colleaction').addClass('dnone');
      $('sitereaction_store_colleaction_info').addClass('dnone');
      
      $('sitereaction_store_loader').removeClass('dnone');
      en4.core.request.send(new Request.HTML({
        url: en4.core.baseUrl + 'sitereaction/store',
        data: {
          format: 'html',
          page: <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>
        }
      }), {
        'element': anchor,
        'force': true,
      })
    });

    $('sitereaction_store_next').removeEvents('click').addEvent('click', function() {
      $('sitereaction_store_colleaction').addClass('dnone');
      $('sitereaction_store_colleaction_info').addClass('dnone');
      $('sitereaction_store_loader').removeClass('dnone');
      en4.core.request.send(new Request.HTML({
        url: en4.core.baseUrl + 'sitereaction/store',
        data: {
          format: 'html',
          page: <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
        }
      }), {
        'element': anchor,
        'force': true,
      })
    });
    $('sitereaction_store_add_remove').removeEvents('click').addEvent('click', function() {
    var req = new Request.JSON({
        url: '<?php echo $this->url(array('action' => $this->isAdded ? 'remove' : 'add')) ?>',
        data: {
          format: 'json',
          collection_id: '<?php echo $item->getIdentity() ?>',
          page: <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>
        }
      });

      req.addEvent('success', function(response){
        if($('stickers_layer_dummy')) {
          var el = new Element('div', {
           html: response.collectionBody
          });
          $('stickers_layer_dummy').set('html', el.getElement('.stickers_layer').get('html'));
        }
        var button = $("sitereaction_add_remove_<?php echo $item->getGuid() ?>");
          if (button) {
             <?php if ($this->isAdded): ?>
              button.set('html', button.get('data-action-add'));
              button.set('data-added', 0);
              <?php else: ?>
              button.set('html', button.get('data-action-remove'));
              button.set('data-added', 1);
            <?php endif; ?>
          }
      });
      this.empty();
      en4.core.loader.clone().inject(this);
      en4.core.request.send(req, {
        'force': true,
        'element': anchor
      })
    });
  });

</script>

