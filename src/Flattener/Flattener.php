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

namespace NestedJsonFlattener\Flattener;

/**
 * Cvswriter allows you to transform nested json data into a flat csv
 *
 * @author tonirilix
 */
class Flattener extends FlattenerBase {

    /**
     * A simple constructor
     */
    public function __construct($options = []) {
        parent::__construct($options);
    }

    /**
     * Resturns a flatted array
     * @return array
     */
    public function getFlatData() {

        $result = [];

        // Checks wether data is an array or not
        if (!is_array($this->getData())) {
            // If it's not we convert it to array
            $data0 = [$this->getData()];
            $this->setData($data0);
        }

        // Loops the array 
        foreach ($this->getData() as $data) {
            // Flats passed array of data
            $result[] = $this->flatten($data, []);
        }

        // Returns
        return $result;
    }

    /**
     * Writes a csv file with the passed data
     * @param string $name the name of the file. Default: "file_" . rand()
     */
    public function writeCsv($name = '') {
        $fileName = !empty($name) ? $name : "file_" . rand();
        // Setting data
        $dataFlattened = $this->getFlatData();

        $this->getCsvWriter()->writeCsv($fileName, $dataFlattened);
    }

    /**
     * Flats a nested array
     * @param array $data Array with data to be flattened
     * @param array $path Options param, it's used by the recursive method to set the full key name     
     * @return array Flattened array
     */
    private function flatten($data, array $path = array()) {

        if ($this->validateMaxDepth($path)) {            
            return $data;
        }

        // Check if the data is an object        
        if (is_object($data)) {

            $flatObject = $this->flatObject($data, $path);
            return $flatObject;

            // Check if the data is an array
        } elseif (is_array($data)) {

            $flatArray = $this->flatArray($data, $path);
            return $flatArray;
        }

        // If the data isn't an object or an array is a value
        $flatValue = $this->addValue($data, $path);
        return $flatValue;
    }

    private function flatObject($data, array $path = array()) {


        $dataModified = get_object_vars($data);

        $flatArrayHelper = $this->flatArrayHelper($dataModified, $path);
        return $flatArrayHelper;
    }

    private function flatArray($data, array $path = array()) {

        if (count($data) > 0 && !is_object($data[0]) && !is_array($data[0])) {
            $flatPrimitives = $this->flatten(join(",", $data), $path);
            return $flatPrimitives;
        }


        $flatArrayHelper = $this->flatArrayHelper($data, $path);
        return $flatArrayHelper;
    }

    private function flatArrayHelper($data, $path) {
        $result = array();

        foreach ($data as $key => $value) {
            $currentPath = array_merge($path, array($key));
            $flat = $this->flatten($value, $currentPath);
            $result = is_array($flat) ? array_merge($result, $flat) : $result;
        }

        return $result;
    }

    private function addValue($data, array $path = array()) {
        $result = array();

        $pathName = join('.', $path);
        $result[$pathName] = $data;

        return $result;
    }

}
