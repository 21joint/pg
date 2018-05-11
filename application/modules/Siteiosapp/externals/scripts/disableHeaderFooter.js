window.addEvent('domready', function () {
    // Disable in case of mobi plugin
    if(document.getElementById('global_header'))
        document.getElementById('global_header').style.display = 'none';
    
    if(document.getElementById('global_footer'))
        document.getElementById('global_footer').style.display = 'none';

    if(document.getElementById("cometchat"))
    	document.getElementById('cometchat').style.display = "none";
});