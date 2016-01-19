# Csvwriter
A php package to create cvs files from nested json objects, json files and nested arrays.

The package still needs to get configurations from params. It also needs to separate methods into different files.

It's based on [csvwriter](https://www.npmjs.com/package/csvwriter) npm package implementation.

## How to use it
**If you need to flat a nested json string**

```
use Csvwriter\Csvwriter;
$dataJson = '{"name":"javascript","repo":{"type":"git","url":"XD"},"collection":[{"key":"comment", "value": 55}, {"key":"comment", "value": 44}, {"key":"comment", "value": 77}]}';

$csvWriter = new Csvwriter();
$csvWriter->setJsonData($dataJson);
$flat = $csvWriter->getFlatData();
print_r($flat);
```


**If you need to flat a nested array**

```
use Csvwriter\Csvwriter;
$data = ['name' => 'scala', 'repo' => ['type'=>'git', 'url'=>'XD']];

$csvWriter = new Csvwriter();
$csvWriter->setArrayData($data);
$flat = $csvWriter->getFlatData();
print_r($flat);
```
**If you need to write a csv file**

```
use Csvwriter\Csvwriter;
$data = ['name' => 'typescript', 'repo' => ['type'=>'git', 'url'=>'XD']];

$csvWriter = new Csvwriter();
$csvWriter->writeCsv();

```
