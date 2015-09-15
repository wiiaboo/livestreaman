<?php

class XmlHelper{
	private $xml;
	
	function __construct( $xml = '' ){
		if( ! empty( $xml ) ){
			$xml = self::loadXML( $xml );
		}
		$this->xml = $xml;
	}
	
	function getXML(){
		return $this->xml;
	}
	
	function setXML( $xml ){
		$this->xml = $xml;
	}
	
	/**
	*
	* Creates XML document
	*
	* @param    string  $filename The name of the xml file
	* @param    string [optional]  $xmlstr Xml to be added to file on string format
	* @return      SimpleXMLElement
	*
	*/
	function createXML( $filename, $xmlstr = '' ){
		if( empty( $xmlstr ) ){
			$xmlstr = <<<XML
				<?xml version="1.0"?>
				<streams></streams>
XML;
		}
		$this->xml = new SimpleXMLElement( $xmlstr );
		$this->asXML( $filename );
		
		return $this->xml;
	}
	
	/**
	*
	* Saves the XmlHelper xml to a xml document
	*
	* @param    string  $filename The name of the xml file
	* @param    SimpleXMLElement [optional]  $xml Xml to be saved to file on string format
	* @return   
	*
	*/
	function saveToXML( $filename, $xml = null ){
		if( ! $xml ){
			$xml = $this->xml;
		}
		$xml->asXML( $filename );
	}
	
	/**
	*
	* Loads XML document
	*
	* @param    string  $xml The location of the xml file
	* @return
	*
	*/
	private static function loadXML( $xml ){
		return simplexml_load_file( $xml );
	}
	
	/**
	*
	* Creates a xml structure based on an array
	*
	* @param    array  $array The array to base the xml structure off
	* @param    SimpleXMLElement  $node Current xml node
	* @return   SimpleXMLElement
	*
	*/
	function arrayToXML( $array, $node = null ){
		if( $node === null ){ $node = $this->xml; }
			
		if( $array ){
			foreach( $array as $key => $value ){
				if( is_array( $value ) ){
					$$key = $node->addChild( $key );
					
					$this->arrayToXML( $value, $$key );
					continue;
				}
				$node->addChild( $key, $value );
			}
		}
		return $this->xml;
	}
	
	/**
	*
	* XML to array
	*
	* @param    string  $xmlstr String based xml
	* @return   array
	*
	*/
	function toArray( $xmlstr = '' ){
		if( empty( $xmlstr ) ){
			$xmlstr = $this->xml->asXML();
		}
		return json_decode( json_encode( ( array ) simplexml_load_string( $xmlstr ) ), 1);
	}
	
	/**
	*
	* Set xpath to the current xml document
	*
	* @param    string  $xpath Xpath expression
	* @param    SimpleXMLElement [optional]  $xml Xml object
	* @return   array
	*
	*/
	function xmlXpath( $xpath, $xml = null ){
		if( $xml === null ){
			$xml = $this->xml;
		}
		$this->xml = $xml->xpath( $xpath );
	}
}