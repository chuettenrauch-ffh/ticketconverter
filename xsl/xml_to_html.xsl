<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
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
				<xsl:for-each select="tickets/ticket">
					<div class="ticket">
                        
                        <!-- HEAD -->
						<div class="header">
							<xsl:if test="parent">
								<p class="parent">
									<xsl:value-of select="parent/key"/>
									<xsl:text>   </xsl:text>
									<xsl:choose>
										<xsl:when test="string-length(parent/summary/text()) > 50">
											<xsl:value-of select="concat(substring(parent/summary, 0, 47), '...')"/>
										</xsl:when>
										<xsl:otherwise>
											<xsl:value-of select="parent/summary"/>
										</xsl:otherwise>
									</xsl:choose>
								</p>
							</xsl:if>	
							
							<xsl:if test="type/text() = 'Bug'">
								<img class="bugtype" src="images/bug.jpg"/>
							</xsl:if>
							
							<p class="id">
								<xsl:choose>
									<xsl:when test="contains(key/text(), 'DMF')">
										<xsl:value-of select="substring-after(key,'-')"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="key"/>
									</xsl:otherwise>
								</xsl:choose> 
							</p>
							
							<p class="reporter">
								<xsl:text>(R) </xsl:text>
								<xsl:value-of select="reporter"/>

							</p>
						</div>
                
                        <!-- CONTENT -->
						<div class="content">
							<p class="title">
								<xsl:value-of select="summary"/>
							</p>
						</div>
                
                        <!-- FOOTER -->
						<div class="footer">
							<p class="assignee">
								<xsl:text>(A) </xsl:text>
								<xsl:value-of select="assignee"/>
							</p>
							
							<p class="devteam">
								<xsl:value-of select="devteam" />
							</p>
	
						</div>
						
					</div>
                    
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
    
</xsl:stylesheet>
