<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Form_Admin_Settings_Global extends Engine_Form {
    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
    "submit_lsetting", "environment_mode","enable_default"
    );

    public function init() {

        $productType = 'advancedpagecache';

        $this->setTitle('Global Settings')
             ->setDescription('Below are the general settings related to multiple users and single User Cache in order to improve the speed of your website.');
        
        // ELEMENT FOR LICENSE KEY
        $this->addElement('Text', $productType . '_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting($productType . '.lsettings'),
        ));
        $this->addElement('Radio', 'enable_default', array(
            'label' => 'Default configuration',
            'description' => 'Do you want to enable default configuration for this plugin ?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
        ));
        if (APPLICATION_ENV == 'production') {
            $this->addElement('Checkbox', 'environment_mode', array(
                'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
                'description' => 'System Mode',
                'value' => 1,
            ));
        } else {
            $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
        }

        $this->addElement('Button', 'submit_lsetting', array(
            'label' => 'Activate Your Plugin Now',
            'type' => 'submit',
            'ignore' => true
        ));       

        $url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'advancedpagecache', 'controller' => 'settings','action' => 'faq'), 'admin_default', true);
        $coreSettingsApi = Engine_Api::_()->getApi('settings', 'core');

        $this->addElement('Radio', 'disable_partial', array(
            'label' => 'Multiple Users Caching',
            'description' => 'Do you want to enable <a href="'.$url.'">Multiple Users Caching</a>?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'onclick' => 'updatepartialFields();',
            'value' => 1,
        ));
        $this->disable_partial->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
        $this->addElement('Text', 'partial_lifetime', array(
            'label' => 'Duration of Multiple Users Caching',
            'description' => "Please enter duration for Multiple users caching of pages. [Note: duration should be in seconds e.g: 10 mins = 600 secs]",
            'size' => 5,
            'maxlength' => 4,
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('Int'),
            ),
            'value' => 1200,
        ));
        $this->addElement('Radio', 'disable_browse', array(
            'label' => 'Single User Caching',
            'description' => 'Do you want to enable <a href="'.$url.'">Single User Caching</a>?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'onclick' => 'updatebrowserFields();',
            'value' => 1,
        ));
        $this->disable_browse->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
        $this->addElement('Text', 'browser_lifetime', array(
            'label' => 'Duration of Single User Caching',
            'description' => "Please enter duration for Single user caching of pages. [Note: duration should be in seconds e.g: 10 mins = 600 secs]",
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('Int'),
            ),
            'value' => 1200,
        ));
        $this->addElement('Text', 'cache_id_prefix', array(
            'label' => 'Caching Prefix',
            'description' => "If you have enabled same memory cache system for many sites on the same server, you can add prefix for the cache files in case of Single user based caching.",
            'required' => true,
            'allowEmpty' => false,
        ));

        $attribs = array();
        $typeDescription = $this->getTranslator()->translate('If you have enabled Single user caching above, please select the type of caching that you want to use. Memory-based caches (Memcache, APC(u), XCache, Redis) can offer better performance, but must be enabled in PHP and sometimes require further configuration. Contact your hosting provider for assistance in configuring memory-based caching.');
        $cacheOptions = array(
            'File'      => 'File-based',
            'Memcached' => 'Memcache',
            'Xcache'    => 'Xcache',
        );
        
        $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
        $coreversion = $coremodule->version;
        if( $coreversion >= '4.9.0' ) {
            $cacheOptions['Engine_Cache_Backend_Redis'] = 'Redis';
        } 
        if( function_exists('apcu_fetch') && $coreversion >= '4.9.3') {
            $cacheOptions['Engine_Cache_Backend_Apc'] = 'APCu';
        } else {
            $cacheOptions['Apc'] = 'APC';
        }
        $this->addElement('Radio', 'type', array(
        'label' => 'Caching Feature',
        'description' => $typeDescription,
        'required' => true,
        'allowEmpty' => false,
        'multiOptions' => $cacheOptions,
        'onclick' => 'updateFields();',
        'attribs' => $attribs,
        ));

        $this->type->getDecorator('Description')->setOption('escape', false);
        $this->type->setAttrib('escape', false);

        $this->addElement('Text', 'file_path', array(
            'label' => 'File-based Cache Directory',
            'description' => strtoupper('Core_Form_Admin_Settings_Performance_file_path_description'),
            'attribs' => $attribs,
        ));

        $this->addElement('Checkbox', 'file_locking', array(
            'label' => 'File locking?',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'memcache_host', array(
            'label' => 'Memcache Host',
            'description' => 'Can be a domain name, hostname, or an IP address (recommended)',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'memcache_port', array(
            'label' => 'Memcache Port',
            'attribs' => $attribs,
        ));

        $this->addElement('Checkbox', 'memcache_compression', array(
            'label' => 'Memcache compression?',
            'title' => 'Title?',
            'description' => 'Compression will decrease the amount of memory used, however will increase processor usage.',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'redis_host', array(
            'label' => 'Redis Host',
            'description' => 'Can be a domain name, hostname, or an IP address (recommended)',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'redis_port', array(
            'label' => 'Redis Port',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'xcache_username', array(
            'label' => 'Xcache Username',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'xcache_password', array(
            'label' => 'Xcache Password',
            'attribs' => $attribs,
        ));

        $this->addElement('Text', 'utilization_space', array(
            'label' => 'Disk Space',
            'description' => "Please enter the disk space (in GB). If the disk space available is less than the amount specified below cache will not work.[Note: This setting will work in case of File based caching only]",
            'required' => true,
            'maxlength' => 6,
            'validators' => array(
                array('NotEmpty', true),
                array('Float', true),
            ),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
            ), 'value' => 1,));

        $this->addElement('Text', 'automatic_clear', array(
            'label' => 'Duration for Storage Space Check',
            'description' => "Please enter the duration after which a check for utilized storage space is done and cache will be automatically cleared if exceeding the specified storage space allowed. [Note: Please enter the duration in seconds]",
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('Int'),
            ),
            'value' => 900,
        ));

        $this->addElement('MultiCheckbox', 'flush', array(
            'label' => 'Clear Cache',
            'description' => 'Do you want to clear cache?',
            'multiOptions' => array('1' => 'Flush Cache',),
        ));
        // Element: submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));

        /* if (APPLICATION_ENV != 'production') {
          $this->addError('Note: Caching is disabled when your site is in development mode. Your site must be in production mode to modify the settings below.');
          } */
    }

    public function populate(array $currentCache) {
        $enabled = false;
        if (isset($currentCache['disable_partial']))
            $enabled = $currentCache['disable_partial'];
        $this->getElement('disable_partial')->setValue($enabled);

        $enabled = false;
        if (isset($currentCache['disable_browse']))
            $enabled = $currentCache['disable_browse'];
        $this->getElement('disable_browse')->setValue($enabled);

        if (isset($currentCache['utilization_space'])) {
            $this->getElement('utilization_space')->setValue($currentCache['utilization_space']);
        }
        $prefix = 'advancedpagecache_page_';
        if (isset($currentCache['frontend']['cache_id_prefix'])) {
            $prefix = $currentCache['frontend']['cache_id_prefix'];
            $this->getElement('cache_id_prefix')->setValue($prefix);
        }

        if (isset($currentCache['partial_lifetime'])) {
            $this->getElement('partial_lifetime')->setValue($currentCache['partial_lifetime']);
        }
        $backend = Engine_Cache::getDefaultBackend();
        if (isset($currentCache['backend'])) {
            $backend = array_keys($currentCache['backend']);
            $backend = $backend[0];
        }
        $this->getElement('type')->setValue($backend);

        $filePath = $currentCache['default_file_path'];
        if (isset($currentCache['backend']['File']['cache_dir']))
            $filePath = $currentCache['backend']['File']['cache_dir'];
        $this->getElement('file_path')->setValue($filePath);

        $fileLocking = 1;
        if (isset($currentCache['backend']['File']['file_locking'])) {
            $fileLocking = $currentCache['backend']['File']['file_locking'];
        }
        $this->getElement('file_locking')->setValue($fileLocking);

        if (isset($currentCache['frontend']['lifetime'])) {
            $lifetime = $currentCache['frontend']['lifetime'];
        }
        $this->getElement('browser_lifetime')->setValue($lifetime);

        $memcache_host = '127.0.0.1';
        $memcache_port = '11211';
        $memcache_compression = 0;
        if (isset($current_cache['backend']['Memcached']['servers'][0]['host']))
            $memcache_host = $current_cache['backend']['Memcached']['servers'][0]['host'];
        if (isset($current_cache["backend"]["Memcached"]["servers"][0]["port"]))
            $memcache_port = $current_cache["backend"]["Memcached"]["servers"][0]["port"];
        if (isset($current_cache["backend"]["Memcached"]["compression"]))
            $memcache_compression = $current_cache["backend"]["Memcached"]["compression"];
        $this->getElement('memcache_host')->setValue($memcache_host);
        $this->getElement('memcache_port')->setValue($memcache_port);
        $this->getElement('memcache_compression')->setValue($memcache_compression);
        $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
        $coreversion = $coremodule->version;
        if( $coreversion >= '4.9.0' ) {
            $redisHost = Engine_Cache_Backend_Redis::DEFAULT_HOST;
            $redisPort = Engine_Cache_Backend_Redis::DEFAULT_PORT;
            $redisBackend = 'Engine_Cache_Backend_Redis';
            if (isset($currentCache['backend'][$redisBackend]['servers'][0]['host']))
                $redisHost = $currentCache['backend'][$redisBackend]['servers'][0]['host'];
            if (isset($currentCache["backend"][$redisBackend]["servers"][0]["port"]))
                $redisPort = $currentCache["backend"][$redisBackend]["servers"][0]["port"];
            $this->getElement('redis_host')->setValue($redisHost);
            $this->getElement('redis_port')->setValue($redisPort);
        }    
        // Set Existing Value for Translation Performance checkbox
    }

}

?>
