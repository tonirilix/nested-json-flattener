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

namespace NestedJsonFlattener\Utils;

/**
 * Description of Csvwriter
 *
 * @author tonirilix
 */
class Csvwriter {
    
    private $normalizer;


    public function __construct() {
        $this->normalizer = new Normalizer();
    }
    
    /**
     * Writes a csv file with the passed data
     * @param string $name the name of the file. Default: "file_" . rand()
     * @param array The flattened data
     */
    public function writeCsv($name, $dataFlattened) {
        // Setting data        
        $csvFormat = $this->arrayToCsv($dataFlattened);
        $this->writeCsvToFile($csvFormat, $name);
    }

    private function arrayToCsv($data) {

        $dataNormalized = $this->normalizer->normalizeKeys($data);

        $rows[0] = array_keys($dataNormalized[0]);

        foreach ($dataNormalized as $value) {
            //$rows[0] = array_keys($value);
            $rows[] = array_values($value);
        }
        return $rows;
    }

    private function writeCsvToFile($data, $name) {
        $file = fopen($name . '.csv', 'w');
        foreach ($data as $line) {
            fputcsv($file, $line, ',');
        }
        fclose($file);
    }

}
