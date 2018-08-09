<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

abstract class Pgservicelayer_Controller_Action_Api extends Siteapi_Controller_Action_Standard
{
    protected $_inputStream = null;
    public function init(){
        if($this->isApiRequest()){
            header("Content-Type: application/json");
            $user_id = Engine_Api::_()->getApi('oauth', 'pgservicelayer')->validateOauthToken();
            $user = Engine_Api::_()->user()->getUser($user_id);
            $viewer = Engine_Api::_()->user()->getViewer();
            if(!$viewer->getIdentity() && $user->getIdentity()){
                Engine_Api::_()->user()->setViewer($user);
            }            
        }
        $timezone = Engine_Api::_()->getApi('settings', 'core')->core_locale_timezone;
        $viewer   = Engine_Api::_()->user()->getViewer();
        $defaultLocale = $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
        $defaultLocaleObj = new Zend_Locale($defaultLocale);
        Zend_Registry::set('LocaleDefault', $defaultLocaleObj);

        if ($viewer->getIdentity()) {
            $timezone = $viewer->timezone;
            if($viewer->locale == 'English'){
                $viewer->locale = 'en';
                $viewer->save();
            }
        }
        Zend_Registry::set('timezone', $timezone);
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
        $view = Zend_Registry::get("Zend_View");
        $view->addHelperPath(APPLICATION_PATH . '/application/modules/Sitemailtemplates/View/Helper', 'Sitemailtemplates_View_Helper');
        
        if (!$viewer->getIdentity() && !$this->isConsumerValid()) {
            $this->validateOrigin();
        }
        
        $method = strtolower($this->getRequest()->getMethod());
        if($method == 'put' || $method == 'delete' || $method == 'patch' || $method == 'post'){
            $inputStream = $this->getInputStream();
            $params = (array)@json_decode($inputStream);
            if(empty($params)){
                parse_str($inputStream,$params);
            }
            $request = Zend_Controller_Front::getInstance()->getRequest();
            if(!empty($params) && is_array($params)){
                foreach($params as $key => $param){
                    $this->setParam($key, $param);
                    $request->setParam($key,$param);
                    $_REQUEST[$key] = $param;
                }
            }
        }
        
        $this->filterApiData();
    }
    public function getInputStream(){
        if($this->_inputStream == null){
            $this->_inputStream = file_get_contents("php://input");
        }
        return $this->_inputStream;
    }
    public function isApiRequest(){
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $consumerKey = $request->getHeader("oauth_consumer_key");
        if(empty($consumerKey)){
            $consumerKey = $request->getHeader("oauth-consumer-key");
        }
        if(empty($consumerKey)){
            $consumerKey = $request->getParam("oauth_consumer_key",$request->getParam("oauth-consumer-key"));
        }
        if(empty($consumerKey)){
            $consumerKey = $request->getServer("HTTP_POSTMAN_TOKEN",$request->getHeader("HTTP_POSTMAN_TOKEN"));
        }
        if(!empty($consumerKey)){
            return true;
        }
        
//        $xRequestedWith = $this->getRequest()->getHeader("X-Requested-With");
//        $xRequested = $this->getRequest()->getHeader("X-Request");;
//        if($xRequestedWith == "XMLHttpRequest" && $xRequested == "JSON"){
//            return false;
//        }
        $referer = $request->getServer("HTTP_REFERER");
        if(!strstr($referer,$_SERVER['HTTP_HOST'])){
            return true;
        }
        return false;
    }
    public function isConsumerValid(){
        return Engine_Api::_()->getApi('oauth', 'pgservicelayer')->isConsumerValidOnly();
    }
    public function postDispatch() {
        
    }
    
    public function respondWithSuccess($body, $setCache = null) {
        $this->view->status_code = 200;
        $this->view->status = "Success";
        if (isset($body)) {
            $this->view->body = $body;
        } else {
            $this->view->body = '';
        }

        $this->sendResponse();
    }
    public function successResponseNoContent($statusCode, $deleteCache = null) {
        $this->view->status_code = $statusCsode = $this->_statusCode[$statusCode];
        $this->view->status = "Success";
        // Delete cache
        if (!empty($deleteCache)) {
            if (is_string($deleteCache))
                Engine_Api::_()->getApi('cache', 'siteapi')->deleteCache($deleteCache);
            else
                Engine_Api::_()->getApi('cache', 'siteapi')->deleteCache();
        }

        $this->sendResponse();
    }
    public function sendResponse() {
        ob_clean();
        $accept = $this->getRequest()->getHeader("Accept");
        if(empty($accept) || strstr(strtolower($accept), "json") || $this->isApiRequest()){
            header("Content-Type: application/json");
        }        
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
            $data = @json_encode($this->view);
        }

        if (!function_exists('json_encode') || empty($data))
            $data = Zend_Json::encode($this->view);

        $body = $this->getResponse()->getBody();
        if(!empty($body) && (empty($data) || $data == "{}")){
            $this->getResponse()->setBody($body);
        }else{
            $this->getResponse()->setBody($data);
        }        
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
    
