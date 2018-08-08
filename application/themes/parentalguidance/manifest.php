<?php return array(
  'package' =>
    array(
      'type'        => 'theme',
      'name'        => 'Parental Guidance',
      'version'     => null,
      'revision'    => '$Revision: 10267 $',
      'path'        => 'application/themes/parentalguidance',
      'repository'  => 'socialengine.com',
      'title'       => 'Parental Guidance',
      'thumb'       => 'default_theme.jpg',
      'author'      => 'Parental Guidance - Integration',
      'actions'     =>
        array(
          0 => 'install',
          1 => 'upgrade',
          2 => 'refresh',
          3 => 'remove',
        ),
      'callback'    =>
        array(
          'class' => 'Engine_Package_Installer_Theme',
        ),
      'directories' =>
        array(
          0 => 'application/themes/parentalguidance',
        ),
      'description' => '',
    ),
  'files'   =>
    array(
      0 => 'theme.css',
      1 => 'constants.css',
      2 => 'mobile.css',
    ),
); ?>
