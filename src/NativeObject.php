<?php

namespace Wid\Data;

use Wid\Data\Cast;

class NativeObject implements \ArrayAccess
{
	public $propertyMap = array();

	protected $rawData = array();
	protected $data = array();

	/**
	 * @param array $data
	 */
	public function __construct(array $data = array())
	{
		$this->init();

		if ( ! empty($data))
		{
			$this->set($data);
		}
	}

	/**
	 * Any internal initialization to take place.
	 */
	protected function init(){}

	/**
	 * @param  string  $name
	 * @return boolean
	 */
	public function has($name)
	{
		return \array_key_exists($name, $this->data);
	}

	/**
	 * Same as __get() only allowing for a default value if the property isn't found
	 *
	 * @see __get()
	 *
	 * @param  string $name
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function get($name, $default = null)
	{
		if ( ! $this->has($name))
		{
			return $default;
		}

		return $this->data[$name];
	}

	/**
	 * @param  mixed $name   The name of the property or key => value pairs
	 * @param  mixed $value  If setting a single property, the value of said property
	 * @return DataObject
	 */
	public function set($name, $value = null)
	{
		if ( ! \is_array($name))
		{
			$name = array($name => $value);
		}

		$mapping = $this->getPropertyMap();
		foreach ($name as $key => $value)
		{
			$this->rawData[$key] = $value;

			if (\array_key_exists($key, $mapping))
			{
				$value = $this->convert($value, $mapping[$key]);
			}

			$this->data[$key] = $value;
		}

		return $this;
	}

	/**
	 * @param  string $name
	 * @return mixed  The value of the property before it is removed
	 */
	public function remove($name)
	{
		$value = $this->get($name);
		unset($this->data[$name]);

		return $value;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * Gets the raw (unconverted) data.
	 *
	 * @return array
	 */
	public function getRawData()
	{
		return $this->rawData;
	}

	/**
	 * Converts the data to json.
	 *
	 * @return string
	 */
	public function toJSON()
	{
		return json_encode($this->data);
	}

	/**
	 * @see toJSON()
	 */
	public function jsonSerialize()
	{
		return $this->toJSON();
	}

	/**
	 * Gets the property map for the object
	 *
	 * @return array
	 */
	public function getPropertyMap()
	{
		return $this->propertyMap;
	}

	/**
	 * Set the property map for the object
	 *
	 * @param  array $map  A key / value pair for property mapping
	 * @return DataObject
	 */
	public function setPropertyMap($map)
	{
		$this->propertyMap = $map;
		return $this;
	}

	private function convert($value, $func)
	{
		if (is_string($func))
		{
			$func = "to".$func;
			return Cast::$func($value);
		}

		return Cast::toCallback($value, $func);
	}

/* ////////////////////////// ArrayAccess /////////////////////////////////// */

	/**
	 * @see has()
	 */
	public function offsetExists($name)
	{
		return $this->has($name);
	}

	/**
	 * @see get()
	 */
	public function offsetGet($name)
	{
		return $this->get($name);
	}

	/**
	 * @see set()
	 */
	public function offsetSet($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * @see remove()
	 */
	public function offsetUnset($name)
	{
		$this->remove($name);
	}

/* ////////////////////////// Object Magic ////////////////////////////////// */

	/**
	 * @see has()
	 */
	public function __isset($name)
	{
		return $this->has($name);
	}

	/**
	 * @see get()
	 */
	public function __get($name)
	{
		return $this->get($name);
	}

	/**
	 * @see set()
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * @see remove()
	 */
	public function __unset($name)
	{
		$this->remove($name);
	}

}
