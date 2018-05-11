/* $Id: core_feedbackbutton.js 2010-07-08 9:40:21Z SocialEngineAddOns Copyright 2009-2010 BigStep Technologies Pvt. Ltd. $ */
var FeedbackHandler = new Class({ 
	
	Implements : [Events, Options],
	
  options : {
    debug : false,
    baseUrl : '/',
		feedback_text : 'Feedback',
    enableFeedback : true,
    stylecolor : '#0267cc',
    mouseovercolor : '#ff0000',
    classname : 'smoothbox feedback-button'
    
  },

  initialize : function(options) {
    this.setOptions(options);
    this.rooms = new Hash();
  },
  
  
  start : function() {  	
  	var mouseovercolortemp = this.options.stylecolor;
  	var mouseoutcolortemp = this.options.mouseovercolor;
  	var baseurl = this.options.baseUrl;
    (new Element('a', {
    'href' : baseurl + '/feedbacks/create',     
    'class' : this.options.classname, 'html' : this.options.feedback_text,   
	   'styles': {
	        'background': this.options.stylecolor
	    },    
    	'events': {
      'mouseout': function(){
          this.style.backgroundColor = mouseovercolortemp;
      },
      'mouseover': function(){
         this.style.backgroundColor = mouseoutcolortemp;
      }
    }     
    }).inject(this.container || $('global_content') || document.body));	  	
  }			
});