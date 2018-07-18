<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>
<form method="post" class="global_form_popup">
  <div>
    <h3>Delete Badge?</h3>
    <p>
      Are you sure that you want to delete this Badge entry? It will not be recoverable after being deleted.
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?php echo $this->offer_id?>"/>
      <button type='submit'>Delete</button>
       or
      <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
        cancel</a>
      </p>
    </div>
  </form>

  <?php if( @$this->closeSmoothbox ): ?>
    <script type="text/javascript">
      TB_close();
    </script>
  <?php endif; ?>