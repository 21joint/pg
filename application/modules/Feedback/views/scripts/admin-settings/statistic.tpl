<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: statistic.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Feedback Plugin');?></h2>

<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'> <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?> </div>
<?php endif; ?>

<div class='clear'>
  <div class='settings'>
    <form class="global_form" style="width:50%;">
      <div>
        <h3><?php echo $this->translate('Feedback Statistics');?></h3>
        <p class="description"> <?php echo $this->translate('Below are some valuable statistics for the Feedback submitted on this site:');?> </p>
        <table class='admin_table' width="100%">
          <tbody>
            <tr>
            	<td class="admin_table_bold" width="50%"><?php echo $this->translate('Total feedback');?> :</td>
            	<td class="admin_table_bold"><?php echo $this->statistics['total_feedback']; ?></td>
            </tr>
            <tr>
            	<td class="admin_table_bold"><?php echo $this->translate('Total Public Feedback');?> :</td>
            	<td class="admin_table_bold"><?php echo $this->statistics['total_public']; ?></td>
            </tr>
            <tr>
            	<td class="admin_table_bold"><?php echo $this->translate('Total Private Feedback');?> :</td>
            	<td class="admin_table_bold"><?php echo $this->statistics['total_private']; ?></td>
            </tr>
            <tr>
            	<td class="admin_table_bold"><?php echo $this->translate('Total Anonymous Feedback');?> :</td>
            	<td style="font-weight:bold;"><?php echo $this->statistics['total_anonymous']; ?></td>
            </tr>
            <tr>
            	<td class="admin_table_bold"><?php echo $this->translate('Total Comments');?> :</td>
            	<td class="admin_table_bold">
	            	<?php echo $this->statistics['total_comments']; ?>
            	</td>
            </tr>
            <tr>
            	<td class="admin_table_bold"><?php echo $this->translate('Total Votes');?> :</td>
            	<td class="admin_table_bold">
            		<?php echo $this->statistics['total_votes'] ?>	
            	</td>
            </tr>	
          </tbody>
        </table>
    </form>
  </div>
</div>