<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>
<div class="badge_popup global_form_popup ">
	<table>
		<tr><td colspan="2" style="text-align: right; border-bottom: 1px solid #ddd;"><a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><strong>Close X</strong></a></td></tr>
		<tr>
			<td>
				<div class="photo">
					<?= $this->itemPhoto($this->badge, 'thumb.profile')?>
				</div> 
			</td>
			<td> 
				<div class="credit_points">
					<strong>Credit Values :</strong> <?= $this->badge->credit_count?>
				</div>
				<div class="desc"><h4><?= $this->badge->description?></h4></div>
			</td>
		</tr>
	</table>
	<input type="hidden" name="confirm" value="<?= $this->id?>"/>
</div>
<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
		TB_close();
	</script>
<?php endif; ?>