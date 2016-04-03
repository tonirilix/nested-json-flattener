<?php

/*
 * The MIT License
 *
 * Copyright 2016 tonirilix.
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

use Exception;
use NestedJsonFlattener\Utils\Csvwriter;
use NestedJsonFlattener\Utils\Dataconverter;
use Peekmo\JsonPath\JsonStore;

/**
 * Description of FlattenerBase
 *
 * @author tonirilix
 */
abstract class FlattenerBase implements IFlattener {

    /**
     * Stores the data converted to object wether was passed as object or json string
     * @var object 
     */
    private $data;

    /**
     * TODO: This is going to be the configuration. WIP
     * @var array 
     */
    private $options;
    private $dataConverter;
    private $csvWriter;

    public function __construct($options = []) {
        $this->data = [];
        $this->options = $options;
        $this->dataConverter = new Dataconverter();
        $this->csvWriter = new Csvwriter();
    }

    /**
     * Sets a simple array
     * @param array $array
     */
    public function setArrayData(array $array = []) {

        $data = $array;

        $selectedNode = $this->getDataPath($data);

        $this->data = $this->dataConverter->arrayToObject($selectedNode);
    }

    /**
     * Sets a json passed as string
     * @param string $json
     */
    public function setJsonData($json = '{}') {

        $data = json_decode($json, true);
        $selectedNode = $this->getDataPath($data);
        $this->data = $this->dataConverter->arrayToObject($selectedNode);
    }

    /**
     * TODO: Sets options that are going to be used as configuration. WIP
     * @param array $options
     */
    public function setOptions() {

        throw new Exception('Please, set options in constructor. This is method is not yet implemented');
        //$this->_options = $options;
    }

    private function getDataPath($data) {
        $selectedNode = $data;

        if ($this->validateDataPath()) {
            $store = new JsonStore($data);
            $path = $this->options['path'];
            // Returns an array with all categories from books which have an isbn attribute
            $selectedNode = $store->get($path);
        }
        return $selectedNode;
    }

    protected function getData() {
        return $this->data;
    }

    public function getOptions() {
        return $this->options;
    }

    /**
     * 
     * @return Csvwriter
     */
    protected function getCsvWriter() {
        return $this->csvWriter;
    }

    protected function setData($data) {
        $this->data = $data;
    }

    /**
     * OPTION METHODS     
     */

    /**
     * Validates whether a path was already set
     * @return type
     */
    protected function validateDataPath() {
        $optionExists = !empty($this->options) && isset($this->options['path']);
        return $optionExists;
    }
    
    /**
     * Validates whether maxDepth was reached
     * @param array $path
     * @return boolean
     */
    protected function validateMaxDepth($path) {
        $optionExists = !empty($this->options) && isset($this->options['maxDepth']);
        $useMaxDepth = false;
        if ($optionExists) {
            $maxDepth = $this->options['maxDepth'];
            $pathLength = count($path);
            
            if ($maxDepth >= 0 && $pathLength > ($maxDepth +1)) {
                $useMaxDepth = true;
            }
        }
        return $useMaxDepth;
    }

}
