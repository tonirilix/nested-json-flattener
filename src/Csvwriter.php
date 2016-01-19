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

namespace Csvwriter;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

/**
 * Cvswriter allows you to transform nested json data into a flat csv
 *
 * @author tonirilix
 */
class Csvwriter {

    private $_data;
    private $_options;

    public function __construct() {
        $this->_data = [];
        $this->_options = [];
    }

    public function setJsonData($json = '{}') {
        $this->_data = json_decode($json);
    }

    public function setArrayData($array = []) {
        $this->_data = $this->_arrayToObject($array);
    }

    public function setOptions($options = []) {
        $this->_options = $options;
    }

    public function getFlatData() {
        
        $result = [];
        
        if(!is_array($this->_data)){
            $this->_data = [$this->_data];
        }
        
        foreach ($this->_data as $data) {
            // Flats passed array of data
            $result[] = $this->flatten($data, [], $this->_options);
        }               

        // Returns
        return $result;
    }

    public function writeCsv($name = '') {
        $_name = !empty($name) ? $name : "file_" . rand();
        // Setting data
        $_data = $this->getFlatData();        

        $csvFormat = $this->_arrayToCsv($_data);
        $this->_writeCsv($csvFormat, $_name);
    }

    private function _arrayToCsv($data) {

        $dataNormalized = $this->_normalizeKeys($data);

        $rows[0] = array_keys($dataNormalized[0]);

        foreach ($dataNormalized as $value) {
            //$rows[0] = array_keys($value);
            $rows[] = array_values($value);
        }
        return $rows;
    }

    private function _writeCsv($data, $name) {
        $fp = fopen($name . '.csv', 'w');
        foreach ($data as $line) {
            fputcsv($fp, $line, ',');
        }
        fclose($fp);
    }

    private function _normalizeKeys($param) {
        $keys = array();
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($param)) as $key => $val) {
            $keys[$key] = '';
        }

        $data = array();
        foreach ($param as $values) {
            $data[] = array_merge($keys, $values);
        }

        return $data;
    }    

    private function _arrayToObject(array $arr) {
        $flat = array_keys($arr) === range(0, count($arr) - 1);
        $out = $flat ? [] : new \stdClass();

        foreach ($arr as $key => $value) {
            $temp = is_array($value) ? $this->_arrayToObject($value) : $value;

            if ($flat) {
                $out[] = $temp;
            } else {
                $out->{$key} = $temp;
            }
        }

        return $out;
    }

    /**
     * Flats a nested array
     * @param array $data Array with data to be flattened
     * @param array $path Options param, it's used by the recursive method to set the full key name
     * @param array $options Not implemented yet
     * @return array Flattened array
     */
    private function flatten($data, array $path = array(), array $options = array()) {
        $result = array();
//        foreach ($data as $key => $val) {
//            $currentPath = array_merge($path, array($key));
//            if (is_array($val)) {
//                $result = array_merge($result, $this->flatten($val, $currentPath, $options));
//            } else {
//                $pathName = join('.', $currentPath);
//                $result[$pathName] = $val;
//            }
//        }

        if (is_object($data)) {
            $flat = $this->flatObject($data, $path, $options);
            $result = array_merge($result, $flat);
        } elseif (is_array($data)) {
            $flat = $this->flatArray($data, $path, $options);
            $result = array_merge($result, $flat);
        } else {
            $flat = $this->addValue($data, $path, $options);
            $result = array_merge($result, $flat);
        }

        return $result;
    }

    private function flatObject($data, array $path = array(), array $options = array()) {

        $result = array();

        $data = get_object_vars($data);
        foreach ($data as $key => $value) {
            $currentPath = array_merge($path, array($key));
            $flat = $this->flatten($value, $currentPath, $options);
            $result = array_merge($result, $flat);
        }

        return $result;
    }

    private function flatArray($data, array $path = array(), array $options = array()) {
        $result = array();
        /**
         * if (params.arrayDelimiter && data.length > 0 && typeof data[0] !== 'object' && !(data[0] instanceof Array)) {
          flatten(data.join(params.arrayDelimiter), columns, params, path, row);
          } else {
          var i;
          for (i = 0; i < data.length; i++) {
          flatten(data[i], columns, params, path.concat(i), row);
          }
          }
         */
        if (count($data) > 0 && !is_object($data[0]) && !is_array($data[0])) {
            $flat = $this->flatten(join(",", $data), $path, $options);
            $result = array_merge($result, $flat);
        } else {
            foreach ($data as $key => $value) {                
                $currentPath = array_merge($path, array($key));
                $flat = $this->flatten($value, $currentPath, $options);
                $result = array_merge($result, $flat);                               
            }
        }
        
        /**
         * foreach ($data as $key => $value) {
                $currentPath = array_merge($path, array($key));
                $flat = $this->flatten($value, $currentPath, $options);
                $result = array_merge($result, $flat);
            }
         */

        return $result;
    }

    private function addValue($data, array $path = array(), array $options = array()) {
        $result = array();

        $pathName = join('.', $path);
        $result[$pathName] = $data;

        return $result;
    }

}
