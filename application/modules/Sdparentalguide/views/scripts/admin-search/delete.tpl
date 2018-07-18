<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: delete.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>

<form method="post" class="global_form_popup" action="<?= $this->url(array()) ?>">
  <div>
    <h3><?= $this->translate("Delete Search Term?") ?></h3>
    <p>
      <?= $this->translate("Are you sure that you want to delete this Search Term? It will not be recoverable after being deleted.") ?>
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?= $this->badge_id?>"/>
      <button type='submit'><?= $this->translate("Delete") ?></button>
      <?= $this->translate("or") ?>
			<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
				<?= $this->translate("cancel") ?>
			</a>
    </p>
  </div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>
