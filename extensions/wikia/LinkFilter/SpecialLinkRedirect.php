<?php
class LinkRedirect extends SpecialPage {

	
	function LinkRedirect(){
		parent::__construct("LinkRedirect");
	}
	
	function execute(){
		global $wgRequest, $IP, $wgOut, $wgUser;
		
		$wgOut->setArticleBodyOnly(true);
		$sk = $wgUser->getSkin();
		$url = $wgRequest->getVal("url");
		$wgOut->addHTML("
			<html>
				<body onload=window.location=\"{$url}\">
				{$sk->bottomScripts()}
				</body>
			</html>");
		
		return "";
	
	}
  
}


?>