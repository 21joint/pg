<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 10247 2014-05-30 21:34:25Z andres $
 * @author     John
 */
?>

<?php
  $this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . '/externals/uploader/uploader.css');
?>

<script type="text/javascript">
  var fileData = <?php echo Zend_Json::encode($this->contents) ?>;
  var absBasePath = '<?php echo (_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $this->baseUrl() . '/public/admin/'; ?>';

  var fileCopyUrl = function(arg)
  {
    var fileInfo = fileData[arg];
    Smoothbox.open('<div><input type=\'text\' style=\'width:400px\' /><br /><br /><button onclick="Smoothbox.close();">Close</button></div>', {autoResize : true});
    Smoothbox.instance.content.getElement('input').set('value', absBasePath + fileInfo['rel']).focus();
    Smoothbox.instance.content.getElement('input').select();
    Smoothbox.instance.doAutoResize();
  }

  var uploadFile = function()
  {
    $('upload_file').click();
  }

  var previewFileForceOpen;
  var previewFile = function(event)
  {
    event = new Event(event);
    element = $(event.target).getParent('.admin_file').getElement('.admin_file_preview');
    
    // Ignore ones with no preview
    if( !element || element.getChildren().length < 1 ) {
      return;
    }

    if( event.type == 'click' ) {
      if( previewFileForceOpen ) {
        previewFileForceOpen.setStyle('display', 'none');
        previewFileForceOpen = false;
      } else {
        previewFileForceOpen = element;
        previewFileForceOpen.setStyle('display', 'block');
      }
    }
    if( previewFileForceOpen ) {
      return;
    }

    var targetState = ( event.type == 'mouseover' ? true : false );
    element.setStyle('display', (targetState ? 'block' : 'none'));
  }

  window.addEvent('load', function() {
    $$('.admin_file_name').addEvents({
      click : previewFile,
      mouseout : previewFile,
      mouseover : previewFile
    });
    $$('.admin_file_preview').addEvents({
      click : previewFile
    });
  });

var BaseFileUpload = {
  uploadFile: function (obj, file, iteration, total) {
    var url = obj.get('data-url');
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    $('files-status-overall').setStyle('display', 'block');

    // progress bar
    xhr.upload.addEventListener('progress', function (e) {
      var per = (total <= 1 ? e.loaded/e.total : iteration/total) * 100;
      var overAllFileProgress = -400 + ((per) * 2.5);
      $$('div#files-status-overall img')[0].setStyle('background-position', overAllFileProgress + 'px 0px');
      $$('div#files-status-overall span')[0].set('html', per + '%');
    }, false);

    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        try {
          var res = JSON.parse(xhr.responseText);
        } catch (err) {
          BaseFileUpload.processUploadError('An error occurred.');
          return false;
        }

        if (res['error'] !== undefined) {
          BaseFileUpload.processUploadError(res['error']);
          return false;
        }
        BaseFileUpload.processUploadSuccess(res);

        if (iteration === total) {
          BaseFileUpload.showRefresh();
        }
      }
    };
    fd.append('ajax-upload', 'true');
    fd.append(obj.get('name'), file);
    xhr.send(fd);
  },

  showRefresh: function () {
    $('files-status-overall').setStyle('display', 'none');
    $('upload-complete-message').setStyle('display', '');
  },

  processUploadSuccess: function(response) {
    var uploadedFileList = document.getElementById("uploaded-file-list");
    var uploadedFile = new Element('li', {
      'class': 'file file-success',
      'html' : '<span class="file-name">' + response['fileName'] + '</span>',
    }).inject(uploadedFileList);
    if (uploadedFile.offsetParent === null) {
      uploadedFileList.style.display = "block";
    }
  },

  processUploadError: function(errorMessage) {
    var uploadedFileList = document.getElementById("uploaded-file-list");
    var uploadedFile = new Element('li', {
      'class': 'file file-error',
      'html' : '<span class="validation-error" title="Click to remove this entry.">' + errorMessage + '</span>',
      events: {
        click: function() {
          this.destroy();
        }
      }
    }).inject(uploadedFileList, 'top');
    // If hidden show upload list
    if (uploadedFile.offsetParent === null) {
      uploadedFileList.style.display = "block";
    }
    $('files-status-overall').setStyle('display', 'none');
    return false;
  },
};

en4.core.runonce.add(function () {
  $$('.file-input').each(function (el) {
    $('upload-complete-message').setStyle('display', 'none');
    el.addEvent('change', function () {
      var files = this.files;
      var total = files.length;
      var iteration = 0;
      for(var i = 0; i < files.length; i++) {
        iteration++;
        BaseFileUpload.uploadFile($(el), this.files[i], iteration, total);
      }
    });
    el.addEvent('click', function () {
      this.value = '';
    });
  });
});

</script>

<h2><?php echo $this->translate("File & Media Manager") ?></h2>
<p>
  <?php echo $this->translate('You may want to quickly upload images, icons, or other media for use in your layout, announcements, blog entries, etc. You can upload and manage these files here. Move your mouse over a filename to preview an image.') ?>
</p>
<?php
$settings = Engine_Api::_()->getApi('settings', 'core');
if( $settings->getSetting('user.support.links', 0) == 1 ) {
	echo 'More info: <a href="http://support.socialengine.com/questions/123/Admin-Panel-Layout-File-Media-Manager" target="_blank">See KB article</a>';	
} 
?>	
<br />	