    public function respondWithError($statusCode, $message=null, $type = null) {
        $this->view->status_code = $this->_statusCode[$statusCode];
        $this->view->status = "Error";
        $this->view->error = true;
        $this->view->error_code = $statusCode;

        if (isset($message) && !empty($message)) {
            $this->view->message = $this->translate($message);
        } else if (isset($type) && !empty($type)) {
            $this->view->message = $this->translate('You do not have permission to view this ' . $type);
        } else
            $this->view->message = $this->getMessageTemplate($statusCode);

        $this->sendResponse();
    }
    public function respondWithValidationError($statusCode, $message = null) {
        $this->view->status_code = $this->_statusCode[$statusCode];
        $this->view->status = "Error";
        $this->view->error = true;
        $this->view->error_code = $statusCode;

        if (isset($message) && !empty($message)) {
            if (is_array($message)) {
                foreach ($message as $key => $value) {
                    $messages[$key] = Engine_Api::_()->getApi('Core', 'siteapi')->translate($value);
                }
                $message = $messages;
            } else {
                $message = $this->getMessageTemplate($statusCode) . ': ' . $message;
            }
        }

        $this->view->message = $message;

        $this->sendResponse();
    }
    public function respondWithServerError($exception){
        $this->view->status_code = 500;
        $this->view->status = "Error";
        $this->view->error = true;
        $this->view->error_code = 500;
        $this->view->message = "Service temporary unavailable";
        $this->view->exceptionMessage = $exception->getMessage();
        $this->view->stackTrace = $exception->getTraceAsString();
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
    
    public function requireSubject(){
        $resourceType = $this->getParam("contentType");
        $resourceId = $this->getParam("contentID");
        $resourceType = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($resourceType);
        if(empty($resourceType) || empty($resourceId)){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->hasItemType($resourceType)){
            $this->respondWithError('no_record');
        }
        
        $subject = Engine_Api::_()->getItem($resourceType,$resourceId);
        if(empty($subject) || !$subject->getIdentity()){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->core()->hasSubject()){
            Engine_Api::_()->core()->setSubject($subject);
        }
    }
    
    public function validateOrigin(){
        if(empty($_SERVER['HTTP_ORIGIN'])){
            return true;
        }
        $origin = $_SERVER['HTTP_ORIGIN'];
        $origin = str_replace("http://","",$origin);
        $origin = str_replace("https://","",$origin);
        $origin = str_replace("www","",$origin);
        $host = $_SERVER['HTTP_HOST'];
        $host = str_replace("http://","",$host);
        $host = str_replace("https://","",$host);
        $host = str_replace("www","",$host);
        
        if($origin == $host){
            return true;
        }
                
        $this->view->status_code = 400;
        $this->view->status = "Error";
        $this->view->error = true;
        $this->view->error_code = "unauthorized";

        $this->view->message = $this->translate('You do not have permission to view this.');

        $this->sendResponse();        
    }
    
    public function validateParams($params = array()){
        $params[] = 'rewrite';
        $requestParams = (array)$_GET;
        $invalidParams = array();
        foreach($requestParams as $key => $value){
            if(!in_array($key,$params)){
                $invalidParams[] = $key;
            }
        }
        if(!empty($invalidParams)){
            $this->respondWithError("invalid_parameters",sprintf($this->translate("Extra parameters detected. %s"),implode(", ",$invalidParams)));
        }
    }
    
    public function validatePermission($permission, $viewer = null,$subject = null){
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
    
    public function filterApiData(){
        if(!empty($_REQUEST) && is_array($_REQUEST)){
            foreach($_REQUEST as $key => $data){
                if(is_array($data)){
                    foreach($data as $key2 => $data){
                        $_REQUEST[$key][$key2] = $this->cleanVar($data);
                    }
                }else{
                    $_REQUEST[$key] = $this->cleanVar($data);
                }
            }
            $this->getRequestAllParams = !empty($_REQUEST) ? $_REQUEST : array();
        }
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $params = $request->getParams();
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['rewrite']);
        unset($params['version']);
        
        if(!empty($params) && is_array($params)){
            foreach($params as $key => $data){
                if(is_array($data)){
                    foreach($data as $key2 => $data){
                        $params[$key][$key2] = $this->cleanVar($data);
                    }
                }else{
                    $params[$key] = $this->cleanVar($data);
                }
                $this->setParam($key,$params[$key]);
                $request->setParam($key,$params[$key]);
            }
        }
    }
    public function cleanVar($var){
        if(empty($var) || is_array($var)){
            return $var;
        }
        
        //Remove Tags
        $var = html_entity_decode($var, ENT_COMPAT, 'UTF-8');
        $var = strip_tags($var); //,'<strong><b><em><i><u><strike><sub><sup><p>'
        $var = filter_var($var, FILTER_SANITIZE_STRING); //It filters all tags. May not be useful for some tags.
//        $var = $this->xss_clean($var); //Extra cleaning
        return $var;
    }
    
    function xss_clean($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
    }
}
