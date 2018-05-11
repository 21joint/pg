<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>

<div class="admin_seaocore_files_wrapper">
  <ul class="admin_seaocore_files seaocore_faq">	
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("Why pages of this plugin are coming blank at user side?");?></a>
      <div class='faq' style='display: none;' id='faq_1'>
       <?php echo $this->translate('In the beginning, all pages will be blank as there are no credits and transactions performed yet. But, as user start performing activities he will earn credits and all the widget will render on the pages of this plugin at user side.');?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("I am unable to set the credit value to be deducted for a few activities in case they are deleted by the member. Why so?");?></a>
      <div class='faq' style='display: none;' id='faq_2'>
       <?php echo $this->translate('Track for few activities are not saved that is why it is not possible to deduct any credits once those activities are deleted by members.<br/> 
        For example:<br/>
        - When someone replied on a comment on Event.<br/>
        - When someone replied on a comment on Event review.<br/>
        - When a user (subject) becomes friends with another user (object).<br/>
        - When a user (subject) updates their profile photo.');?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("I have set the credit validity for a specific time interval and my website is running with this settings for some time. Now, I would like to change the credit validity to lifetime. What will happen to the existing validity of credits earned by site members?");?></a>
      <div class='faq' style='display: none;' id='faq_3'>
        <?php echo $this->translate('Whether you change the credit validity from a specific time interval to lifetime or vice-versa, the new settings will be implemented for all your members. So, it is a suggestion not to change this setting frequently.<br/>

          1. Lifetime to a Specific Time Interval<br/>
          Suppose you have set the specific interval of time to 6 months. A user is earning / spending his credits since 16 months on your site. Now, the new validity of the user will be calculated in the cycle of 6 months i.e. 6 + 6 + 4 =  16. So, now new validity will be 2 months as he has already used 4 months. The credits of earlier 12 months will be lost.<br/>

          2. Specific Time Interval to Lifetime<br/>
          The credit validity of all users will change to lifetime and all the credits will be added back into user account which he had lost because the validity expired.<br/>

          3. Specific Time Interval to Specific Time Interval.<br/>
          Suppose earlier time interval was set to 7 months and now you have changed it to 5 months. A user is earning / spending his credits since 16 months on your site. Now, the new validity of the user will be calculated in the cycle of 7 months i.e. 7 + 7 + 2 =  16. So, now new validity will be 3 months as he has already used 2 month. The credits of earlier 14 months will be lost.');?>
        </div>
      </li>    
      <li>
        <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("Is it possible to set different credit earning limit for members at different member levels?");?></a>
        <div class='faq' style='display: none;' id='faq_4'>
          <?php echo $this->translate('Yes, it is possible to set different credit earning limit for members at different member levels. To do so follow below steps:<br/>
            1. Go to ‘Member Level Settings’ section of the admin panel of this plugin.<br/>
            2. Select the desired ‘Member Level’.<br/>
            3. Set the value for the field ‘Credit Earning Limit’.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("How credit earning limit is defined for a member?");?></a>
          <div class='faq' style='display: none;' id='faq_5'>
            <?php echo $this->translate('1. You can define credit earning limit on a per member level basis from ‘Member Level Settings’ section of the admin panel of this plugin.<br/>
            2. Credit earning limit is the total of ‘Credits Earned’ and ‘Debited Credits’ by a member.');?>
          </div>
        </li> 
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("Can a member switch between the member levels which have same credit value?");?></a>
          <div class='faq' style='display: none;' id='faq_6'>
            <?php echo $this->translate('Yes, a member can switch between the member levels which have same credit value. But, the member cannot go back to the previous member level even if he has the desired credit balance.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("What is the complete process of redeeming the credits on checkout page while purchasing the Event tickets or Store products?");?></a>
          <div class='faq' style='display: none;' id='faq_7'>
            <?php echo $this->translate('1. First, you need to purchase install both the ‘Advanced Events - Events Booking, Tickets Selling & Paid Events Extension’ and ‘Stores / Marketplace - Ecommerce’ if you don’t have these plugins on your site.<br/>
              2. Now, go to ‘Manage Modules’ and enable / disable installed modules on your site.<br/>
              3. Set the value for below fields:<br/>
              Minimum Credit Balance: This is the minimum credit value a user must have to redeem them on checkout page.<br/>
              Minimum Checkout Total: This is the minimum amount of products / tickets a user must have on the checkout page.<br/>
              Limit of Credit Use (%): How many credits a user can redeem on the checkout page, it is calculated on the basis of checkout total and available user’s credits.
              ');?>
            </div>
          </li>    
          <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate("What are the restrictions for members while redeeming the credits on checkout page for purchasing the Event tickets or Store products?");?></a>
            <div class='faq' style='display: none;' id='faq_8'>
              <?php echo $this->translate('The restriction for members while redeeming the credits on checkout page for purchasing the Event tickets or Store products is that Event and Store both should be admin driven.');?>
            </div>
          </li>
          <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate("Limit for credit use on checkout page can be set only in percentage. So, what will happen when final credit value redeemed is in decimal point?");?></a>
            <div class='faq' style='display: none;' id='faq_9'>
              <?php echo $this->translate('If the final credit value redeemed is in decimal point then we simply round it off to nearest integer value. Let’s take an example to understand this:<br/>
                Your credits = 1500<br/>
                Credit value of $1 = 10<br/>
                Limit of Credit Use (%) = 10%<br/>
                Checkout total = $9.8<br/>
                Credits that can be applied = 10% of $9.8 = $.98 = $.98 * 10 = 9.8 credits
                9.8 credits cannot be deducted from your credit balance, so we will round it to 10 credits. Final deduction from your credit balance will be of 10 credits.<br/>

                Round Off:<br/>
                1. If decimal value is equal to or greater than .5 then it will round off to next integer.<br/>
                Example:  9.8 = 10<br/>
                2. If decimal value is less than .5 then it will round off to previous integer.<br/>
                Example: 9.45 = 9');?>
              </div>
            </li> 
            <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate("I don’t want to show name of modules at user’s side as they are visible at admin panel side. Also, I would like to show all the activities of a module and its extension as a single entity. How can I do the needful changes?");?></a>
            <div class='faq' style='display: none;' id='faq_10'>
              <?php echo $this->translate('To change the name of modules to be visible at user’s side and to merge activities of a module and its various extensions, follow below steps:<br/>
1. Go to ‘Modules List’ section of the admin panel of this plugin.<br/>
2. Click on ‘Edit’ with respect to the module whose title you want to change. From here, you can also choose whether you want to merge two module’s activities and show them under one module item or not.<br/>
3. You can also drag these modules up and down to change their order at user’s side. ');?>
              </div>
            </li> 
            <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_11');"><?php echo $this->translate("I want to set ‘Limit for Credit Use’ based on different stores and products. How can I do so?");?></a>
            <div class='faq' style='display: none;' id='faq_11'>
              <?php echo $this->translate('You can set ‘Limit for Credit Use’ based on different stores and products by following below steps:<br/>
1. Go to ‘Global Settings’ → ‘General Settings’ section of the admin panel of this plugin.<br/>
2. Choose the ‘Credit Redemption Method’ between: All Stores, Store Wise and Product Wise.<br/>

Case 1: All Stores<br/>
- To set ‘Limit for Credit Use’ for all stores go to ‘Manage Modules’ section.<br/>
<br/>
Case 2: Store Wise<br/>
- You can set ‘Limit for Credit Use’ for a particular store while creating a new store or from ‘Dashboard’ → ‘Edit Info’ section of existing stores.<br/><br/>

Case 3: Product Wise<br/>
- You can set ‘Limit for Credit Use’ for a particular product while creating a new product or from ‘Dashboard’ → ‘Edit Info’ section of existing products.');?>
              </div>
            </li>               
          </ul>
        </div>
        