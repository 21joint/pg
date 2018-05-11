<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    index.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<style type="text/css">
    .sitehashtag-search-box .form-elements input[type="text"] {
        width: <?php echo $this->width;?>px;
        outline: none;
    }
</style>

<script>
function checkValidHashtag(){ 
    var text = $('search').value;
    var patt = /\B(#[^\s[!\"\#$%&'()*+,\-.\/\\:;<=>?@\[\]\^`{|}~]+)/g;
    if(!(patt.test(text))){ 
        $('sitehashtag_error').style.display = 'block';
        return false;
    }else{
        $('sitehashtag_error').style.display = 'none';
        return true;
    }
    
    }
</script>

<script type="text/javascript">
en4.core.runonce.add(function()
    {
        
        var contentAutocomplete = new Autocompleter.Request.JSON('search', '<?php echo $this->url(array('action' => 'get-hashtag'), "sitehashtag_general", true) ?>', {
            'postVar': 'text',
            'postData': {'limit': <?php echo $this->limit;?> ,
                         'search_criteria': '<?php echo $this->search_criteria;?>' , },
            'minLength': 2,
            'selectMode': 'pick',
            'autocompleteType': 'tag',
            'className': 'tag-autosuggest',
            'customChoices': true,
            'filterSubset': true,
            'multiple': false,
            'injectChoice': function(token) { 
                if (typeof token.label != 'undefined' && checkValidHashtag()) {
                        var choice = new Element('li', {'class': 'autocompleter-choices',  'id': token.label, 'hashtag_url': token.hashtag_url, onclick: 'javascript:getPageResultsSearch("' + token.hashtag_url + '")'});
                        new Element('div', {'html': this.markQueryValue(token.label), 'class': 'autocompleter-choice'}).inject(choice);
                        this.addChoiceEvents(choice).inject(this.choices);
                        choice.store('autocompleteChoice', token);
                }
            }
        });
    
        contentAutocomplete.addEvent('onSelection', function(element, selected, value, input) { 
            window.addEvent('keyup', function(e) {
                if (e.key == 'enter') {
                    if (selected.retrieve('autocompleteChoice') != 'null') {
                        var url = selected.retrieve('autocompleteChoice').hashtag_url;
      
                            window.location.href = url;
                        
                    }
                }
            });
        });
    });

    

    function getPageResultsSearch(url) {
        if (url != 'null') {
            window.location.href = url;
        }
    }

</script>

<div class="sitehashtag_quick_search">
    <form id = "global_search_form" class="sitehashtag-search-box" action = "<?php echo $this->baseUrl()."/hashtag";?>" method = "get">
	<div class="form-elements">
            <div class="form-wrapper">
                <div class="form-label">&nbsp;</div>
                <div class="form-element">
                        <input name = 'search' type='text' id = 'search' class = 'text suggested'  autocomplete = "off" <?php if(!empty($this->search)){?> value = "<?php echo $this->search;?>"<?php }?> placeholder="<?php echo $this->translate('Enter Hashtag...');?>">
                </div>
            </div>
            <div class="form-wrapper" id="buttons-wrapper">
                <div id="buttons-label" class="form-label">&nbsp;</div>
                <div id="buttons-element" class="form-element">
                    <button type = "submit" onclick="return checkValidHashtag();">search</button>
                </div>
            </div>
        </div>
    </form> 
</div>   
<div id = "sitehashtag_error" class="seaocore_txt_red mtop10" style="display: none">
    <?php echo $this->translate("Please enter the valid hashtag.");?>
</div>



