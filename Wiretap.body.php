<?php

class Wiretap {

	static $referers = null;
	
	/**
	 *
	 *
	 *
	 **/
	public static function updateTable( &$title, &$article, &$output, &$user, $request, $mediaWiki ) {
		
		$output->enableClientCache( false );
		$output->addMeta( 'http:Pragma', 'no-cache' );

		$now = time();
		$hit = array(
			'page_id' => $title->getArticleId(),
			'page_name' => $title->getFullText(),
			'user_name' => $user->getName(),
			'hit_timestamp' => wfTimestampNow(),
			
			'hit_year' => date('Y',$now),
			'hit_month' => date('m',$now),
			'hit_day' => date('d',$now),
			'hit_hour' => date('H',$now),
			'hit_weekday' => date('w',$now), // 0' => sunday, 1=monday, ... , 6=saturday

			'page_action' => $request->getVal( 'action' ),
			'oldid' => $request->getVal( 'oldid' ),
			'diff' => $request->getVal( 'diff' ),
		);

		$hit['referer_url'] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;
		$hit['referer_title'] = self::getRefererTitleText( $request->getVal('refererpage') );

		
		$dbw = wfGetDB( DB_MASTER );
		
		$dbw->insert(
			'wiretap',
			$hit,
			__METHOD__
		);
		return true;
	}

	public static function updateDatabase( DatabaseUpdater $updater ) {
		global $wgDBprefix;
		$updater->addExtensionTable( $wgDBprefix . 'wiretap', __DIR__ . '/Wiretap.sql' );
		return true;
	}
	
	/**
	 *	See WebRequest::getPathInfo() for ideas/info
	 *  Make better use of: $wgScript, $wgScriptPath, $wgArticlePath;
	 *
	 *  Other recommendations:
	 *    wfSuppressWarnings();
	 *    $a = parse_url( $url );
	 *    wfRestoreWarnings();
	 **/
	public static function getRefererTitleText ( $refererpage=null ) {
		
		// global $egWiretapReferers;
		global $wgScriptPath;
	
		if ( $refererpage )
			return $refererpage;
		else if ( ! isset($_SERVER["HTTP_REFERER"]) )
			return null;
	
		$wikiBaseUrl = WebRequest::detectProtocol() . '://' . $_SERVER['HTTP_HOST'] . $wgScriptPath;
		
		// if referer URL starts 
		if ( strpos($_SERVER["HTTP_REFERER"], $wikiBaseUrl) === 0 ) {
			
			$questPos = strpos( $_SERVER['HTTP_REFERER'], '?' );
			$hashPos = strpos( $_SERVER['HTTP_REFERER'], '#' );
			
			if ($hashPos !== false) {
				$queryStringLength = $hashPos - $questPos;
				$queryString = substr($_SERVER['HTTP_REFERER'], $questPos+1, $queryStringLength);
			} else {
				$queryString = substr($_SERVER['HTTP_REFERER'], $questPos+1);
			}
						
			$query = array();
			parse_str( $queryString, $query );

			return isset($query['title']) ? $query['title'] : false;
		
		}
		else
			return false;
		
	}
	
}
