<?php

/*
 * The MIT License
 *
 * Copyright 2015 tonirilix.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require __DIR__ . '/vendor/autoload.php';

use NestedJsonToCsv\Csvcreator;

$dataJson = '{"name":"javascript","repo":{"type":"git","url":"XD"},"collection":[{"key":"comment", "value": 55}, {"key":"comment", "value": 44}, {"key":"comment", "value": 77}]}';
$dataJson = '{
	"name": "This is a name",
	"nested": {
		"type": "This is a type",
		"location": "Earth",
		"geo": {
			"latitude": "1234567890",
			"longitude": "0987654321"
		},
		"primitivesCollection": [123, 456, 789]
	}
}';
//$dataJson = json_decode($dataJson);
//$dataJson = json_encode($dataJson);
$data = ['name' => 'scala', 'repo' => ['type'=>'git', 'url'=>'XD'], "collectionPrimitives"=>[1234,2134,55]];
$params = ['fields'=>'name, nick'];

$csvWriter = new Csvcreator();
$csvWriter->setJsonData($dataJson);
//$csvWriter->setArrayData($data);
$flat = $csvWriter->getFlatData();
print_r($flat);
$csvWriter->writeCsv();