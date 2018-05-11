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
  function rating_over(rating) {
    if ($.mobile.activePage.data('rated') == 1) {
      $.mobile.activePage.find('#rating_text').html("<?php echo $this->translate('you have already rated'); ?>");
    }
    else if ( <?php echo $this->viewer_id; ?> === 0) {
      $.mobile.activePage.find('#rating_text').html("<?php echo $this->translate('Only logged-in user can rate'); ?>");
    }
    else {
      $.mobile.activePage.find('#rating_text').html("<?php echo $this->translate('Please click to rate'); ?>");
      for (var x = 1; x <= 5; x++) {
        if (x <= rating) {
          $.mobile.activePage.find('#rate_' + x).attr('class', 'rating_star_big_generic rating_star_big');
        } else {
          $.mobile.activePage.find('#rate_' + x).attr('class', 'rating_star_big_generic rating_star_big_disabled');
        }
      }
    }
  }

  function rating_out() {
    $.mobile.activePage.find('#rating_text').html(" <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count), $this->locale()->toNumber($this->rating_count)) ?>");
       if ($.mobile.activePage.data('pre_rate') !== 0) {
         set_rating();
       }
       else {
         for (var x = 1; x <= 5; x++) {
           $.mobile.activePage.find('#rate_' + x).attr('class', 'rating_star_big_generic rating_star_big_disabled');
         }
       }
  }

  function set_rating() {
    var rating = $.mobile.activePage.data('pre_rate');
        var current_total_rate = $.mobile.activePage.data('current_total_rate');
        if (current_total_rate) {
          var current_total_rate = $.mobile.activePage.data('current_total_rate');
          if (current_total_rate === 1) {
            $.mobile.activePage.find('#rating_text').html(current_total_rate + '<?php echo $this->string()->escapeJavascript($this->translate(" rating")) ?>');
          }
          else {
            $.mobile.activePage.find('#rating_text').html(current_total_rate + '<?php echo $this->string()->escapeJavascript($this->translate(" rating")) ?>');
          }
        }
        else {
          $.mobile.activePage.find('#rating_text').html("<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count), $this->locale()->toNumber($this->rating_count)) ?>");
        }

        for (var x = 1; x <= parseInt(rating); x++) {
          $.mobile.activePage.find('#rate_' + x).attr('class', 'rating_star_big_generic rating_star_big');
        }

        for (var x = parseInt(rating) + 1; x <= 5; x++) {
          $.mobile.activePage.find('#rate_' + x).attr('class', 'rating_star_big_generic rating_star_big_disabled');
        }

        var remainder = Math.round(rating) - rating;
        if (remainder <= 0.5 && remainder != 0) {
          var last = parseInt(rating) + 1;
          $.mobile.activePage.find('#rate_' + last).attr('class', 'rating_star_big_generic rating_star_big_half');
        }
  }

  function faqRate(rating,faq_id) {
    $.mobile.activePage.find('#rating_text').html("<?php echo $this->translate('Thank you for rating!'); ?>");
    for (var x = 1; x <= 5; x++) {
      $.mobile.activePage.find('#rate_' + x).attr('onclick', '');
    }
    sm4.core.request.send({
      type: "POST",
      dataType: "json",
      'url': '<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'rating'), 'default', true) ?>',
      'data': {
        'format': 'json',
        'rating': rating,
        'faq_id': faq_id
      },
      beforeSend: function() {
        $.mobile.activePage.data('rated', 1);
        var total_votes = $.mobile.activePage.data('total_votes');
        total_votes = total_votes+1;
        var pre_rate = ($.mobile.activePage.data('pre_rate') + rating) / total_votes;
        $.mobile.activePage.data('total_votes', total_votes);
        $.mobile.activePage.data('pre_rate', pre_rate);
        set_rating();
      },
      success: function(response)
      {add(set_rating);
        $.mobile.activePage.find('#rating_text').html(response[0].total + '<?php echo $this->string()->escapeJavascript($this->translate("rating")) ?>');
        $.mobile.activePage.data('current_total_rate', response[0].total);
      }
    });

  }

  sm4.core.runonce.add(function() {
    $.mobile.activePage.data('pre_rate',<?php echo $this->sitefaq->rating; ?>);
    $.mobile.activePage.data('rated', '<?php echo $this->sitefaq_rated; ?>');
    $.mobile.activePage.data('total_votes',<?php echo $this->rating_count; ?>);
    set_rating();
  });

  function tagAction(tag){
    $.mobile.activePage.find('#tag').val(tag);
    $.mobile.activePage.find('#filter_form').submit();
  }

</script>

        <?php if (!empty($this->viewer_id) && !empty($this->can_rate)): ?>
            <div class="sm-ui-video-rating t_l">
                <div id="video_rating" class="rating" onmouseout="rating_out();" valign="top">
                    <span id="rate_1" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(1,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(1);"></span>
                    <span id="rate_2" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(2,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(2);"></span>
                    <span id="rate_3" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(3,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(3);"></span>
                    <span id="rate_4" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(4,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(4);"></span>
                    <span id="rate_5" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(5,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(5);"></span>
                    <div id="rating_text" class="rating_text"><?php echo $this->translate('click to rate'); ?></div>
                </div>
            </div>

        <?php elseif ($this->sitefaq->rating > 0): ?>
            <?php
            $currentRatingValue = $this->sitefaq->rating;
            $difference = $currentRatingValue - (int) $currentRatingValue;
            if ($difference < .5) {
                $finalRatingValue = (int) $currentRatingValue;
            } else {
                $finalRatingValue = (int) $currentRatingValue + .5;
            }
            ?>
            <div class="sm-ui-video-rating t_l">
                <div id="video_rating" class="rating" onmouseout="rating_out();" valign="top">
                    <span id="rate_1" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(1,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(1);"></span>
                    <span id="rate_2" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(2,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(2);"></span>
                    <span id="rate_3" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(3,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(3);"></span>
                    <span id="rate_4" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(4,<?php echo $this->sitefaq->faq_id; ?>);"<?php endif; ?> onmouseover="rating_over(4);"></span>
                    <span id="rate_5" class="rating_star_big_generic" <?php if (!$this->sitefaq_rated && $this->viewer_id): ?> onclick="faqRate(5,<?php echo $this->sitefaq->faq_id; ?>);;"<?php endif; ?> onmouseover="rating_over(5);"></span>
                    <div id="rating_text" class="rating_text"><?php echo $this->translate('click to rate'); ?></div>
                </div>
            </div>
        <?php endif; ?>	
