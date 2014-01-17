<?php

use Wid\Data\NativeObject;

class NativeObjectTest extends PHPUnit_Framework_TestCase
{
	public $obj;

	public function setUp()
	{
		parent::setUp();

		/** A mocked row from a database pull... */
		$this->obj = new UserObject(array(
			'id' => '1',
			'username' => 'dwidmer',
			'active' => '0',
			'joined_at' => '2014-01-15 12:00:00',
			'roles' => 'super,admin,user'
		));
	}

	public function testAutomaticConversion()
	{
		$converted = $this->obj->toArray();

		$this->assertSame(1, $converted['id']);
		$this->assertSame('dwidmer', $converted['username']);
		$this->assertFalse($converted['active']);
		$this->assertInstanceOf('\\DateTime', $converted['joined_at']);
		$this->assertSame(array('super', 'admin', 'user'), $converted['roles']);
	}

	public function testHas()
	{
		$this->assertTrue($this->obj->has('id'));
	}

	public function testGet()
	{
		$this->assertSame(1, $this->obj->get('id'));
	}

	public function testGetDefault()
	{
		$this->assertNull($this->obj->get('fail'));
	}

	public function testRemove()
	{
		$active = $this->obj->remove('active');

		$this->assertFalse($this->obj->has('active'));
		$this->assertFalse($active);
	}

	public function testToArray()
	{
		$data = $this->obj->toArray();
		$this->assertInternalType('array', $data);
	}

	public function testGetRawData()
	{
		$raw = $this->obj->getRawData();
		$this->assertSame('1', $raw['id']);
	}

	/**
	 * Use toJSON in your code, this 1 test will test them both though...
	 */
	public function testToJSON()
	{
		$expected = '{"id":1,"username":"dwidmer","active":false,"joined_at":{"date":"2014-01-15 12:00:00","timezone_type":3,"timezone":"America\/New_York"},"roles":["super","admin","user"]}';

		$this->assertSame($expected, $this->obj->jsonSerialize());
	}

	public function testPropertyMap()
	{
		$this->obj->setPropertyMap(array('id' => 'Float'));
		$this->assertSame(array('id' => 'Float'), $this->obj->getPropertyMap());
	}

	public function testArrayAccess()
	{
		$this->assertFalse(isset($this->obj['nope']));
		$this->obj['nope'] = true;
		$this->assertTrue($this->obj['nope']);
		unset($this->obj['nope']);
		$this->assertFalse(isset($this->obj['nope']));
	}

	public function testMagicObject()
	{
		$this->assertFalse(isset($this->obj->nope));
		$this->obj->nope = true;
		$this->assertTrue($this->obj->nope);
		unset($this->obj->nope);
		$this->assertFalse(isset($this->obj->nope));
	}

}

class UserObject extends NativeObject
{
	protected function init()
	{
		$this->setPropertyMap(array(
			'id' => 'Integer',
			'active' => 'Boolean',
			'joined_at' => 'DateTime',
			'roles' => function($arg){
				return explode(',', $arg);
			}
		));

		parent::init();
	}
}