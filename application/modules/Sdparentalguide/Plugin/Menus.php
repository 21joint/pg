<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Menus
{
  public function editPreferences($row)
  {
    return array(
      'label' => $row->label,
      'class' => $row->name == 'user_profile_preferences'?'icon_edit':'',
      'route' => 'sdparentalguide_preferences',
      'params' => array(
        'controller' => 'preferences',
        'action' => 'edit'
      )
    );
  }
  
  public function editFamilyMembers($row)
  {
    return array(
      'label' => $row->label,
      'class' => $row->name == 'user_profile_familymembers'?'icon_edit':'',
      'route' => 'sdparentalguide_family',
      'params' => array(
        'controller' => 'family',
        'action' => 'index'
      )
    );
  }
}