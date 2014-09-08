<?php

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'Wiretap',
	'author'=> array(
		'[https://www.mediawiki.org/wiki/User:Jamesmontalvo3 James Montalvo]'
	),
	'descriptionmsg' => 'wiretap-desc',
	'version' => 0.1,
	'url' => 'https://www.mediawiki.org/wiki/Extension:Wiretap',
);

$wgExtensionMessagesFiles['Wiretap'] = __DIR__ . '/Wiretap.i18n.php';
$wgExtensionMessagesFiles['WiretapAlias'] = __DIR__ . '/Wiretap.alias.php';

$wgAutoloadClasses['Wiretap'] = __DIR__ . '/Wiretap.body.php'; // autoload body class
$wgAutoloadClasses['SpecialWiretap'] = __DIR__ . '/SpecialWiretap.php'; // autoload special page class

$wgSpecialPages['Wiretap'] = 'SpecialWiretap'; // register special page

// $wgHooks['ParserAfterTidy'][] = 'Wiretap::updateTable';
// $wgHooks['BeforePageDisplay'][] = 'Wiretap::updateTable';
$wgHooks['BeforeInitialize'][] = 'Wiretap::updateTable';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'Wiretap::updateDatabase';



$wiretapResourceTemplate = array(
	'localBasePath' => __DIR__ . '/modules',
	'remoteExtPath' => 'Wiretap/modules',
);

$wgResourceModules += array(

	// 'ext.wiretap.base' => $watchAnalyticsResourceTemplate + array(
	// 	'styles' => 'base/ext.wiretap.base.css',
	// ),

	'ext.wiretap.charts' => $wiretapResourceTemplate + array(
		'styles' => 'charts/ext.wiretap.charts.css',
		'scripts' => array(
			'charts/Chart.js',
			'charts/ext.wiretap.charts.js',
		),
		// 'messages' => array(
		// 	'watchanalytics-pause-visualization',
		// 	'watchanalytics-unpause-visualization',
		// ),
		// 'dependencies' => array(
		// 	'base',
		// ),

	),

);
