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

    public function testWriteCsvAllowsOmittingFileName(): void
    {
        $writer = new Csvwriter();
        $data = [
            ['person.name' => 'Jane Doe'],
        ];

        $originalCwd = getcwd();
        $tempDir = sys_get_temp_dir() . '/csvwriter_' . uniqid('', true);
        self::assertTrue(mkdir($tempDir), 'Failed to create temporary directory');

        chdir($tempDir);

        try {
            $writer->writeCsv('', $data);
            $generatedFiles = glob('file_*.csv');

            self::assertIsArray($generatedFiles);
            self::assertNotEmpty($generatedFiles, 'Expected Csvwriter to generate a default file name');
        } finally {
            chdir($originalCwd);
            array_map('unlink', glob($tempDir . '/*'));
            rmdir($tempDir);
        }
    }
}
