<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Helps.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Model_DbTable_Helps extends Engine_Db_Table
{
  protected $_rowClass = "Sitefaq_Model_Help";

	 /**
   * Make sitefaq helpful
   * @param int $faq_id : sitefaq id
	 * @param int $owner_id : user id
	 * @param int $helpful : helpful value
   */

  public function setHelful($faq_id, $owner_id, $helpful,$option_id) {

    //FETCH DATA
    $done_helpful = $this->select()
                    ->from($this->info('name'), array('faq_id'))
                    ->where('faq_id = ?', $faq_id)
                    ->where('owner_id = ?', $owner_id)
                    ->query()
                    ->fetchColumn();

		//INSERT HELPFUL ENTRIES IN TABLE
    if (empty($done_helpful)) {
      $this->insert(array(
          'faq_id' => $faq_id,
          'owner_id' => $owner_id,
          'helpful' => $helpful,
          'option_id' => $option_id,
          'modified_date' => new Zend_Db_Expr('NOW()')
      ));
    }
		else{
      $this->update(array(
          'helpful' => $helpful,
          'option_id' => $option_id,
          'modified_date' => new Zend_Db_Expr('NOW()')
      ),array(
          'faq_id = ?' => $faq_id,
          'owner_id = ?' => $owner_id,
      ));
		}

		$helpful_value = $this->countHelpful($faq_id, 1);

		if($helpful_value == null) {
			$helpful_value = -1;
		}
		elseif($helpful_value == 200) {
			$helpful_value = 0;
		}

		Engine_Api::_()->getDbtable('faqs', 'sitefaq')->update(array(
				'helpful' => $helpful_value
		),array(
				'faq_id = ?' => $faq_id,
		));

  }

	/**
   * Get previous helpful answer
   * @param int $faq_id : sitefaq id
	 * @param int $viewer_id : viewer id
   */
  public function getHelpful($faq_id, $viewer_id) {

		//RETURN NULL IF FAQ ID IS NULL
		if(empty($faq_id) || empty($viewer_id)) {
			return 0;
		}

		//FETCH DATA
    $previousHelpMark = $this->select()
                    ->from($this->info('name'), array('helpful'))
                    ->where('faq_id = ?', $faq_id)
                    ->where('owner_id = ?', $viewer_id)
                    ->query()
										->fetchColumn();

		//RETURN DATA
    if (!empty($previousHelpMark)) {
      return $previousHelpMark;
		}
    
		return 0;
  }

	/**
   * Get helpful datas
   * @param int $faq_id
   * @param int $final_value
	 * @return helpful datas
   */
  public function countHelpful($faq_id, $final_value = 1) {

		//RETURN NULL IF FAQ ID IS NULL
		if(empty($faq_id)) {
			return null;
		}

		$totalHelpsData = array();

    //FETCH TOTAL YES
		$totalHelpsData['total_yes'] = 0;
    $totalHelpsData['total_yes'] = $this->select()
                    ->from($this->info('name'), array('COUNT(faq_id) AS total_count'))
                    ->where('faq_id = ?', $faq_id)
										->where('helpful = ?', 2)
                    ->query()
                    ->fetchColumn();

    //FETCH TOTAL NO
		$totalHelpsData['total_no'] = 0;
    $totalHelpsData['total_no'] = $this->select()
                    ->from($this->info('name'), array('COUNT(faq_id) AS total_count'))
                    ->where('faq_id = ?', $faq_id)
										->where('helpful = ?', 1)
                    ->query()
                    ->fetchColumn();

		//GET TOTAL
		$totalHelpsData['total_marks'] = $totalHelpsData['total_yes'] + $totalHelpsData['total_no'];

		//RETURN VALUE
		if(!empty($final_value)) {

			if(empty($totalHelpsData['total_yes']) && !empty($totalHelpsData['total_no'])) {
				return 200;
			}
			elseif(!empty($totalHelpsData['total_yes']) && empty($totalHelpsData['total_no'])) {
				return 100;
			}
			elseif(empty($totalHelpsData['total_yes']) && empty($totalHelpsData['total_no'])) {
				return null;
			}
			else {
				$final_value = ($totalHelpsData['total_yes']/($totalHelpsData['total_marks']))*100;
				$final_value = round($final_value);
				return $final_value;
			}
		}
		else {
			return $totalHelpsData;
		}
  }

}