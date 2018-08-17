<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php if ($this->subject() && $this->subject() instanceof User_Model_User): ?>
  <script type="text/javascript">
    en4.core.runonce.add(function () {
      let tab = document.getElementById('main_tabs');
      tab.addClass('container');
      tab.addClass('m-auto');
    });
  </script>
<?php else: ?>
  <script type="text/javascript">
    en4.core.runonce.add(function () {
      var tabContainerSwitch = window.tabContainerSwitch = function (element) {
        if (element.tagName.toLowerCase() == 'a') {
          element = element.getParent('li');
        }

        var myContainer = element.getParent('.tabs_parent').getParent();
        element.getParent('.tabs_parent').addClass('tab_collapsed');
        myContainer.getChildren('div:not(.tabs_alt)').setStyle('display', 'none');
        myContainer.getElements('ul > li').removeClass('active');
        element.get('class').split(' ').each(function (className) {
          className = className.trim();
          if (className.match(/^tab_[0-9]+$/)) {
            myContainer.getChildren('div.' + className).setStyle('display', null);
            element.addClass('active');
          }
        });

      }
      var moreTabSwitch = window.moreTabSwitch = function (el) {
        el.toggleClass('tab_open');
        el.toggleClass('tab_closed');
      }
      $$('.tab_collapsed_action').addEvent('click', function (event) {
        event.target.getParent('.tabs_alt').toggleClass('tab_collapsed');
      });
    });
  </script>
<?php endif; ?>

  <div class='tabs_alt tabs_parent tab_collapsed'>
    <span class="tab_collapsed_action"></span>
    <ul class="nav nav-tabs" id="prg-profile--tabs" role="tablist">
      <?php foreach ($this->tabs as $key => $tab): ?>
        <?php if ($key < $this->max): ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" role="tab" id="profileTab<?= $tab['id'] ?>"><?php echo $this->translate($tab['title']) ?>
              <?php if (!empty($tab['childCount'])): ?>
                <span>(<?php echo $tab['childCount'] ?>)</span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php if (count($this->tabs) > $this->max): ?>
        <li class="tab_closed more_tab" onclick="moreTabSwitch($(this));">
          <div class="tab_pulldown_contents_wrapper">
            <div class="tab_pulldown_contents">
              <ul>
                <?php foreach ($this->tabs as $key => $tab): ?>
                  <?php
                  $class = array();
                  $class[] = 'tab_' . $tab['id'];
                  $class[] = 'tab_' . trim(str_replace('generic_layout_container', '', $tab['containerClass']));
                  if ($this->activeTab == $tab['id'] || $this->activeTab == $tab['name']) $class[] = 'active';
                  $class = join(' ', array_filter($class));
                  ?>
                  <?php if ($key >= $this->max): ?>
                    <li class="<?php echo $class ?>"
                        onclick="tabContainerSwitch($(this), '<?php echo $tab['containerClass'] ?>')"><?php echo $this->translate($tab['title']) ?><?php if (!empty($tab['childCount'])): ?>
                        <span> (<?php echo $tab['childCount'] ?>)</span><?php endif; ?></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <a href="javascript:void(0);"><?php echo $this->translate('More +') ?><span></span></a>
        </li>
      <?php endif; ?>
    </ul>
  </div>

<?php echo $this->childrenContent ?>
