<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:param name="filenamesDoc"/> <!-- parameter: filepath to file with references to input files -->
	<xsl:output method="html"/>
    
	<xsl:template match="/">
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
				<title></title>
				<link rel="shortcut icon" href="icon.ico" type="image/x-icon"/>
				<link rel="stylesheet" type="text/css" href="style.css"/>
			</head>
			<body>
                <!-- FILE ITERATION (multiple input files in one output) -->
				<xsl:for-each select="document($filenamesDoc)/filenames/file/text()">
					<xsl:variable name="file" select="document(string(.))"/>
					<div class="ticket">
                        
                        <!-- HEAD -->
						<div class="header">
							<xsl:if test="$file/rss/channel/item/type/text() = 'Bug'">
								<img class="bugtype" src="images/bug.jpg"/>
							</xsl:if>
							
							<p class="id">
								<xsl:choose>
									<xsl:when test="contains($file/rss/channel/item/key/text(),'DMF')">
										<xsl:value-of select="substring-after($file/rss/channel/item/key/text(),'-')"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="$file/rss/channel/item/key/text()"/>
									</xsl:otherwise>
								</xsl:choose> 
							</p>
							
							<p class="reporter">
                                <!--<xsl:text>(</xsl:text>-->
								<xsl:text>(R) </xsl:text>
								<xsl:value-of select="$file/rss/channel/item/reporter"/>
                                <!--<xsl:text>)</xsl:text>-->
							</p>
						</div>
                
                        <!-- CONTENT -->
						<div class="content">
							<p class="title">
								<xsl:value-of select="$file/rss/channel/item/summary"/>
							</p>
                
                            <!--<div class="description">
                                <p>
                                    <xsl:choose>
                                        <xsl:when test="string-length($file/rss/channel/item/description/text()) > 800">
                                            <xsl:call-template name="prepareString">
                                                <xsl:with-param name="inputString">
                                                    <xsl:value-of select="concat(substring($file/rss/channel/item/description, 0, 800), '...')" disable-output-escaping="yes"/>
                                                </xsl:with-param>
                                            </xsl:call-template>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:call-template name="prepareString">
                                                <xsl:with-param name="inputString">
                                                    <xsl:value-of select="$file/rss/channel/item/description" disable-output-escaping="yes"/>
                                                </xsl:with-param>
                                            </xsl:call-template>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </p>
                            </div>-->
						</div>
                
                        <!-- FOOTER -->
						<div class="footer">
							<p class="assignee">
                                <!--<xsl:text>(</xsl:text>-->
								<xsl:text>(A) </xsl:text>
								<xsl:value-of select="$file/rss/channel/item/assignee"/>
                                <!--<xsl:text>)</xsl:text>-->
							</p>
							
                            <!--<ul class="hasSubtasks">
                                <xsl:for-each select="$file/rss/channel/item/hasSubtasks/subtask">
                                    <xsl:sort select="text()"/>
                                    <li class="subtask">
                                        <div class="checkbox">
                                            <xsl:value-of select="substring(text(),5)"/>
                                        </div>
                                    </li>
                                </xsl:for-each>
                            </ul>-->
						</div>
						
					</div>
                    
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
    
    <!-- SUBTASK TEMPLATE -->
    <!--<xsl:template match="subtask">
        <li class="subtask">
            <div>
                <xsl:value-of select="substring(text(),5)"/>
                <div class="checkbox"/>
            </div>
        </li>
    </xsl:template>-->
    
    <!-- TEMPLATES FOR DESRCIPTION TEXT PREPARATION -->
	<xsl:template name="prepareString">
		<xsl:param name="inputString"/>
		<xsl:call-template name="removeLinksBeginnings">
			<xsl:with-param name="input"> 
				<xsl:call-template name="stripTags">
					<xsl:with-param name="input">  
						<xsl:value-of select="$inputString"/>
					</xsl:with-param>
				</xsl:call-template>
			</xsl:with-param>
		</xsl:call-template>
	</xsl:template>
    
	<xsl:template name="stripTags">
		<xsl:param name="input"/>
		<xsl:choose>
			<xsl:when test="contains($input,'&lt;p&gt;')">
				<xsl:variable name="remainingString" select="substring-after($input,'&lt;p&gt;')"/>
				<xsl:choose>
					<xsl:when test="contains($remainingString,'&lt;/p&gt;')">
						<xsl:value-of select="substring-before($remainingString,'&lt;/p&gt;')"/>
						<xsl:call-template name="stripTags">
							<xsl:with-param name="input" select="substring-after($remainingString,'&lt;p&gt;')"/>
						</xsl:call-template>
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="$remainingString"/>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$input"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
    
	<xsl:template name="removeLinksBeginnings">
		<xsl:param name="input"/>
		<xsl:variable name="remainingString" select="$input"/>
		<xsl:choose>
			<xsl:when test="contains($remainingString,'&lt;a href=&quot;')">
				<xsl:value-of select="substring-before($remainingString,'&lt;a href=&quot;')"/>
				<xsl:call-template name="removeLinksEndings">
					<xsl:with-param name="input" select="substring-after($remainingString,'&quot;&gt;')"/>
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$remainingString"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
    
	<xsl:template name="removeLinksEndings">
		<xsl:param name="input"/>
		<xsl:variable name="remainingString" select="$input"/>
		<xsl:choose>
			<xsl:when test="contains($remainingString,'&lt;/a&gt;')">
				<xsl:value-of select="substring-before($remainingString,'&lt;/a&gt;')"/>
				<xsl:call-template name="removeLinksBeginnings">
					<xsl:with-param name="input" select="substring-after($remainingString,'&lt;/a&gt;')"/>
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$remainingString"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
    
</xsl:stylesheet>
