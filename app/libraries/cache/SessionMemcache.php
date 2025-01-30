<?php

class SessionMemcache
{
	private $memcache_obj;

	private $host = 'localhost';

	private $port = 11211;

	function __construct( $config = [])
	{
		$this->memcache_obj = new \Memcached;
		
		/* connect to memcached server */
		$this->memcache_obj->addServer($this->host, $this->port, 1);
	}
	
	function  create( $name, $value , $seconds = 10 )
	{
		$this->memcache_obj->set( $name, $value , $seconds ) or die("Memcached is not installed, try: apt-get install memcached");
	}
	
	function get( $name )
	{	
		return $this->memcache_obj->get( $name ) or die("Memcached is not installed, try: apt-get install memcached");
	}

	function info()
	{		
		return $this->memcache_obj->getStats();
	}
	
	function getkeys() {
		$all_items = [];
	   	$cdump 	= $this->memcache_obj->getExtendedStats('cachedump',1);
	  	
	  	if( isset($cdump[$this->host.':'.$this->port] ) )
	  	{
	  		$keys = array_keys($cdump[$this->host.':'.$this->port]);
	  		
	  		foreach ($keys as $k) {
	  			$all_items[$k] = $this->get($k);
	  		}
	  	}
	  	
	  	return $all_items;
	} 
}