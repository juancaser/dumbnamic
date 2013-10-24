<?php
if(!defined('ABSPATH')) exit(/* Silence is golden*/);
/**
 * Dumb-Namic
 * 
 * Static-dynamic(ish) web framework
 * 
 * @version b0.1
 * @author The Juan Who Code <caserjan@gmail.com>
 * @package core
 */

/**
 * registry.php
 *
 * Manages and Stores global variable and instances
 *
 * @version b0.1
 * @package core
 * @subpackage system
 */
class Registry{
     
     /**
      * Instance
      */
     private static $instance;
     
     /**
      * Private variable storage
      */
     private $storage = array();
     
     /**
      * Set to private to prevent public instantiation
      */
     private function __construct(){ /* Do nothing, since this is a static class */ }
     
     /**
      * Return class instance
      *
      * @return object
      */
     public static function getInstance(){
		
          if(!isset(self::$instance) && !self::$instance instanceof Registry) self::$instance = new Registry;
		
		return self::$instance;     
     }
     
     /**
      * Set variables
      *
      * @param string $name Variable name
      * @param mixed $value Variable value
      */
     public function __set($name,$value){
          
          $this->storage[$name] = $value;
     }
     
     /**
      * Return variable
      *
      * @param string $name Variable name
      * @return mixed Variable value, else return NULL
      */
     public function __get($name){
          if(array_key_exists($name,$this->storage)){
               return $this->storage[$name];
          }else{
               return NULL;
          }
     }
}

/**
 * Registry global functions
 *
 * @since b0.1
 */

/**
 * Register variable
 *
 * @param string $name Variable name
 * @param mixed $value Variable value
 */
function __setVar($name,$value = ''){
     Registry::getInstance()->$name = $value;
}

/**
 * Get variable
 *
 * @param string $name Variable name
 * @return mixed Return value, else return NULL
 */
function __getVar($name){
     return Registry::getInstance()->$name;
}
?>