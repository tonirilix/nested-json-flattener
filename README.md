# NestedJsonFlattener
A php package to flatten nested json objects and nested arrays. It also allows you to create csv files from the flattened data.

## Features
1. The package allows you to select a specific node of the json object or array and flat it. The selected node can be flattened whether is a object or collection.

2. It takes in count the full path where a value is stored in a nested json object and uses it as header name. Let's use the example below.

```
{
	"name": "This is a name",
	"nested": {
		"type": "This is a type",
		"location": "Earth",
		"geo": {
			"latitude": "1234567890",
			"longitude": "0987654321"
		},
		"primitivesCollection":[123, 456, 789]
	}	
}
```
If we'd like to flat that json object and put it into a csv file, the result would be as follows:

| name             | nested.type      | nested.location | nested.geo.latitude | nested.geo.longitude | nested.primitivesCollection | 
|------------------|------------------|-----------------|---------------------|----------------------|-----------------------------| 
| This is a name | This is a type | Earth           | 1234567890          | 0987654321           | 123, 456, 789               | 


## Credits
It's based on [csvwriter](https://www.npmjs.com/package/csvwriter) npm package implementation.

## How to use it
**If you need to flat a nested json string**

```
use NestedJsonFlattener\Csvcreator;
$dataJson = '{
	"name": "This is a name",
	"nested": {
		"type": "This is a type",
		"location": "Earth",
		"geo": {
			"latitude": "1234567890",
			"longitude": "0987654321"
		},
		"primitivesCollection":[123, 456, 789]
	}	
}';

$csvWriter = new Csvcreator();
$csvWriter->setJsonData($dataJson);
$flat = $csvWriter->getFlatData();
print_r($flat);
```


**If you need to flat a nested array**

```
use NestedJsonFlattener\Csvcreator;
$data = [
	'name' => 'This is a name', 
	'nested' => [
		'type' => 'This is a type',
		'location' => 'Earth',
		'geo' => [
			'latitude'=> '1234567890',
			'longitude'=> '0987654321'
		],
		'primitivesCollection'=> [123, 456, 789]
	]
];

$csvWriter = new Csvcreator();
$csvWriter->setArrayData($data);
$flat = $csvWriter->getFlatData();
print_r($flat);
```
**If you need to select a specific path to be flattened**

Read [JsonPath](http://goessner.net/articles/JsonPath/) documentation from Stefan Goessner to learn how to create paths.

```
use NestedJsonFlattener\Csvcreator;
$data = [
	'name' => 'This is a name', 
	'nested' => [
		'type' => 'This is a type',
		'location' => 'Earth',
		'geo' => [
			'latitude'=> '1234567890',
			'longitude'=> '0987654321'
		],
		'primitivesCollection'=> [123, 456, 789]
	]
];
// This is a path based on JsonPath implementation
$options = ['path'=>'$.nested'];

$csvWriter = new Csvcreator($options);
$csvWriter->setArrayData($data);
$flat = $csvWriter->getFlatData();
print_r($flat);
```

**If you need to write a csv file**

```
use NestedJsonFlattener\Csvcreator;
$data = [
	'name' => 'This is a name', 
	'nested' => [
		'type' => 'This is a type',
		'location' => 'Earth',
		'geo' => [
			'latitude'=> '1234567890',
			'longitude'=> '0987654321'
		],
		'primitivesCollection'=> [123, 456, 789]
	]
];
$csvWriter = new Csvcreator();
$csvWriter->setArrayData($data);
$csvWriter->writeCsv();

```
## TODO
1. The package still needs to get configurations from params. 
2. Some of the params in mind are: whether take primitives arrays as one element or not (taken as one element by default)
3. Some methods need to be splitted into different files.
4. Add a way to create a configuration to tell the class how to handle internal collections. 
