<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SitemapIndex.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_View_Helper_SitemapIndex extends Zend_View_Helper_Navigation_Sitemap
{
    /**
     * Namespace for the <urlset> tag
     *
     * @var string
     */
    const SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * Schema URL
     *
     * @var string
     */
    const SITEMAP_XSD = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';

    /**
     * Whether the Sitemap Stylesheet should be included in XML output
     *
     * @var bool
     */
    protected $_useXslStylesheet = true;

    public function sitemapIndex(Zend_Navigation_Container $container = null)
    {
        if (null !== $container) {
            $this->setContainer($container);
        }

        return $this;
    }




    /**
     * Sets whether the Sitemap Stylesheet should be used in output
     *
     * @param  bool $useXslStylesheet                     whether XML delcaration
     *                                              should be rendered
     * @return Zend_View_Helper_Navigation_Sitemap  fluent interface, returns
     *                                              self
     */
    public function setUseXslStylesheet($useXslStylesheet)
    {
        $this->_useXslStylesheet = (bool) $useXslStylesheet;
        return $this;
    }

    /**
     * Returns whether the Sitemap Stylesheet should be used in output
     *
     * @return bool  whether the Sitemap Stylesheet should be used in output
     */
    public function getuseXslStylesheet()
    {
        return $this->_useXslStylesheet;
    }
    /**
     * Returns a DOMDocument containing the Sitemap XML for the given container
     *
     * @param  Zend_Navigation_Container $container  [optional] container to get
     *                                               breadcrumbs from, defaults
     *                                               to what is registered in the
     *                                               helper
     * @return DOMDocument                           DOM representation of the
     *                                               container
     * @throws Zend_View_Exception                   if schema validation is on
     *                                               and the sitemap is invalid
     *                                               according to the sitemap
     *                                               schema, or if sitemap
     *                                               validators are used and the
     *                                               loc element fails validation
     */
    public function getDomSitemap(Zend_Navigation_Container $container = null)
    {
        if (null === $container) {
            $container = $this->getContainer();
        }

        // check if we should validate using our own validators
        if ($this->getUseSitemapValidators()) {
            // require_once 'Zend/Validate/Sitemap/Changefreq.php';
            // require_once 'Zend/Validate/Sitemap/Lastmod.php';
            // require_once 'Zend/Validate/Sitemap/Loc.php';
            // require_once 'Zend/Validate/Sitemap/Priority.php';

            // create validators
            $locValidator        = new Zend_Validate_Sitemap_Loc();
            $lastmodValidator    = new Zend_Validate_Sitemap_Lastmod();
            $changefreqValidator = new Zend_Validate_Sitemap_Changefreq();
            $priorityValidator   = new Zend_Validate_Sitemap_Priority();
        }

        // create document
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = $this->getFormatOutput();
        if($this->getuseXslStylesheet()) {
            $url = $this->view->absoluteUrl($this->view->baseUrl('application/modules/Siteseo/externals/styles/sitemap.xsl'));
            $xslt = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="' . $url . '"');
            $dom->appendChild($xslt);
        }

        // ...and urlset (root) element
        $urlSet = $dom->createElementNS(self::SITEMAP_NS, 'sitemapindex');
        $dom->appendChild($urlSet);

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::SELF_FIRST);

        $maxDepth = $this->getMaxDepth();
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }
        $minDepth = $this->getMinDepth();
        if (!is_int($minDepth) || $minDepth < 0) {
            $minDepth = 0;
        }

        // iterate container
        foreach ($iterator as $page) {
            if ($iterator->getDepth() < $minDepth || !$this->accept($page)) {
                // page should not be included
                continue;
            }

            // get absolute url from page
            if (!$url = $this->url($page)) {
                // skip page if it has no url (rare case)
                continue;
            }

            // create url node for this page
            $urlNode = $dom->createElementNS(self::SITEMAP_NS, 'sitemap');
            $urlSet->appendChild($urlNode);

            if ($this->getUseSitemapValidators() &&
                !$locValidator->isValid($url)) {
                // require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception(sprintf(
                        'Encountered an invalid URL for Sitemap XML: "%s"',
                        $url));
                $e->setView($this->view);
                throw $e;
            }

            // put url in 'loc' element
            $urlNode->appendChild($dom->createElementNS(self::SITEMAP_NS,
                                                        'loc', $url));

            // add 'lastmod' element if a valid lastmod is set in page
            if (isset($page->lastmod)) {
                $lastmod = strtotime((string) $page->lastmod);

                // prevent 1970-01-01...
                if ($lastmod !== false) {
                    $lastmod = date('c', $lastmod);
                }

                if (!$this->getUseSitemapValidators() ||
                    $lastmodValidator->isValid($lastmod)) {
                    $urlNode->appendChild(
                        $dom->createElementNS(self::SITEMAP_NS, 'lastmod',
                                              $lastmod)
                    );
                }
            }

            // add 'changefreq' element if a valid changefreq is set in page
            if (isset($page->changefreq)) {
                $changefreq = $page->changefreq;
                if (!$this->getUseSitemapValidators() ||
                    $changefreqValidator->isValid($changefreq)) {
                    $urlNode->appendChild(
                        $dom->createElementNS(self::SITEMAP_NS, 'changefreq',
                                              $changefreq)
                    );
                }
            }

            // add 'priority' element if a valid priority is set in page
            if (isset($page->priority)) {
                $priority = $page->priority;
                if (!$this->getUseSitemapValidators() ||
                    $priorityValidator->isValid($priority)) {
                    $urlNode->appendChild(
                        $dom->createElementNS(self::SITEMAP_NS, 'priority',
                                              $priority)
                    );
                }
            }
        }

        // validate using schema if specified
        if ($this->getUseSchemaValidation()) {
            if (!@$dom->schemaValidate(self::SITEMAP_XSD)) {
                // require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception(sprintf(
                        'Sitemap is invalid according to XML Schema at "%s"',
                        self::SITEMAP_XSD));
                $e->setView($this->view);
                throw $e;
            }
        }

        return $dom;
    }

    // Zend_View_Helper_Navigation_Helper:

    /**
     * Renders helper
     *
     * Implements {@link Zend_View_Helper_Navigation_Helper::render()}.
     *
     * @param  Zend_Navigation_Container $container  [optional] container to
     *                                               render. Default is to
     *                                               render the container
     *                                               registered in the helper.
     * @return string                                helper output
     */
    public function render(Zend_Navigation_Container $container = null)
    {
        $dom = $this->getDomSitemap($container);

        $xml = $this->getUseXmlDeclaration() ?
               $dom->saveXML() :
               $dom->saveXML($dom->documentElement);

        return rtrim($xml, self::EOL);
    }
}
