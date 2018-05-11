<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: guidline.tpl 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>
  <?php echo $this->translate('Minify Plugin - Speed up your Website') ?>
</h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<div class='clear'>
</div>

<div class='' style="margin-top:15px;">
  <h3>Guidline For Gzip compression</h3>
  <p>Please follow the steps below to do the minor modifications required to enable proper Gzip compression on your site.</p>
  <br /><br />
  <div class='tip'>
    <span>
      NOTE: Whenever you will upgrade SocialEngine Core for your site, these changes will be overwritten and they will then have to be re-applied in the respective files as mentioned below.
    </span>
  </div>

  <div class="global_form_popup">

    <p>
      <span style="font-weight: bold">Step 1:</span> Open the file: <?php echo APPLICATION_PATH . '/.htaccess' ?>.<br />
    </p>
    <br />
    <p>
      <span style="font-weight: bold">Step 2:</span> Now, place the below code at the end of file: <br /> <br />
    <div id="code-wapperbox" style="overflow: hidden; margin: 1em auto">
      <div class="code-wapper">
        <?php echo nl2br(htmlentities(file_get_contents(APPLICATION_PATH . '/application/modules/Siteminify/settings/htaccess_code.txt'))); ?>
        </br >
      </div>
    </div>
    </p>

  </div>
  <div id="tooltip">
    Copied!
  </div>
  <style>

    .code-wapper {
      float: left;
      margin-right: 8px;
      border: 1px solid orange;
      background: lightyellow;
      padding: 7px;
      width: 700px;
    }
    #tooltip {
      padding: 5px;
      background: black;
      color: white;
      display: inline-block;
      z-index: 5;
      position: fixed;
      opacity: 0;
      visibility: hidden;
      font-size: 20px;
    }
  </style>
  <script type="text/javascript">




    function selectElementText(el) {
      var range = document.createRange(); // create new range object
      range.selectNodeContents(el); // set range to encompass desired element text
      var selection = window.getSelection(); // get Selection object from currently user selected text
      selection.removeAllRanges(); // unselect any user selected text (if any)
      selection.addRange(range);
      // add range to Selection object to select it
    }
    function copySelectionText() {
      var copysuccess; // var to check whether execCommand successfully executed
      try {
        copysuccess = document.execCommand("copy"); // run command to copy selected te xt to clipboard
      } catch (e) {
        copysuccess = false;
      }
      return copysuccess;
    }
    var codeWapperbox = document.getElementById('code-wapperbox');
    codeWapperbox.addEventListener('mouseup', function (e) {
      var e = e || event; // equalize event object between modern and older IE browsers
      var target = e.target || e.srcElement; // get target element mouse is over
      if (target.className == 'code-wapper') {
        selectElementText(target); // select the element's text we wish to read
        var copysuccess = copySelectionText();
        if (copysuccess) {
          showtooltip(e);
        }
      }
    }, false);

    function showtooltip(e) {
      $('tooltip').setStyles({
        left: e.x - 30,
        top: e.y + 40
      });
      $('tooltip').fade('in');
      (function () {
        $('tooltip').fade('out')
      }).delay(2000);
    }

  </script>