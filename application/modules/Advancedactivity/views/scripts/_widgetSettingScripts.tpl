<div id="statusBoxDesign-wrapper" class="form-wrapper"><div id="statusBoxDesign-label" class="form-label"><label for="statusBoxDesign" class="optional">Choose the Status (Post) Box design.</label></div>
  <div id="statusBoxDesign-element" class="form-element">
    <select name="statusBoxDesign" id="statusBoxDesign" onchange="showHideAttachmentOption()" >
      <option value="activator_icon">All Attachments Link Icon</option>
      <option value="activator_buttons">All Attachments Links in Buttons with Popup</option>
      <option value="activator_top">All Attachments Links on Top of box</option>
    </select></div></div>
<script type="text/javascript">
  en4.core.runonce.add(function () {
    (function () {
      showHideAttachmentOption();
      showHideAafFeedPhotoBlocks();
    }).delay(300);
  });
  function showHideAttachmentOption() {
    if ($('statusBoxDesign').value == 'activator_icon') {
      $('maxAllowActivator-wrapper').style.display = 'none';
    } else {
      $('maxAllowActivator-wrapper').style.display = 'block';
    }
  }
  function showHideAafFeedPhotoBlocks() {
    if ($('customPhotoBlock').value == 0) {
      $$('.aaf_feed_photo_blocks').setStyle('display', 'none');
    } else {
      $$('.aaf_feed_photo_blocks').setStyle('display', 'block');
    }
  }
</script>
