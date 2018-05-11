<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    IndexController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_IndexController extends Siteapi_Controller_Action_Standard {

	public function init(){

	}

	// Hashtag search suggestions
	public function browseAction()
	{
		$this->validateRequestMethod();

		$values = $this->getAllParams();
		$dataArray = array();
		if(isset($values['hashtag']))
		{
			$data = Engine_Api::_()->getDbtable('tags', 'sitehashtag')->getItems($values['hashtag'] , 20 , 'text');
			if($data)
			{
				foreach($data as $row => $value)
				{
					$temparray = array();
					$temparray['id'] = $value['tag_id'];
					$temparray['label'] = $value['text'];
					$temparray['url'] = '/hashtag?search='.urlencode($value['text']);
					$dataArray[] = $temparray;
					unset($temparray);
				}
			}
		}
		else
			$this->respondWithValidationError('parameter_missing','hashtag empty');

		$this->respondWithSuccess($dataArray);
	}

	// Hashtag top trends
	public function trendsAction()
	{
		$this->validateRequestMethod();
		$this->respondWithSuccess(Engine_Api::_()->getDbtable('tags' , 'sitehashtag')->getTopTrends(20 , 20));
	}

}