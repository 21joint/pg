<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Admin_Manage_Global extends Engine_Form
{
    public function init(){
        $this->setTitle('Cache Settings');
        
        $order = 30;
        $attribs = array();
        if( APPLICATION_ENV != 'production' ) {
          $attribs = array('disabled' => 'disabled', 'readonly' => 'readonly');
          $this->addError('Note: Caching is disabled when your site is in development mode. Your site must be in production mode to modify the settings below.');
        }
        
        $this->addElement('Radio', 'enable', array(
            'label' => 'Cache Global Settings',
            'order' => $order++,
            'attribs' => $attribs,
            'multiOptions' => array(
                '1' => 'Enable',
                '0' => 'Disabled'
            ),
            'onchange' => 'switchLifeTime(this);',
            'value' => Engine_Api::_()->getDbTable("settings","sdparentalguide")->getSetting('gg.cache',0)
        ));
        
        $this->addElement('Text', 'lifetime', array(
            'label' => 'Cache Life',
            'order' => $order++,
            'attribs' => $attribs,
            'value' => Engine_Api::_()->getDbTable("settings","sdparentalguide")->getSetting('gg.cacheage',300),
        ));
        
        $this->addElement("Hidden","type",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","file_path",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","file_locking",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","memcache_host",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","memcache_port",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","memcache_compression",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","redis_host",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","redis_port",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","xcache_username",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","xcache_password",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","flush",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","translate_array",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));
        
        $this->addElement("Hidden","gzip_html",array(
            'order' => $order++,
            'attribs' => $attribs,
        ));        
        
        // Add submit button
        $this->addElement('Button', 'save', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
            'order' => $order++,
        ));
    }
}
