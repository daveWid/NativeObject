# NativeObject

PHP likes to send data back as strings when pulling from datasources. This
library will help you convert everything into a native php format.

## Example

The easiest way to learn about this library is to see an example. We'll
pretend that we are converting a row from a database.

The property map is an array of _'property name' => 'mapping name'_ pairs. See the 
[Casting](#casting) section for more information on what is available.

``` php
class UserObject extends Wid\Data\NativeObject
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

$databaseRow = array(
 	'id' => '1',
	'username' => 'dwidmer',
 	'active' => '0',
 	'joined_at' => '2014-01-15 12:00:00',
	'roles' => 'super,admin,user'
);

$user = new UserObject($databaseRow);
$user->toArray();

/* //// Output //// */

// 'id' => (int) 1
// 'username' => (string) 'dwidmer'
// 'active' => (boolean) false,
// 'joined_at' => \DateTime
// 'roles' => array('super', 'admin', 'user')

```

It's really that easy!

## Casting

Below is a list of casting types available in the library

 Mapping Name | Transforms to 
--------------|-------------
Boolean       | boolean
Integer       | int
Float         | float
DateTime      | \DateTime
Date          | \DateTime
Time          | \DateTime
Timestamp     | \DateTime

You can also pass in anything that can be called by `call_user_func` as the second
argument in the propery map (see `roles` in the example) and the data can be
casted that way. In our example it is turning a string into an array, exploding
on the commas.

## Property Access

So how do I get at the data?

Well each of the following lines will work.

``` php
$id = $user['id']; // Array Access
$id = $user->id; // Object Notation
$id = $user->get('id'); // Getter
```

`get()` has the added bonus of being able to add a default value as the second
parameter. This is `null` if not explicitly set.

And setting?

You can set with Array Access, Object Notation or a Setter, just like when you
are getting data out. All of the methods below will work.

``` php
$user['active'] = true;
$user->active = true;
$user->set('active', true);
```

## Why?

Getting our data into native php has a lot of advantages, but the one I had in
mind when develping this library is for API development. When we send our data
it should be in native data types (i.e. booleans shouldn't come back as '0').
_P.S. - you can call `toJSON()` on any NativeObject and get the json representation
of it._

## Development

See some things that can be improved or want to help add some features?

* Clone this repo
* Run `composer install`
* Code away (with phpunit tests to back up your changes!)
* Send me a pull request!

----------------

Developed by [Dave Widmer](http://www.davewidmer.net)

