<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<h2><?php echo $this->translate("Parental Guidance Customizations") ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='clear'>
    <div class='settings'>
      <?php echo $this->form->render($this); ?>
    </div>
</div>
<script type='text/javascript'>
function switchLifeTime(element){
    var value = $(element).get("value");
    if(value == "1"){
        $("lifetime-wrapper").setStyle("display","block");
    }else{
        $("lifetime-wrapper").setStyle("display","none");
    }
}    
en4.core.runonce.add(function(){
    switchLifeTime($("enable-element").getElement("input:checked"));
});
</script>