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

/**
 *
 * @author tonirilix
 */
interface IFlattener {

    /**
     * A simple constructor
     */
    public function __construct();

    /**
     * Sets a json passed as string
     * @param string $json
     */
    public function setJsonData($json = '{}');

    /**
     * Sets a simple array
     * @param array $array
     */
    public function setArrayData(array $array = []);

    /**
     * TODO: Sets options that are going to be used as configuration. WIP
     * @param array $options
     */
    public function setOptions();
}
