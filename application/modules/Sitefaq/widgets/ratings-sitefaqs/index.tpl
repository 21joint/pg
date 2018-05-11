<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">

  var viewer = <?php echo $this->viewer_id;?>;
  var sitefaq_rate_previous = <?php echo $this->sitefaq->rating;?>;
  var faq_id = <?php echo $this->sitefaq->faq_id;?>;
  var sitefaq_total_rating = <?php echo $this->rating_count;?>;
  var sitefaq_rated = '<?php echo $this->sitefaq_rated;?>';
	var check_rating = 0;
	var current_total_rate;
	var rating_var = '<?php echo $this->string()->escapeJavascript($this->translate(" rating")) ?>';
	
  function rate(rating) {
	    $('rating_text').innerHTML = "<?php echo $this->translate('Thank you for rating to this FAQ!');?>";
	    for(var x=1; x<=5; x++) {
	      $('rate_'+x).set('onclick', '');
	    }
	    (new Request.JSON({
	      'format': 'json',
	      'url' : '<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'rating'), 'default', true) ?>',
	      'data' : {
	        'format' : 'json',
	        'rating' : rating,
	        'faq_id': faq_id
	      },
	      'onRequest' : function(){
	        sitefaq_rated = 1;
	        sitefaq_total_rating = sitefaq_total_rating + 1;
	        sitefaq_rate_previous = (sitefaq_rate_previous+rating)/sitefaq_total_rating;
	        sitefaq_set_rating();
	      },
	      'onSuccess' : function(responseJSON, responseText)
	      {
	        $('rating_text').innerHTML = responseJSON[0].total+rating_var;
	        current_total_rate = responseJSON[0].total;
	        check_rating = 1;
	      }
	    })).send();
	    
	  }
  
  function sitefaq_rating_out() {
	  $('rating_text').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
    if (sitefaq_rate_previous != 0){
      sitefaq_set_rating();
    }
    else {
      for(var x=1; x<=5; x++) {
        $('rate_'+x).set('class', 'rating_star_big_generic rating_star_big_disabled');
      }
    }
  }

  function sitefaq_set_rating() {
    var rating = sitefaq_rate_previous;
    if(check_rating == 1) {
      if(current_total_rate == 1) {
    	  $('rating_text').innerHTML = current_total_rate+rating_var;
      }
      else {      
		  	$('rating_text').innerHTML = current_total_rate+rating_var;
    	}
	  }
	  else { 
    	$('rating_text').innerHTML = "<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
	  }
    for(var x=1; x<=parseInt(rating); x++) {
      $('rate_'+x).set('class', 'rating_star_big_generic rating_star_big');
    }

    for(var x=parseInt(rating)+1; x<=5; x++) {
      $('rate_'+x).set('class', 'rating_star_big_generic rating_star_big_disabled');
    }

    var remainder = Math.round(rating)-rating;
    if (remainder <= 0.5 && remainder !=0){
      var last = parseInt(rating)+1;
      $('rate_'+last).set('class', 'rating_star_big_generic rating_star_big_half');
    }
  }

  function sitefaq_rating_over(rating) {
	    if (sitefaq_rated == 1){
	      $('rating_text').innerHTML = "<?php echo $this->translate('you have already rated this FAQ');?>";
	      //sitefaq_set_rating();
	    }
	    else if (viewer == 0){
	      $('rating_text').innerHTML = "<?php echo $this->translate('Only logged-in user can rate');?>";
	    }
	    else{
	      $('rating_text').innerHTML = "<?php echo $this->translate('Please click to rate');?>";
	      for(var x=1; x<=5; x++) {
	        if(x <= rating) {
	          $('rate_'+x).set('class', 'rating_star_big_generic rating_star_big');
	        } else {
	          $('rate_'+x).set('class', 'rating_star_big_generic rating_star_big_disabled');
	        }
	      }
	    }
	  }
	  
  en4.core.runonce.add(sitefaq_set_rating);

</script>

<ul>
  <li>
  	<span class="sitefaq_widget_label"><?php echo $this->translate('Rating:');?></span>
		<?php if(!empty($this->viewer_id) && !empty($this->can_rate)): ?>
			<div id="sitefaq_rating" class="rating sitefaq_rating" onmouseout="sitefaq_rating_out();">
				<?php for($i = 1; $i <= 5; $i++): ?>
				<span id="rate_<?php echo $i; ?>" <?php if (!$this->sitefaq_rated && $this->viewer_id):?>onclick="rate(<?php echo $i; ?>);"<?php endif; ?> onmouseover="sitefaq_rating_over(<?php echo $i; ?>);"></span>
				<?php endfor;?>
				<br/>
				<span id="rating_text" class="rating_text"><?php echo $this->translate('click to rate');?></span>
			</div>
		<?php elseif($this->sitefaq->rating > 0): ?>
			<?php 
				$currentRatingValue = $this->sitefaq->rating;
				$difference = $currentRatingValue- (int)$currentRatingValue;
				if($difference < .5) {
					$finalRatingValue = (int)$currentRatingValue;
				}
				else {
					$finalRatingValue = (int)$currentRatingValue + .5;
				}	
			?>
			<div id="sitefaq_rating" class="rating sitefaq_rating" onmouseout="sitefaq_rating_out();">
				<?php for($i = 1; $i <= 5; $i++): ?>
					<span id="rate_<?php echo $i;?>" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>"></span>
				<?php endfor;?>
				<br/>
				<span id="rating_text" class="rating_text"><?php echo $this->translate('click to rate');?></span>
			</div>
		<?php endif; ?>	
  </li>
</ul>