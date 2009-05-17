<?php 
	ob_start();
	define('DOING_AJAX', true);
	require_once('../../../wp-load.php');
	require_once('settings.php');
	require_once('lib/kaltura_helpers.php');
	require_once("lib/kaltura_model.php");
	
	$sessionUser = KalturaHelpers::getSessionUser();
	
	$kshowId = @$_GET["kshowId"];
	$postId = @$_GET["postId"];
	
	// update the kshow
	$kshowUpdate = new KalturaKShow();
	$kshowUpdate->name = "Video comment for post #".$postId;
	$kalturaClient = KalturaHelpers::getKalturaClient(false, "edit:".$kshowId);  
	KalturaModel::updateKshow($kalturaClient, $kshowId, $kshowUpdate);
	
	// add widget
	$widget = new KalturaWidget();
	$widget->kshowId = $kshowId;
	$player = KalturaHelpers::getPlayerByType(get_option('kaltura_comments_player_type'));
	$widget->uiConfId = $player["uiConfId"];
	$result = $kalturaClient->addwidget($sessionUser, $widget);
	$widgetId = @$result["result"]["widget"]["id"];
	$playerSize = "comments";
	
	ob_clean();
	echo '[kaltura-widget wid="'.$widgetId.'" size="'.$playerSize.'" /]'; 
	ob_flush();
?>