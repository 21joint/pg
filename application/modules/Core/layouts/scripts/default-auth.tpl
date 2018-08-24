<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: default-simple.tpl 10227 2014-05-16 22:43:27Z andres $
 * @author     John
 */
?>
<?php echo $this->doctype()->__toString() ?>
<?php $locale = $this->locale()->getLocale()->__toString();
$orientation = ($this->layout()->orientation == 'right-to-left' ? 'rtl' : 'ltr'); ?>
<html class="h-100" style="overflow-x: hidden;" id="smoothbox_window" xmlns="http://www.w3.org/1999/xhtml"
      xml:lang="<?php echo $locale ?>" dir="<?php echo $orientation ?>">
<head>
  <base href="<?php echo rtrim($this->serverUrl($this->baseUrl()), '/') . '/' ?>"/>

  <?php // TITLE/META ?>
  <?php

  $counter = (int)$this->layout()->counter;
  $staticBaseUrl = $this->layout()->staticBaseUrl;
  $headIncludes = $this->layout()->headIncludes;

  $this->headLink()->appendStylesheet('/styles/header.bundle.css');
  $this->headLink()->appendStylesheet('/styles/login.bundle.css');

  $request = Zend_Controller_Front::getInstance()->getRequest();
  $this->headTitle()
    ->setSeparator(' - ');
  $pageTitleKey = 'pagetitle-' . $request->getModuleName() . '-' . $request->getActionName()
    . '-' . $request->getControllerName();
  $pageTitle = $this->translate($pageTitleKey);
  if ($pageTitle && $pageTitle != $pageTitleKey) {
    $this
      ->headTitle($pageTitle, Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);
  }
  $this
    ->headTitle($this->translate($this->layout()->siteinfo['title']));
  $this->headMeta()
    ->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')
    ->appendHttpEquiv('Content-Language', $this->locale()->getLocale()->__toString());

  // Make description and keywords
  $description = $this->layout()->siteinfo['description'];
  $keywords = $this->layout()->siteinfo['keywords'];

  if ($this->subject() && $this->subject()->getIdentity()) {
    $this->headTitle($this->subject()->getTitle(), Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);

    $description = $this->subject()->getDescription() . ' ' . $description;
    // Remove the white space from left and right side
    $keywords = trim($keywords);
    if (!empty($keywords) && (strrpos($keywords, ',') !== (strlen($keywords) - 1))) {
      $keywords .= ',';
    }
    $keywords .= $this->subject()->getKeywords(',');
  }

  $keywords = trim($keywords, ',');

  $this->headMeta()->appendName('description', trim($description));
  $this->headMeta()->appendName('keywords', trim($keywords));
  $this->headMeta()->appendName('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');

  //Adding open graph meta tag for video thumbnail
  if ($this->subject() && $this->subject()->getPhotoUrl()) {
    $this->headMeta()->setProperty('og:image', $this->absoluteUrl($this->subject()->getPhotoUrl()));
  }

  // Get body identity
  if (isset($this->layout()->siteinfo['identity'])) {
    $identity = $this->layout()->siteinfo['identity'];
  } else {
    $identity = $request->getModuleName() . '-' .
      $request->getControllerName() . '-' .
      $request->getActionName();
  }
  ?>
  <?php echo $this->headTitle()->toString() . "\n" ?>
  <?php echo $this->headMeta()->toString() . "\n" ?>


  <?php // LINK/STYLES ?>
  <?php

  $this->headLink(array(
    'rel' => 'shortcut icon',
    'href' => $staticBaseUrl . (isset($this->layout()->favicon) ? $this->layout()->favicon : 'favicon.ico'),
    'type' => 'image/x-icon'),
    'PREPEND');
  $themes = array();
  if (!empty($this->layout()->themes)) {
    $themes = $this->layout()->themes;
  } else {
    $themes = array('default');
  }

  // Process
  foreach ($this->headLink()->getContainer() as $dat) {
    if (!empty($dat->href)) {
      if (false === strpos($dat->href, '?')) {
        $dat->href .= '?c=' . $counter;
      } else {
        $dat->href .= '&c=' . $counter;
      }
    }
  }
  ?>
  <?php echo $this->headLink()->toString() . "\n" ?>
  <?php echo $this->headStyle()->toString() . "\n" ?>

  <?php // TRANSLATE ?>
  <?php $this->headScript()->prependScript($this->headTranslate()->toString()) ?>

  <?php // SCRIPTS ?>
  <script type="text/javascript">
    <?php echo $this->headScript()->captureStart(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND) ?>

    var serverOffset = 0;

    Date.setServerOffset = function (ts) {
      var server = new Date(ts);
      var client = new Date();
      serverOffset = server - client;
    };

    Date.prototype.getServerOffset = function () {
      return serverOffset;
    };
    Date.prototype.getDOY = function () {
      var onejan = new Date(this.getFullYear(), 0, 1);
      return Math.ceil((this - onejan) / 86400000);
    };
    Date.prototype.clone = function () {
      return new Date(this.getTime());
    };

    //Date.setServerOffset('<?php echo date('D, j M Y G:i:s O', time()) ?>');

    en4.orientation = '<?php echo $orientation ?>';
    en4.core.environment = '<?php echo APPLICATION_ENV ?>';
    en4.core.language.setLocale('<?php echo $this->locale()->getLocale()->__toString() ?>');
    en4.core.setBaseUrl('<?php echo $this->url(array(), 'default', true) ?>');
    en4.core.staticBaseUrl = '<?php echo $this->escape($staticBaseUrl) ?>';
    // en4.core.loader = new Element('img', {src: en4.core.baseUrl + 'application/themes/parentalguidance/images/loader.svg'});

    <?php if( $this->subject() ): ?>
    en4.core.subject = {
      type: '<?php echo $this->subject()->getType(); ?>',
      id: <?php echo $this->subject()->getIdentity(); ?>,
      guid: '<?php echo $this->subject()->getGuid(); ?>'
    };
    <?php endif; ?>
    <?php if( $this->viewer()->getIdentity() ): ?>
    en4.user.viewer = {
      type: '<?php echo $this->viewer()->getType(); ?>',
      id: <?php echo $this->viewer()->getIdentity(); ?>,
      guid: '<?php echo $this->viewer()->getGuid(); ?>'
    };
    <?php endif; ?>
    if ( <?php echo(Zend_Controller_Front::getInstance()->getRequest()->getParam('ajax', false) ? 'true' : 'false') ?> ) {
      en4.core.dloader.attach();
    }

    <?php echo $this->headScript()->captureEnd(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND) ?>
  </script>
  <?php
  $this
    ->headScript()
    ->prependFile('/scripts/login.bundle.js')
    ->prependFile('/scripts/cores.bundle.js');
  //    ->prependFile($staticBaseUrl . 'application/modules/User/externals/scripts/core.js')
  //    ->prependFile($staticBaseUrl . 'application/modules/Core/externals/scripts/core.js')
  //    ->prependFile($staticBaseUrl . 'externals/chootools/chootools.js')
  //    ->prependFile($staticBaseUrl . 'externals/mootools/mootools-more-1.4.0.1-full-compat-' . (APPLICATION_ENV == 'development' ? 'nc' : 'yc') . '.js')
  //    ->prependFile($staticBaseUrl . 'externals/mootools/mootools-core-1.4.5-full-compat-' . (APPLICATION_ENV == 'development' ? 'nc' : 'yc') . '.js');
  // Process
  foreach ($this->headScript()->getContainer() as $dat) {
    if (!empty($dat->attributes['src'])) {
      if (false === strpos($dat->attributes['src'], '?')) {
        $dat->attributes['src'] .= '?c=' . $counter;
      } else {
        $dat->attributes['src'] .= '&c=' . $counter;
      }
    }
  }
  ?>
  <?php echo $this->headScript()->toString() . "\n" ?>

  <!-- vertical scrollbar fix -->


  <?php echo $headIncludes ?>

</head>
<body style="margin:0;" id="global_page_<?php echo $identity ?>"
      class="<?php echo ($identity == 'core-index-index') ? '' : 'h-100 full-height-auth'; ?>">
<?php echo $this->layout()->content ?>
</body>
</html>
