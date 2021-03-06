<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroupmember
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit-announcement.tpl 2013-03-18 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/styles/style_sitegroup_dashboard.css'); ?>

<?php //if (empty($this->is_ajax)) : ?>
	<?php include_once APPLICATION_PATH . '/application/modules/Sitegroup/views/scripts/payment_navigation_views.tpl'; ?>
	<div class="layout_middle">
		<?php include_once APPLICATION_PATH . '/application/modules/Sitegroup/views/scripts/edit_tabs.tpl'; ?>
		<div class="sitegroup_edit_content">
			<div class="sitegroup_edit_header">
				<a href='<?php echo $this->url(array('group_url' => Engine_Api::_()->sitegroup()->getGroupUrl($this->group_id)), 'sitegroup_entry_view', true) ?>'><?php echo $this->translate('VIEW_GROUP'); ?></a>
				<a style="margin-right:7px;" href='<?php echo $this->url(array('action' => 'announcements', 'group_id' => $this->group_id), 'sitegroup_dashboard', true) ?>'><?php echo $this->translate('Back to Manage Announcements'); ?></a>
				<h3><?php echo $this->translate('Dashboard: ').$this->sitegroup->title; ?></h3>
			</div>
			<div id="show_tab_content">
<?php //endif; ?>

			<div class="sitegroup_post_announcement">
				<?php echo $this->form->render($this); ?>
			</div>
<br />	
<div id="show_tab_content_child">
</div>
<?php //if (empty($this->is_ajax)) : ?>
	</div>
</div>
</div>
<?php //endif; ?>

<script type="text/javascript">

	function updateTextFields(option) {
		if(option == 0) {
			if ($('expirydate-wrapper'))
				$('expirydate-wrapper').style.display='none';
		}
		else {
			if ($('expirydate-wrapper'))
				$('expirydate-wrapper').style.display='block';
		}
	}
</script>
<script type="text/javascript">

  en4.core.runonce.add(function()
  {
    en4.core.runonce.add(function init()
    {
      monthList = [];
      myCal = new Calendar({ 'start_cal[date]': 'M d Y', 'end_cal[date]' : 'M d Y' }, {
        classes: ['event_calendar'],
        pad: 0,
        direction: 0
      });
    });
  });


  en4.core.runonce.add(function(){

    // check end date and make it the same date if it's too
    cal_startdate.calendars[0].start = new Date( document.getElementById('startdate-date').value );
    // redraw calendar
    cal_startdate.navigate(cal_startdate.calendars[0], 'm', 1);
    cal_startdate.navigate(cal_startdate.calendars[0], 'm', -1);

    cal_startdate_onHideStart();
    // cal_endtime_onHideStart();
  });

  var cal_startdate_onHideStart = function() {
    // check end date and make it the same date if it's too
    cal_expirydate.calendars[0].start = new Date( $('startdate-date').value );
    // redraw calendar
    cal_expirydate.navigate(cal_expirydate.calendars[0], 'm', 1);
    cal_expirydate.navigate(cal_expirydate.calendars[0], 'm', -1);
  }
  
  var cal_expirydate_onHideStart = function() {
    // check start date and make it the same date if it's too
    cal_startdate.calendars[0].end = new Date( $('expirydate-date').value );
    // redraw calendar
    cal_startdate.navigate(cal_startdate.calendars[0], 'm', 1);
    cal_startdate.navigate(cal_startdate.calendars[0], 'm', -1);
  }

  window.addEvent('domready', function() {
    if($('expirydate-minute')) {
      $('expirydate-minute').style.display= 'none';
    }
    
    if($('expirydate-ampm')) {
      $('expirydate-ampm').style.display= 'none';
    }
    
    if($('expirydate-hour')) {
      $('expirydate-hour').style.display= 'none';
    }
    
    if($('startdate-minute')) {
      $('startdate-minute').style.display= 'none';
    }
    
    if($('startdate-ampm')) {
      $('startdate-ampm').style.display= 'none';
    }
    
    if($('startdate-hour')) {
      $('startdate-hour').style.display= 'none';
    }
  });
  
	if($('expirydate-minute')) {
		$('expirydate-minute').style.display= 'none';
	}
	
	if($('expirydate-ampm')) {
		$('expirydate-ampm').style.display= 'none';
	}
	
	if($('expirydate-hour')) {
		$('expirydate-hour').style.display= 'none';
	}
	
	if($('startdate-minute')) {
		$('startdate-minute').style.display= 'none';
	}
	
	if($('startdate-ampm')) {
		$('startdate-ampm').style.display= 'none';
	}
	
	if($('startdate-hour')) {
		$('startdate-hour').style.display= 'none';
	}
</script>