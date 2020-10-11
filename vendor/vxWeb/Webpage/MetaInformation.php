<?php
/*
 * This file is part of the vxPHP/vxWeb framework
 *
 * (c) Gregor Kofler <info@gregorkofler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vxWeb\Webpage;

class MetaInformation implements \Countable, \IteratorAggregate
{
	private $metaData = [];

	public function __construct() {
	}

	public static function createFromDb($pageId): MetaInformation
    {
        return new self();
	}

	/**
	 * get a metadata value by its name
	 * returns $default, if $key is not found
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $name, $default = null)
    {
		if (!array_key_exists($name, $this->metaData)) {
			return $default;
		}

		return $this->metaData[$name];
	}

    /**
     * set or add a metadata value
     *
     * @param string $name
     * @param mixed $value
     * @return MetaInformation
     */
	public function set(string $name, $value): MetaInformation
    {
		$this->metaData[$name] = $value;
		return $this;
	}

	/**
	 * check existence of a metadata value
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function has(string $name): bool
    {
		return array_key_exists($name, $this->metaData);
	}

    /**
     * remove a metadata value
     *
     * @param string $name
     * @return MetaInformation
     */
	public function remove(string $name): MetaInformation
    {
		unset($this->metaData[$name]);
		return $this;
	}

	/**
	 * returns the number of metadata value
	 *
	 * @return int
	 */
	public function count(): int
    {
		return count($this->metaData);
	}

	/**
	 * returns an iterator for metadata values
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator(): \ArrayIterator
    {
		return new \ArrayIterator($this->metaData);
	}
}