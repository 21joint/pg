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

<?php $bodyContent .= '<div style="text-align:center;overflow:hidden;">';?>
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

	<section class="email" style="display: flex;align-items: center;justify-content: center;margin-top: 4rem;margin-bottom: 4rem;">
        <div class="container" style=" width: 39%;">
            <div class="header" style=" padding-left: 3rem;padding-top: 1.5rem;padding-bottom:0.3rem;background-color: #F5F5F5;display: flex;align-items: center;justify-content: flex-start;">
				<div class="image-holder">
                    <a href="#">
                        <img src="https://i.imgur.com/rG8vDO3.png"/>
                    </a>
                </div>
            </div> <!--end of header -->
                
            <div class="main" style="padding: 3rem;background-color: #F5F5F5;margin-bottom: 1rem;">

                <div class="row-one" style="margin-bottom: 6rem;">
                    <div class="text-holder">
					    ' .$html. '
                    </div>
                </div>
            </div> <!-- end of main -->

            <div class="footer" style="color: #93A4A6;">

				<div class="footer-upper">
	 				<div style="font-size: 14px!important;
					 color: #93A4A6!important;">
					'.$this->textofFooter .'
					</div>
                </div>

                <div class="footer-bottom" style="margin-top: 1rem;display: flex;justify-content: space-between;">

                    <div class="left-side" style="width: 78%;">
                        <h5 style="font-weight: normal;color: #93A4A6;">Copyright ©2018</h5>
                    </div>
                    <div class="right-side" style="width: 22%;">
                        <ul class="social-icons" style="padding:0;list-style: none;display: flex;
						justify-content: space-between; align-items: center;">
							<li>
								<a style="width:30px;height:30px;display:block;" href="https://www.facebook.com/officialguidanceguide/">
									<img style="width:100%;height:100%;" src="https://i.imgur.com/UYOEgCO.png"/>
								</a>
							</li>
							<li>
								<a style="width:30px;height:30px;display:block;"  href="https://twitter.com/guidanceguide?lang=en">
									<img style="width:100%;height:100%;" src="https://i.imgur.com/ha5uXHO.png"/>
								</a>
							</li>
							<li>
								<a style="width:30px;height:30px;display:block;" href="https://instagram.com/@guidanceguide">
									<img style="width:100%;height:100%;" src="https://i.imgur.com/IakWFWW.png"/>
								</a>
							</li>
                        </ul>
                    </div>

                </div>

            </div> 
        </div> 
	</section> 
';?>





