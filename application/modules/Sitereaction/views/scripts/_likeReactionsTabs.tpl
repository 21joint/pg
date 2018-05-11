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
<ul class="reaction_tabs">
  <li <?php if (!$this->selected): ?> class="active" <?php endif; ?>>
    <?php if ($this->class == 'Advancedactivity_Model_Action'): ?>
      <a href="<?php
      echo $this->url(array('module' => 'sitereaction', 'action' => 'likes',
        'controller' => 'index', 'type' => 'advancedactivity', 'subject_type' => $this->subject->getType(),
        'subject_id' => $this->subject->getIdentity()), 'default', true)
      ?>" class="aff_reaction_tab" data-target="<?php echo 'all' ?>">
        <span class='inline'>
          <span>
  <?php echo $this->translate("All"); ?>
          </span>
        </span>
      </a>
       <?php else: ?>
      <a href="<?php echo $this->url(array('module' => 'sitereaction', 'action' => 'likes',
         'controller' => 'index', 'subject_type' => $this->subject->getType(), 'subject_id' => $this->subject->getIdentity()), 'default', true)
       ?>" class="aff_reaction_tab" data-target="<?php echo 'all' ?>">
        <span class='inline'>
          <span>
      <?php echo $this->translate("All"); ?>
          </span>
        </span>
      </a>
    <?php endif; ?>
  </li>
    <?php foreach ($this->popularity as $icon): ?>
    <li <?php if ($this->selected === $icon['type']): ?> class="active" <?php endif; ?>>
         <?php if ($this->class == 'Advancedactivity_Model_Action'): ?>
        <a href="<?php
       echo $this->url(array('module' => 'sitereaction', 'action' => 'likes',
         'controller' => 'index', 'type' => 'advancedactivity', 'subject_type' => $this->subject->getType(),
         'subject_id' => $this->subject->getIdentity(), 'reaction' => $icon['type']), 'default', true)
           ?>" class="aff_reaction_tab" title="<?php echo $this->translate($icon['caption']) . " (" . $icon['count'] . ")" ?>" data-target="<?php echo $icon['type'] ?>">
          <span class='inline'>
            <span>
              <i style="background-image: url(<?php echo $icon['icon'] ?>)" ></i>
        <?php echo $icon['count'] ?>
            </span>
          </span>
        </a>
  <?php else: ?>
        <a href="<?php
              echo $this->url(array('module' => 'sitereaction', 'action' => 'likes',
                'controller' => 'index', 'subject_type' => $this->subject->getType(),
                'subject_id' => $this->subject->getIdentity(), 'reaction' => $icon['type']), 'default', true)
              ?>" class="aff_reaction_tab" title="<?php echo $this->translate($icon['caption']) . " (" . $icon['count'] . ")" ?>" data-target="<?php echo $icon['type'] ?>">
          <span class='inline'>
            <span>
              <i style="background-image: url(<?php echo $icon['icon'] ?>)" ></i>
    <?php echo $icon['count'] ?>
            </span>
          </span>
        </a>
  <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
