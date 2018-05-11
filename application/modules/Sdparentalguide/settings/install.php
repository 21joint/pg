<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_Installer extends Engine_Package_Installer_Module {
    public function onEnable(){
        $db = $this->getDb();
        parent::onEnable();
        
    }
    public function onDisable(){
        $db = $this->getDb();
        parent::onDisable();
        
    }
}

?>
