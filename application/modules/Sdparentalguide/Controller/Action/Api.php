<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @author     Stars Developer
 */

abstract class Sdparentalguide_Controller_Action_Api extends Core_Controller_Action_Standard
{
    public function postDispatch() {
        
    }
    
    public function respondWithSuccess($body, $setCache = null) {
        $this->view->status_code = 200;
        if (isset($body)) {
            $this->view->body = $body;
        } else {
            $this->view->body = '';
        }

        $this->sendResponse();
    }
    
    public function sendResponse() {
        ob_clean();
        
        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        if ((($moduleName == 'siteevent' || $moduleName == 'sitereview') &&
                ($controllerName == 'index') &&
                ($actionName == 'create' || $actionName == 'edit')) || ($moduleName == 'sitegroupmember' && $controllerName == 'member' && $actionName == 'join')
        ) {
            $data = @json_encode($this->view);
        } else {
            $data = @json_encode($this->view, JSON_NUMERIC_CHECK);
        }

        if (!function_exists('json_encode') || empty($data))
            $data = Zend_Json::encode($this->view);

        $this->getResponse()->setBody($data);
        $this->getResponse()->sendResponse();
        exit;
    }
    
    public function preDispatch() {
        
    }
    
    public function dispatch($action) {
        // Notify helpers of action preDispatch state
        $this->_helper->notifyPreDispatch();
        $this->preDispatch();

        if (empty($this->_noDispatched) && !empty($action) && $this->getRequest()->isDispatched()) {
            if (null === $this->_classMethods) {
                $this->_classMethods = get_class_methods($this);
            }

            if (!($this->getResponse()->isRedirect())) {
                if ($this->getInvokeArg('useCaseSensitiveActions') || in_array($action, $this->_classMethods)) {
                    if ($this->getInvokeArg('useCaseSensitiveActions')) {
                        $this->respondWithError('useCaseSensitiveActions_error');
                    } else {
                        $this->$action();
                    }
                } else {
                    $this->__call($action, array());
                }
            }
        }
        // Notify helpers of action preDispatch state
//        $this->_helper->notifyPostDispatch();
        
        $this->postDispatch();
        

        //send json response back.
        $data = Zend_Json::encode($this->view);
        $this->getResponse()->setBody($data);
        $this->sendResponse();
    }
    
    public function respondWithError($statusCode, $message, $type = null) {
        $this->view->status_code = $this->_statusCode[$statusCode];
        $this->view->error = true;
        $this->view->error_code = $statusCode;

        if (isset($message) && !empty($message)) {
            $this->view->message = $this->view->translate($message);
        } else if (isset($type) && !empty($type)) {
            $this->view->message = $this->view->translate('You do not have permission to view this ' . $type);
        } else
            $this->view->message = $this->view->translate("Error handling your request");

        $this->sendResponse();
    }
    public function getHost() {
        return _ENGINE_SSL ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'];
    }
    public function getBasePath(){
        $staticBaseUrl = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.static.baseurl', null);
        $tempHost = $serverHost = $this->getHost();

        $getDefaultStorageId = Engine_Api::_()->getDbtable('services', 'storage')->getDefaultServiceIdentity();
        $getDefaultStorageType = Engine_Api::_()->getDbtable('services', 'storage')->getService($getDefaultStorageId)->getType();
        $getHost = $getPhotoHost = $this->getHost();
        if ($getDefaultStorageType == 'local'){
            $getPhotoHost = $getHost = !empty($staticBaseUrl) ? $staticBaseUrl : $serverHost;
        }else{
            return '';
        }

        return $getPhotoHost;
    }
}
