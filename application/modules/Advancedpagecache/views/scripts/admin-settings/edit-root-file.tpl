<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    edit-root-file.tpl 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class="global_form_popup">
    <h3>Customize Code</h3>
    <br/>
    <?php echo $this->translate("If you will not add the below code still this plugin will work accurately. But we recommend to add this for more efficient results.");?>
    <br /><br />
    <?php echo $this->translate('Step1: Open the file : '); ?>
    <br /><br />
    <div class="code">
        <?php echo APPLICATION_PATH . '/application/index.php'; ?>
    </div>
    
    <br />
    <?php echo $this->translate('Step2: Find the code :'); ?>
    <br /><br />
    <div class="code">
        <?php echo 'if( _ENGINE_R_MAIN ) {<br/> 
                    require dirname(__FILE__) . DS . _ENGINE_R_MAIN;<br/> 
                    exit();<br/> 
                    }'; ?>
    </div>
    <br />
    <?php echo $this->translate('Step3: Add the following code above the searched one') ?>
    <br /><br />
    <div class="code">
        <?php echo 'include_once APPLICATION_PATH_MOD . DS . \'Advancedpagecache/cache.php\';'; ?>
    </div>
    <br />
    <?php echo $this->translate('Step4: You have successfully modified the file.'); ?>
    <br /><br />

    <div style="float: right">
        <button onclick='javascript:parent.Smoothbox.close()'>Cancel</button>
    </div>
</div>

<?php if (@$this->closeSmoothbox): ?>
    <script type="text/javascript">
        TB_close();
    </script>
<?php endif; ?>