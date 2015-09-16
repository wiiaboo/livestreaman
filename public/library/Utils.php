<?php

class Utils{
	
	/**
	*
	* Returns the first streamer that is live from the array
	*
	* @param    SimpleXMLElement  $streamer SimpleXMLElement
	* @return   boolean
	*
	*/
	
	static function isLive( $streamer ){
		return $streamer->bw_video > 0;
	}
	
	static function getLiveStreamer( $streamers ){
		foreach( $streamers as $streamer ){
			if( (string) $streamer->name !== '' && self::isLive( $streamer ) ){
				return $streamer;
			}
		}
		return false;
	}
	
	/**
	*
	* Returns the streamer list to be used on the frontpage
	*
	* @param    SimpleXMLElement  $streamers SimpleXMLElement containing streamers from the streams.xml document
	* @param    SimpleXMLElement  $streamers_live SimpleXMLElement containing streamers live from the stats.xml document
	* @return   array
	*
	*/
	static function getStreamersList( $streamers, $streamers_live ){
		$streamers_list = array();
		
		foreach( $streamers as $streamer ){
			$name = (string) $streamer->name;
			$stream_url = (string) $streamer->stream_url;
			$is_live = false;
			
			foreach( $streamers_live as $streamer_live ){
				if( $name == (string) $streamer_live->name ){
					$is_live = self::isLive( $streamer_live );
					break;
				}
			}
			$streamers_list [] = [ 'name' => $name, 'stream_url' => $stream_url, 'live' => $is_live ];
		}
		return $streamers_list;
	}
	
	/**
	*
	* Returns the new streamers that are live, but not present on streams.xml
	*
	* @param    array  $streamers The array containing streamers from the streams.xml document
	* @param    array  $streamers_live The array containing streamers live from the stats.xml document
	* @return   array
	*
	*/
	static function getNewStreamers( $streamers, $live_streamers ){
		$new_streamers = array();
		
		foreach( $live_streamers as $live_streamer ){
			$new_entry = true;
			
			foreach( $streamers as $streamer ){
                if( (string) $live_streamer->name === '' &&
                    (string) $streamer->stream_url == (string) $live_streamer->stream_url ||
                    (string) $streamer->name == (string) $live_streamer->name ){
					$new_entry = false;
					break;
				}
			}
			
			if( $new_entry === true ){
                $new_streamers[] = [
                    'streamer' => [
                        'name' => (string) $live_streamer->name,
                        'stream_url' => '/' . (string) $live_streamer->name
                    ]
                ];
			}
		}
		return $new_streamers;
	}
}
