<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */
class Pgservicelayer_View_Helper_PggPermission extends Zend_View_Helper_Abstract
{

    public function pggPermission($permission, $viewer = null,$subject = null) {
        if( null === $viewer ) {
            $viewer = Engine_Api::_()->user()->getViewer();
        }
        
        if($subject == null && Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        
        $permissions = Engine_Api::_()->pgservicelayer()->getPermissions($viewer);
        if(isset($permissions[$permission])){
            return $permissions[$permission];
        }
        return false;
    }
}
