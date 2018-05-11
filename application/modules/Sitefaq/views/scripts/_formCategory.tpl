<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formCategory.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
  
	$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');
	$categories = $tableCategory->getCategories(null);
	if (count($categories) != 0) {

		$faq_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('faq_id', null);
		if(!empty($faq_id) && empty($_POST)) {
			$sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);
		}
		elseif(empty($faq_id) && empty($_POST)) {
			$sitefaq->category_id = '["0"]';
			$sitefaq->subcategory_id = '["0"]';
			$sitefaq->subsubcategory_id = '["0"]';
		}
		else {

			//GET CATEGORIES ARRAY
			foreach($_POST as $key => $value) {
				$sub = strstr($key, 'sub');
				$subsub = strstr($key, 'subsub');
				$category = strstr($key, 'category_id_');

				if(empty($sub) && !empty($category) && !empty($value)) {

					$explode_array = explode('category_id_', $key);
					$key = $explode_array[1];

					//CATEGORY ID ARRAY
					$category_id[] = "$value";

					if(isset($_POST["category_id_$key"]) && (!isset($_POST["subcategory_id_$key"]) || (isset($_POST["subcategory_id_$key"]) && empty($_POST["subcategory_id_$key"])))) {
						$subcategory_id[] = $subsubcategory_id[] = "0";
					}

				}
				elseif(!empty($sub) && empty($subsub) && !empty($category) && !empty($value)) {

					$explode_array = explode('subcategory_id_', $key);
					$key = $explode_array[1];

					//SUB-CATEGORY ID ARRAY
					$subcategory_id[] = "$value";

					if(isset($_POST["subcategory_id_$key"]) && (!isset($_POST["subsubcategory_id_$key"]) || (isset($_POST["subsubcategory_id_$key"]) && empty($_POST["subsubcategory_id_$key"])))) {
						$subsubcategory_id[] = "0";
					}
				}
				elseif(!empty($subsub) && !empty($category) && !empty($value)) {
					//3RD-LAVEL-CATEGORY ID ARRAY
					$subsubcategory_id[] = "$value";
				}
			}

			$default_value = array(0 => '0');
			if(!empty($category_id)) {
				$sitefaq->category_id = Zend_Json_Encoder::encode($category_id);
			}
			else {
				$sitefaq->category_id = Zend_Json_Encoder::encode($default_value);
			}

			if(!empty($subcategory_id)) {
				$sitefaq->subcategory_id = Zend_Json_Encoder::encode($subcategory_id);
			}
			else {
				$sitefaq->subcategory_id = Zend_Json_Encoder::encode($default_value);
			}

			if(!empty($subsubcategory_id)) {
				$sitefaq->subsubcategory_id = Zend_Json_Encoder::encode($subsubcategory_id);
			}
			else {
				$sitefaq->subsubcategory_id = Zend_Json_Encoder::encode($default_value);
			}
		}

		$category_details = array();

		if(!empty($sitefaq->category_id)) {
			$category_ids = Zend_Json_Decoder::decode($sitefaq->category_id);
			foreach($category_ids as $key => $category_value) {
				$key = $key + 1;
				$category_details[$key][] = $category_value;
			}
		}

		if(!empty($sitefaq->subcategory_id)) {
			$subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
			foreach($subcategory_ids as $key => $category_value) {
				$key = $key + 1;
				$category_details[$key][] = $category_value;
			}
		}

		if(!empty($sitefaq->subsubcategory_id)) {
			$subsubcategory_ids = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);
			foreach($subsubcategory_ids as $key => $category_value) {
				$key = $key + 1;
				$category_details[$key][] = $category_value;
			}
		}

		foreach($category_details as $key => $value) {

			$category_id = $value[0];
			$subcategory_id = $value[1];
			$subsubcategory_id = $value[2];

			$subcategories_prepared = array();
			$subcategory_options_string = '';
			$subCategories = $tableCategory->getSubCategories($category_id);
			if (count($subCategories) != 0) {
				$subcategory_options_string = "<option value='0' label=''></option>";
				foreach ($subCategories as $subcategory) {
	
					$subcategory->category_name = Zend_Registry::get('Zend_Translate')->_("$subcategory->category_name");
					$subcategories_prepared[$subcategory->category_id] = $subcategory->category_name;

					if($subcategory_id == $subcategory->category_id) {
						$subcategory_options_string .= "<option value='$subcategory->category_id' label='$subcategory->category_name' selected='selected'>$subcategory->category_name</option>";
					}
					else {
						$subcategory_options_string .= "<option value='$subcategory->category_id' label='$subcategory->category_name'>$subcategory->category_name</option>";
					}
				}
			}

			$subsubcategories_prepared = array();
			$subsubcategory_options_string = '';
			$subsubCategories = $tableCategory->getSubCategories($subcategory_id);
			if (count($subsubCategories) != 0) {
				$subsubcategory_options_string = "<option value='0' label=''></option>";
				foreach ($subsubCategories as $subsubcategory) {

					$subsubcategory->category_name = Zend_Registry::get('Zend_Translate')->_("$subsubcategory->category_name");
					$subsubcategories_prepared[$subsubcategory->category_id] = $subsubcategory->category_name;

					if($subsubcategory_id == $subsubcategory->category_id) {
						$subsubcategory_options_string .= "<option value='$subsubcategory->category_id' label='$subsubcategory->category_name' selected='selected'>$subsubcategory->category_name</option>";
					}
					else {
						$subsubcategory_options_string .= "<option value='$subsubcategory->category_id' label='$subsubcategory->category_name'>$subsubcategory->category_name</option>";
					}
				}
			}

			$level = '';
			if($key == 1) {
				$level = Zend_Registry::get('Zend_Translate')->_('Category');
			}

			$category_options_string = "<option value='0' label=''></option>";
			foreach ($categories as $category) {
				$category->category_name = Zend_Registry::get('Zend_Translate')->_("$category->category_name");
				if($category_id == $category->category_id) {
					$category_options_string .= "<option value='$category->category_id' label='$category->category_name' selected='selected'>$category->category_name</option>";
				}
				else {
					$category_options_string .= "<option value='$category->category_id' label='$category->category_name'>$category->category_name</option>";
				}
			}

			if(Count($subcategories_prepared)) {
				$subCategoryElement = "<div class='fleft'>
						<div id='subcategory_id_$key"."_backgroundimage'></div>
						<div id='subcategory_id_$key-wrapper'>
							<div id='subcategory_id_$key-element'>
								<select name='subcategory_id_$key' id='subcategory_id_$key' onchange='changesubcategory(this.value, this.id);'>
									$subcategory_options_string
								</select>
							</div>
						</div>
					</div>";
			}
			else { 
				$subCategoryElement = "<div class='fleft'>
						<div id='subcategory_id_$key"."_backgroundimage'></div>
						<div id='subcategory_id_$key-wrapper' style='display:none;'>
							<div id='subcategory_id_$key-element'>
								<select name='subcategory_id_$key' id='subcategory_id_$key' onchange='changesubcategory(this.value, this.id);'>
									$subcategory_options_string
								</select>
							</div>
						</div>
					</div>";
			}

			if(Count($subsubcategories_prepared) && Count($subcategories_prepared)) {
				$subSubCategoryElement = "<div class='fleft'>
						<div id='subsubcategory_id_$key"."_backgroundimage'> </div>
						<div id='subsubcategory_id_$key-wrapper'>
							<div id='subsubcategory_id_$key-element'>
								<select name='subsubcategory_id_$key' id='subsubcategory_id_$key'>
									$subsubcategory_options_string
								</select>
							</div>
						</div>
					</div>";
			}
			else {
				$subSubCategoryElement = "<div class='fleft'>
						<div id='subsubcategory_id_$key"."_backgroundimage'> </div>
						<div id='subsubcategory_id_$key-wrapper'style='display:none;'>
							<div id='subsubcategory_id_$key-element'>
								<select name='subsubcategory_id_$key' id='subsubcategory_id_$key'>
									$subsubcategory_options_string
								</select>
							</div>
						</div>
					</div>";
			}

			echo "
			<div id='category_id_$key-wrapper' class='form-wrapper'>
				<div id='category_id_$key-label' class='form-label'>
					<label for='category_id_$key'>$level</label>
				</div>
				<div id='category_id_$key-element' class='form-element'>

					<div class='fleft'>
						<select name='category_id_$key' id='category_id_$key' onchange='subcategories(this.value, \"\", this.id);'>
								$category_options_string
						</select>
					</div>	

						$subCategoryElement

						$subSubCategoryElement

				</div>
			</div>

			";
		}
	}
?>