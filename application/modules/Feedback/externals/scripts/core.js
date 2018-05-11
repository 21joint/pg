/* $Id: core.js 2010-07-08 9:40:21Z SocialEngineAddOns Copyright 2009-2010 BigStep Technologies Pvt. Ltd. $ */
en4.feedback = {

};

en4.feedback.voting = {
// FUNCTION FOR CREATING A FEEDBACK 
createVote : function(viewer_id, feedback_id)
{ 
  var request = new Request.JSON({    
    url : en4.core.baseUrl + 'feedback/vote/vote',
    data : {
      format : 'json',
      viewer_id : viewer_id,
      feedback_id : feedback_id
    }
  });  
  request.send();
  return request;
},

removeVote : function(vote_id, feedback_id, viewer_id)
{ 
  var request = new Request.JSON({    
	url : en4.core.baseUrl + 'feedback/vote/removevote',
    data : {
      format : 'json',
      vote_id : vote_id,
      viewer_id : viewer_id,
      feedback_id : feedback_id
    }
  });  
  request.send();
  return request;
}

}

var Feedback_buttonHandler = new Class({ 
	
	start : function() {
    this.state = true;
    
    if( this.options.enableIM ) {
      this.startIm(this.options.imOptions);
    }

    if( this.options.enableFeedback_button ) {
      this.startFeedback_button(this.options.feedback_buttonOptions);
    }

    this.addEvent('onEvent_reconfigure', this.onReconfigure.bind(this));
    
    // Do idle checking
    this.idleWatcher = new IdleWatcher(this, {timeout : this.options.idleTimeout});
    this.idleWatcher.register();
    this.addEvents({
      'onStateActive' : function() {
        this.activestate = 1;
        this.status(1);
      }.bind(this),
      'onStateIdle' : function() {
        this.activestate = 0;
        this.status(2);
      }.bind(this)
    });
    
    this.loop();
  },
  
  onReconfigure : function(data) {
    if( $type(data.delay) ) {
      this.options.delay = data.delay;
    }
    if( $type(data.feedback_button_enabled) ) {
      // Disabling feedback_button
      if( parseInt(data.feedback_button_enabled) == 0 ) {
        this.rooms.each(function(room) {
          room.destroy();
        });
        (new Element('a', {
                'href' : this.handler.options.baseUrl + 'feedbacks/create',
                'class' : 'feedback-button',
                'html' : 'feedback',
                'style' : "background:#0066cc;",
                'onmouseover' : "this.style.backgroundColor='#ff0000';",
                'onmouseout' : "this.style.backgroundColor='#0066cc';"
              
            }).inject(this.container || $('global_content') || document.body));
      }
      // Enabling feedback_button
      else
      {
        // dont do anything
      }
    }
    if( $type(data.im_enabled) ) {
      if( parseInt(data.im_enabled) == 0 ) {
        if( $type(this.im) ) {
          this.im.destroy();
        }
      }
    }
  },
	  
});