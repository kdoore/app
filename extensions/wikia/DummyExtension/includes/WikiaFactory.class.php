<?php

/**
 * Wikia Factory class
 * @author ADi
 */
abstract class WikiaFactory {
	protected static $constructors = array();
	protected static $setters = array();

	/**
	 * add class constructor
	 * @param string $className class name
	 * @param array $params optional params to be set as defaults
	 * @param string $methodName factory method to call, leave default for "new" operator to be called
	 */
	public static function addClassConstructor($className, Array $params = array(), $methodName = '__construct') {
		if(!isset(self::$constructors[$className])) {
			self::$constructors[$className] = array();
		}
		elseif(isset(self::$constructors[$className]['INSTANCE'])) {
			throw new WikiaException("WikiaFactory: Class $className has already defined instance by setInstance() call, unable to add constructor");
		}

		self::$constructors[$className][$methodName] = $params;
	}

	/**
	 * add predefined instance of given class (useful for mocks or singletons)
	 * @param string $className class name
	 * @param mixed $instance instance
	 */
	public static function setInstance($className, $instance) {
		if(!isset(self::$constructors[$className])) {
			self::$constructors[$className] = array();
		}
		elseif((count(self::$constructors[$className]) > 0) && !isset(self::$constructors[$className]['INSTANCE'])) {
			throw new WikiaException("WikiaFactory: Class $className has already defined constructor by addClassConstructor() call, unable to set instance");
		}

		self::$constructors[$className]['INSTANCE'] = $instance;
	}

	/**
	 * set class setter methods to be called while building an object
	 * @param string $className class name
	 * @param array $setters array of setter methods ( setter name => value )
	 */
	public static function setClassSetters($className, Array $setters) {
		self::$setters[$className] = $setters;
	}

	/**
	 * build object
	 * @param string $className class name
	 * @param array $params array of parameters for constructor or factory method ( param name => value )
	 * @param string $constructorMethod constructor or factory method to call
	 * @return object
	 */
	public static function build($className, Array $params = array(), $constructorMethod = '__construct') {
		if(isset(self::$constructors[$className]['INSTANCE'])) {
			return self::$constructors[$className]['INSTANCE'];
		}

		if(($constructorMethod == '__construct') || isset(self::$constructors[$className][$constructorMethod])) {
			$object = null;
			$buildParams = $params;
			if (isset(self::$constructors[$className][$constructorMethod])) {
				$buildParams = array_merge(self::$constructors[$className][$constructorMethod], $params);
			}

			if($constructorMethod == '__construct') {
				$reflectionObject = new ReflectionClass($className);
				$object = $reflectionObject->newInstanceArgs($buildParams);
			}
			else {
				$object = call_user_func_array(array($className, $constructorMethod), $buildParams);
			}

			if(isset(self::$setters[$className])) {
				foreach(self::$setters[$className] as $setterName => $value) {
					call_user_func(array($object, $setterName), $value);
				}
			}
			return $object;
		}
		else {
			throw new WikiaException("WikiaFactory: Unknown constructor ($constructorMethod) for class: $className");
		}
	}

	/**
	 * reset factory configuration
	 * @param string $className class name (optional)
	 */
	public static function reset($className = null) {
		if(!empty($className)) {
			unset(self::$constructors[$className]);
			unset(self::$setters[$className]);
		}
		else {
			self::$constructors = array();
			self::$setters = array();
		}
	}
}

/**
 * WikiaFactory class alias
 * @author ADi
 *
 */
abstract class WF extends WikiaFactory { }