<br />

<div>
  <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Upload New Files'), array('id' => 'demo-browse', 'class' => 'buttonlink admin_files_upload', 'onclick' => 'uploadFile();')) ?>
</div>

<div id="file-status">
  <div id="files-status-overall" style="display: none;">
    <div class="overall-title">Overall Progress</div>
    <img src='<?php echo $this->layout()->staticBaseUrl . "/externals/fancyupload/assets/progress-bar/bar.gif" ?>' class="progress overall-progress">
    <span>0%</span>
  </div>

  <ul id="uploaded-file-list"></ul>
  <div id="base-uploader-progress"></div>
  <div class="base-uploader">
    <input class="file-input" id="upload_file" type="file" multiple="multiple" data-url="<?php echo $this->url(array('action' => 'upload'))?>" data-form-id="#form-upload" name='Filedata'>
  </div>

  <div id="upload-complete-message" style="display:none;">
    <?php echo $this->htmlLink(array('reset' => false), 'Refresh the page to display new files') ?>
  </div>
</div>

<br />

<?php if(count($this->contents) > 0): $i = 0; ?>
  <div class="admin_files_wrapper">

    <iframe src="about:blank" style="display:none" name="downloadframe"></iframe>
    
    <div class="admin_files_pages">
      <?php $pageInfo = $this->paginator->getPages(); ?>
      <?php echo $this->translate(array('Showing %s-%s of %s file.', 'Showing %s-%s of %s files.', $pageInfo->totalItemCount),
          $pageInfo->firstItemNumber, $pageInfo->lastItemNumber, $pageInfo->totalItemCount) ?>
      <span>
        <?php if( !empty($pageInfo->previous) ): ?>
          <?php echo $this->htmlLink(array('reset' => false, 'APPEND' => '?path=' . urlencode($this->relPath) . '&page=' . $pageInfo->previous), 'Previous Page') ?>
        <?php endif; ?>
        <?php if( !empty($pageInfo->previous) && !empty($pageInfo->next) ): ?>
           |
        <?php endif; ?>
        <?php if( !empty($pageInfo->next) ): ?>
          <?php echo $this->htmlLink(array('reset' => false, 'APPEND' => '?path=' . urlencode($this->relPath) . '&page=' . $pageInfo->next), 'Next Page') ?>
        <?php endif; ?>
      </span>
    </div>

    <form action="<?php echo $this->url(array('action' => 'delete')) ?>?path=<?php echo $this->relPath ?>" method="post">
      <ul class="admin_files">
        <?php foreach( $this->paginator as $content ): $i++; $id = 'admin_file_' . $i; $contentKey = $content['rel']; ?>
          <li class="admin_file admin_file_type_<?php echo $content['type'] ?>" id="<?php echo $id ?>">
            <div class="admin_file_checkbox">
              <?php echo $this->formCheckbox('actions[]', $content['rel']) ?>
            </div>
            <div class="admin_file_options">
              <?php echo $this->htmlLink('javascript:void(0)', $this->translate('copy URL'), array('onclick' => 'fileCopyUrl(\''.$contentKey.'\');')) ?>
              | <a href="<?php echo $this->url(array('action' => 'rename', 'index' => $i)) ?>?path=<?php echo urlencode($content['rel']) ?>" class="smoothbox"><?php echo $this->translate('rename') ?></a>
              | <a href="<?php echo $this->url(array('action' => 'delete', 'index' => $i)) ?>?path=<?php echo urlencode($content['rel']) ?>" class="smoothbox"><?php echo $this->translate('delete') ?></a>
              <?php if( $content['is_file'] ): ?>
                | <a href="<?php echo $this->url(array('action' => 'download')) ?><?php echo !empty($content['rel']) ? '?path=' . urlencode($content['rel']) : '' ?>" target="downloadframe"><?php echo $this->translate('download') ?></a>
              <?php else: ?>
                | <a href="<?php echo $this->url(array('action' => 'index')) ?><?php echo !empty($content['rel']) ? '?path=' . urlencode($content['rel']) : '' ?>"><?php echo $this->translate('open') ?></a>
              <?php endif; ?>
            </div>
            <div class="admin_file_name" title="<?php echo $contentKey ?>">
              <?php if( $content['name'] == '..' ): ?>
                <?php echo $this->translate('(up)') ?>
              <?php else: ?>
                <?php echo $content['name'] ?>
              <?php endif; ?>
            </div>
            <div class="admin_file_preview admin_file_preview_<?php echo $content['type'] ?>" style="display:none">
              <?php if( $content['is_image'] ): ?>
                <?php echo $this->htmlImage($this->baseUrl() . '/public/admin/' . $content['rel'], $content['name']) ?>
              <?php elseif( $content['is_markup'] ): ?>
                <iframe style="background-color: #fff;" src="<?php echo $this->url(array('action' => 'preview')) ?>?path=<?php echo urlencode($content['rel']) ?>"></iframe>
              <?php elseif( $content['is_text'] ): ?>
                <div>
                  <?php echo nl2br($this->escape(file_get_contents($content['path']))) ?>
                </div>
              <?php endif; ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="admin_files_submit">
        <button type="submit"><?php echo $this->translate('Delete Selected') ?></button>
      </div>
      <?php echo $this->formHidden('path', $this->relPath) ?>
    </form>
  </div>
<?php endif; ?>
