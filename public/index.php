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

//finaly, get (updated) streamers list
$streamers_list = Utils::getStreamersList( $xml->getXML(), $stats->getXML() );
$own_ip = false;
$response = "";

if( isset( $_GET['stream'] ) ){
    $info = explode("/", $_GET['stream']);
    $cur_stream = $info[1];
    if (count($info) > 2) {
        if ($info[2] == "nclients") {
            $response=sprintf("%d",$streamers_list[$cur_stream]['nclients']);
        } elseif ($info[2] == "live") {
            if ($streamers_list[$cur_stream]['live']) {
                $response="live";
            } else {
                $response="off";
            }
        }
        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Length: '.strlen($response));
        exit($response);
    }
    if ($cur_stream == "live") {
        $livestreamers = array();
        foreach( $streamers_list as $streamer ){
            if ($streamer["live"]) {
                array_push($livestreamers, $streamer["stream_url"]);
            }
        }
        $response=implode(",", $livestreamers);
        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Length: '.strlen($response));
        exit($response);
    }
    else {
        if ($_SERVER['REMOTE_ADDR'] === $streamers_list[$cur_stream]['streamer_ip']) { $own_ip = true; }
    }
}
elseif ( $live = Utils::getLiveStreamer( $streamers_list ) ) {
    $cur_stream = $live['stream_url'];
    header("Refresh: 1; url=".$cur_stream);
}
else{ $cur_stream = 'mega'; }

//load template
include DOCUMENT_ROOT . 'template.php';
