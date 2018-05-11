<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>Ultimate SEO / Sitemaps Plugin</h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<p>
  Here, you can monitor the ranking of the keywords on Google. You can check the position of the search result consisting of the particular keyword and can redirect to that webpage. You can also view the Google search result for a keyword.
</p>
<div>
  <b>[Note: Top 50 search results of Google are considered here.]</b>
</div>
<br />
 To add / update new keywords, please click <a href="<?php echo $this->baseUrl('/admin/core/settings/general#site_keywords'); ?>">here</a>.
<br />
<br />
<?php if( empty($this->keywordsRanking) ): ?>
  <div class="tip">
    <span>
      You have not added any keyowrds.
    </span>
  </div>
<?php else: ?>
  <table class='admin_table' >
    <thead>
      <tr>
        <th style='width: 40%;'>Keyword / Phrase</th>
        <th style='width: 50%;'>Google Rank</th>
        <th style='width: 10%;'>View On Google</th>
      </tr>
    </thead>
    <?php foreach( $this->keywordsRanking as $keyword => $rankings ): ?>

      <tr>
        <td style='width: 40%;'><?php echo $keyword ?></td>
        <td style='width: 50%;'>
          <?php $sep = ''; ?>
          <?php if( empty($rankings) ): ?>
            Not Found
          <?php endif; ?>
          <?php foreach( $rankings as $rank ): ?>
            <?php echo $sep; ?>
            <?php $sep = ', '; ?>
            <a href="<?php echo $rank['url'] ?>" target="_blank"><?php echo $rank['rank'] ?></a>
          <?php endforeach; ?>
        </td>
        <td style='width: 10%;' class='admin_table_options'>
          <?php $googleSearchUrl = 'https://www.google.com/search?q=%s&num=50&start=0';
          $keyword = str_replace(' ', '+', trim($keyword));
          ?>
          <a href="<?php echo sprintf($googleSearchUrl, $keyword) ?>" target="_blank">View</a>
        </td>
      </tr>
  <?php endforeach; ?>
  </table>
<?php endif; ?>