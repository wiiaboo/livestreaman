<?php

//web directory root
if( !defined( 'DOCUMENT_ROOT' ) ) 	define( 'DOCUMENT_ROOT', '/' );

//paths
if( !defined( 'STAT_XML_URL' ) ) 	define( 'STAT_XML_URL', 'http://ls.fsbn.eu/stats/' );
if( !defined( 'STAT_XML_XPATH' ) ) 	define( 'STAT_XML_XPATH', 'server/application/live/stream' );

//files
if( !defined( 'STREAM_XML' ) ) 		define( 'STREAM_XML', 'streams.xml' );