<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet 
    version="1.0"
    xmlns:sm="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
    xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
    xmlns:fo="http://www.w3.org/1999/XSL/Format"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
    xmlns="http://www.w3.org/1999/xhtml">

    <xsl:output method="html" indent="yes" encoding="UTF-8"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>
                    XML Sitemap
                    <xsl:if test="sm:sitemapindex"> Index</xsl:if>
                </title>
                <link rel="stylesheet" href="../../application/modules/Siteseo/externals/styles/sitemap.css"/>
            </head>
            <body>
                <div id="container">
                    <h1>
                        XML Sitemap<xsl:if test="sm:sitemapindex"> Index</xsl:if>
                    </h1>
                    <h4>
                        <xsl:choose>
                            <xsl:when  test="sm:sitemapindex"> 
                                Total sitemap files listed in this sitemap index file: <xsl:value-of select="count(sm:sitemapindex/sm:sitemap)"/>
                            </xsl:when>
                            <xsl:otherwise> 
                                Total URLs in this sitemap file: <xsl:value-of select="count(sm:urlset/sm:url)"/>
                            </xsl:otherwise>
                        </xsl:choose>
                    </h4>
                    <xsl:apply-templates />
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="sm:sitemapindex">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th style="width:7%">S No.</th>
                <th>URL</th>
                <th>Last Modified</th>
            </tr>
            <xsl:for-each select="sm:sitemap">
                <tr> 
                    <xsl:variable name="loc"><xsl:value-of select="sm:loc"/></xsl:variable>
                    <xsl:variable name="pno"><xsl:value-of select="position()"/></xsl:variable>
                    <td><xsl:value-of select="$pno"/></td>
                    <td><a href="{$loc}" target="_blank"><xsl:value-of select="sm:loc"/></a></td>
                    <xsl:apply-templates/> 
                </tr>
            </xsl:for-each>
        </table>
    </xsl:template>

    <xsl:template match="sm:urlset">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th style="width:5%">S No.</th>
                <th>URL</th>
                <xsl:if test="sm:url/sm:lastmod"><th>Last Modified</th></xsl:if>
                <xsl:if test="sm:url/sm:changefreq"><th>Change Frequency</th></xsl:if>
                <xsl:if test="sm:url/sm:priority"><th>Priority</th></xsl:if>
            </tr>
            <xsl:for-each select="sm:url">
                <tr> 
                    <xsl:variable name="loc"><xsl:value-of select="sm:loc"/></xsl:variable>
                    <xsl:variable name="pno"><xsl:value-of select="position()"/></xsl:variable>
                    <td><xsl:value-of select="$pno"/></td>
                    <td><a href="{$loc}" target="_blank"><xsl:value-of select="sm:loc"/></a></td>
                    <xsl:apply-templates select="sm:*"/> 
                </tr>
                <xsl:apply-templates select="xhtml:*"/> 
            </xsl:for-each>
        </table>
    </xsl:template>

    <xsl:template match="sm:loc|image:loc|image:caption|video:*">
    </xsl:template>

    <xsl:template match="sm:lastmod|sm:changefreq|sm:priority">
        <td>
            <xsl:apply-templates/>
        </td>
    </xsl:template>

    <xsl:template match="xhtml:link">
        <tr> 
            <xsl:variable name="altloc"><xsl:value-of select="@href"/></xsl:variable>
            <td><xsl:value-of select="@hreflang"/></td>
            <td class="url2"><a href="{$altloc}"><xsl:value-of select="@href"/></a></td>
            <td colspan="5"></td>
            <xsl:apply-templates/> 
        </tr>
    </xsl:template>

</xsl:stylesheet>
