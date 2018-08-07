<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemailtemplates
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _set-templates.tpl 6590 2012-06-20 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
	
  	$siteTitle = '';
	$bodyContent = '';
  	$headerContent = '';
	$siteUrl = $this->siteUrl;
	$site_title = $this->site_title;
	if(!empty($this->img_path)) {
	$logo_photo = $this->img_path;
	}
	else {
		$logo_photo = 'application/modules/Sitemailtemplates/externals/images/web.png';
	}

  $upload_image = explode('/',$logo_photo);
  $encoded_image = rawurlencode($upload_image[2]);
  if($upload_image[0] == 'application' && $upload_image[1] == 'modules' && $upload_image[2] == 'Sitemailtemplates' && $upload_image[3] == 'externals') {
		$path = ( _ENGINE_SSL ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']. $this->baseUrl(). '/'.$logo_photo;
	}
	else {
	  $path = ( _ENGINE_SSL ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']. $this->baseUrl(). '/'.'public/admin'.'/'.$encoded_image;
	}
?>

<?php $bodyContent .= '<div style="text-align:center;overflow:hidden;">' ;?>

<?php if($this->show_icon && $this->sitelogo_location == 'body'):?>
	<?php	$bodyContent .= '<div style="float:' .$this->sitelogo_position. '"><a href="' . $siteUrl . '" target="_blank"><img src="'.$path.'" style="max-width:800px;vertical-align: middle;" border="0" /></a></div>';?>
	
<?php endif;?>
	
<?php if($this->show_title && $this->sitetitle_location == 'body'):?>
	<?php	$bodyContent .= '<div style="margin:0 10px;float:' .$this->sitetitle_position. ';font-family:' .$this->sitetitle_fontfamily. ';font-size:' .$this->sitetitle_fontsize. 'px;"><a href="' .$siteUrl .'" target="_blank" style="text-decoration:none; color:' . $this->header_titlecolor. ';font-weight:bold;">' .$site_title. '</a></div>'; ;?>
<?php endif;?>
	
<?php if($this->show_tagline && $this->tagline_location == 'body'):?>
	<?php	$bodyContent .= '<div style="margin:0 10px;float:' .$this->tagline_position. ';font-family:' .$this->tagline_fontfamily. ';font-size:' .$this->tagline_fontsize. 'px;color:' .$this->header_tagcolor. ';">' .$this->tagline_title. '</div>';?>
<?php endif;?>

<?php $bodyContent .= '</div>'; ?>

<?php if($this->tagline_location == 'above_header'):?>
 <?php $headerContent .= '<tr><td style="text-align:center;"><div style="margin:0 10px 5px;float:' .$this->tagline_position. ';font-family:' .$this->tagline_fontfamily. ';font-size:' .$this->tagline_fontsize. 'px;color:' .$this->header_tagcolor. ';">' .$this->tagline_title. '</div></td></tr>';?>
<?php endif;?>

<?php 
	$description = Zend_Registry::get('Zend_Translate')->_("<p><span style='color: #92999C;'>If you are a member of&nbsp;  <a href='%s' target='_parent'>$site_title</a> and do not want to receive these emails from us in the future, then visit your account settings to manage email notifications. To continue receiving our emails, please add us to your address book or safe list.</span></p>");
	$description= sprintf($description, $siteUrl);

	if($this->show_icon && $this->sitelogo_location == 'header') {
		
		//$path_img = 'http://' . $_SERVER['HTTP_HOST'] . $logo_photo;
		if (!empty($path)) {
			if($this->show_title && $this->sitetitle_location == 'header') {
			$siteTitle .= '<div style="float:' .$this->sitelogo_position. '"><a href="' . $siteUrl . '" target="_blank"><img src="'.$path.'" style="max-width:800px;vertical-align: middle;" border="0" /></a></div>';
			}
			else {
				$siteTitle .= '<div style="float:' .$this->sitelogo_position. '"><a href="' . $siteUrl . '" target="_blank"><img alt="'.$site_title.'" src="'.$path.'" style="max-height:800px;vertical-align: middle;" border="0" /></a></div>';
			}
		}
	}
  if($this->show_title && $this->sitetitle_location == 'header') {
		$siteTitle .= '<div style="margin:0 10px;float:' .$this->sitetitle_position. ';font-family:' .$this->sitetitle_fontfamily. ';font-size:' .$this->sitetitle_fontsize. 'px;"><a href="' .$siteUrl. '" target="_blank" style="text-decoration:none; color:' . $this->header_titlecolor. ';font-weight:bold;">' .$site_title. '</a></div>';
 	}
  if($this->show_tagline && $this->tagline_location == 'header') {
		$siteTitle .= '<div style="margin:0 10px;float:' .$this->tagline_position. ';font-family:' .$this->tagline_fontfamily. ';font-size:' .$this->tagline_fontsize. 'px;color:' .$this->header_tagcolor. ';">' .$this->tagline_title. '</div>';
 	}
?>

<?php if(($this->show_title && $this->sitetitle_location == 'header') || ($this->show_icon && $this->sitelogo_location == 'header') || ($this->show_tagline && $this->tagline_location == 'header')):?> 
	<?php $headerContent .= '<tr><td style="background-color:' .$this->header_bgcol. ';padding:' .$this->header_outpadding. 'px;vertical-align:middle;text-align:center"> ' .$siteTitle. '</td></tr>' ;?>
	
<?php endif;?>

<?php $html = $bodyContent.$this->bodyHtmlTemplate;?>

<?php echo $bodyHtmlTemplate = '

	<html>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Righteous" rel="stylesheet">
	<head>
		<style>	
	 		body{
				background-color:#F5F5F5;
			 }
		
		</style>
	</head>
	
	<body bgcolor="#bae1eb"  style="background-color:#F5F5F5;padding:70px 0px;">
	
		<table align="center"  border="0" cellpadding="0" cellspacing="0"  id="bodyTable">
			<tr>
				<td valign="top">
					<table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer">
						<!-- logo holder -->
						<tr bgcolor=" #FFF">
							<td valign="top">
								<table border="0" cellpadding="20" cellspacing="0"  id="emailHeader">
									<tr>
										<td valign="top">
											<a href="#">
												<img style="width:50%;" src="https://i.imgur.com/rG8vDO3.png"/>
											</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<!-- body holder -->
						<tr class="body-holder" bgcolor=" #FFF">
							<td valign="top">
								<table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailBody">
									<tr>
										<td valign="top" class="body-text "style="font-size:20px;font-family:&quot;Open Sans&quot;, sans-serif;font-weight:400;" >
											' .$html. '
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<!-- footer -->
						<tr>
							<td valign="top" style="padding:0;">
								<table border="0"  cellspacing="0" width="100%" id="emailFooter">
									<tr>
										<!-- footer text -->
										<td  valign="top" style="margin-top:30px;display:block;color:#92999c;font-size:14px;font-family:&quot;Open Sans&quot;, sans-serif;font-weight: 400;">
											If you are a member of <a style="text-decoration:none;color:#FFD819!important;" href="#">Parental Guidance - Integration</a> and do not want to receive these emails from us in the future, then please <a style="text-decoration:none;color:#FFD819!important;" href="#">click here.</a>
											To continue receiving our emails, please add us to your address book or safe list.
										</td>
									</tr>
								</table>
							</td>
                    	</tr>
	
						<!-- bottom social holder and coppyright -->
						<tr >
							<td valign="top" style="padding:0;">
								<table border="0"  cellspacing="0" width="100%" id="emailFooter">
									<tr>
										<!-- footer copy right -->
										<td>
											<h5 style="font-size:14px;margin:0; font-weight: normal;color: #93A4A6;">Copyright &copy;2018</h5>
										</td>
					
										<!-- social icon -->
										<td width="8%">
											<a style="float:right;" href="https://www.facebook.com/officialguidanceguide/">
												<img style="width:30px;height:30px;display:block;margin:0" src="https://i.imgur.com/UYOEgCO.png"/>
											</a>
										</td>
										<td width="8%">
											<a style="float:right;" href="https://twitter.com/guidanceguide?lang=en">
												<img style="width:30px;height:30px;display:block; margin:0" src="https://i.imgur.com/ha5uXHO.png"/>
											</a>
										</td>
										<td width="8%">
											<a style="float:right;" href="https://instagram.com/@guidanceguide">
												<img style="width:30px;height:30px;display:block;margin:0" src="https://i.imgur.com/IakWFWW.png"/>
											</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	
	</body>
	</html>
';?>





