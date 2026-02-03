<?php
declare(strict_types=1);

use NestedJsonFlattener\Utils\Csvwriter;
use PHPUnit\Framework\TestCase;

final class CsvwriterTest extends TestCase
{
    public function testWriteCsvCreatesNormalizedFile(): void
    {
        $writer = new Csvwriter();
        $data = [
            ['person.name' => 'Jane Doe', 'person.email' => 'jane@example.com'],
            ['person.name' => 'John Smith'],
        ];

        $fileBase = sys_get_temp_dir() . '/flat_' . uniqid('', true);
        $csvPath = $fileBase . '.csv';

        try {
            $writer->writeCsv($fileBase, $data);

            self::assertFileExists($csvPath);

            $rows = [];
            $handle = fopen($csvPath, 'r');
            self::assertNotFalse($handle);

            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                $rows[] = $row;
            }

            fclose($handle);

            self::assertSame([
                ['person.name', 'person.email'],
                ['Jane Doe', 'jane@example.com'],
                ['John Smith', ''],
            ], $rows);
        } finally {
            if (file_exists($csvPath)) {
                unlink($csvPath);
            }
        }
    }
}
