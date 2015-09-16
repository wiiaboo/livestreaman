<?php

require_once dirname( __FILE__ ) . '/config/config.php';

require_once DOCUMENT_ROOT . 'library/XmlHelper.php';
require_once DOCUMENT_ROOT . 'library/Utils.php';

$streamers_list = array();

//create xml file if it doesn't exist, else load registered streamers
if( ! file_exists( STREAM_XML ) ){
	$xmlHelper = new XmlHelper();
	$xml = $xmlHelper->createXML( STREAM_XML );
}
else{
	$xml = new XmlHelper( STREAM_XML );
}

//load stats xml and extract current streamers live 
$stats = new XmlHelper ( STAT_XML_URL );
$stats->xmlXpath( STAT_XML_XPATH );

//check if there are new streams and save to xml if so
if( $new_streamers = Utils::getNewStreamers( $xml->getXML(), $stats->getXML() ) ){
	foreach( $new_streamers as $new_streamer ){
		$xml->saveToXML( STREAM_XML, $xml->arrayToXML( $new_streamer ) );
	}
}

//finnaly, get (updated) streamers list
$streamers_list = Utils::getStreamersList( $xml->getXML(), $stats->getXML() );

if( isset( $_GET['stream'] ) ){
	$cur_stream = substr($_GET['stream'], 1);
}
//elseif( $live = Utils::getLiveStreamer( $xml->getXML() ) ){
//	$cur_stream = $live['name'];
//}
else{ $cur_stream = 'mega'; }

//load template
include DOCUMENT_ROOT . 'template.php';
