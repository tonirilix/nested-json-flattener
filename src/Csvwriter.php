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

/**
 * Description of Cvswriter
 *
 * @author tonirilix
 */
class Csvwriter {

    private $_columns;
    private $_rows;
    private $_params;
    private $_data;
    private $_cb;

    public function __construct($data, $params, $cb) {
        $this->_columns = [];
        $this->_rows = [];
        $this->_params = $params;
        $this->_data = $data;
        $this->_cb = $cb;


        if (is_callable($this->_params)) {
            $this->_cb = $this->_params;
            $this->_params = null;
        }

        if (!is_array($this->_data)) {
            $this->_data = [$this->_data];
        }

        foreach ($this->_data as $k => $d) {
            $this->_rows[] = $this->flatten($d, $k, $this->_columns, $this->_params);
        }

        $fields = $this->_params['fields'];
        $this->_columns = isset($fields) ? explode(",", $fields) : $this->_columns;
    }

    public function flatten($data, $key, $columns, $params, $path = [], $row = null) {
        $maxDepth = $params['maxDepth'];
        if ($params['maxDepth'] >= 0 && count($path) > ($maxDepth + 1)) {
            return $row;
        }

        if (is_array($data) && count($data)) {
            $this->flattenArray($data, $columns, $params, $path, $row);
        } else if (is_object($data)) {
            $this->flattenObject($data, $columns, $params, $path, $row);
        } else {
            $path[] = $key;
            $this->addField($data, $columns, $params, $path, $row);
        }

        return $row;
    }

    private function flattenArray($data, $columns, $params, $path, $row) {
        
//        if (params.arrayDelimiter && data.length > 0 && typeof data[0] !== 'object' && !(data[0] instanceof Array)) {
//        flatten(data.join(params.arrayDelimiter), columns, params, path, row);
//    } else {
//        var i;
//        for (i = 0; i < data.length; i++) {
//            flatten(data[i], columns, params, path.concat(i), row);
//        }
//    }
        $i = 0;
        for ($i = 0; $i < count($data); $i++) {            
        }
    }

    private function flattenObject($data, $columns, $params, $path, $row) {
        
    }

    private function addField($data, &$columns, $params, $path, &$row) {
        $field = implode(".", $path);
        $row[$field] = $data;
        if (!isset($columns['field'])) {
            $columns[] = $field;
        }
    }

}
