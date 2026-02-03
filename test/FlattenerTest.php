<?php
declare(strict_types=1);

use NestedJsonFlattener\Flattener\Flattener;
use PHPUnit\Framework\TestCase;

final class FlattenerTest extends TestCase
{
    public function testGetFlatDataWithNestedArray(): void
    {
        $flattener = new Flattener();
        $flattener->setArrayData([
            'name' => 'PHP',
            'repo' => ['type' => 'git', 'url' => 'https://example.com'],
            'collectionPrimitives' => [1, 2, 3],
            'collectionMixed' => [
                ['key' => 'comment', 'value' => 55],
                ['key' => 'comment', 'value' => 44],
            ],
        ]);

        $flatData = $flattener->getFlatData();

        self::assertCount(1, $flatData);
        self::assertSame([
            'name' => 'PHP',
            'repo.type' => 'git',
            'repo.url' => 'https://example.com',
            'collectionPrimitives' => '1,2,3',
            'collectionMixed.0.key' => 'comment',
            'collectionMixed.0.value' => 55,
            'collectionMixed.1.key' => 'comment',
            'collectionMixed.1.value' => 44,
        ], $flatData[0]);
    }

    public function testJsonPathOptionLimitsDataSet(): void
    {
        $payload = [
            'items' => [
                [
                    'id' => 10,
                    'meta' => [
                        'category' => 'books',
                        'details' => ['lang' => 'en'],
                    ],
                    'tags' => ['a', 'b'],
                ],
                [
                    'id' => 11,
                    'meta' => ['category' => 'games'],
                    'tags' => ['solo'],
                ],
            ],
            'ignored' => 'value',
        ];

        $flattener = new Flattener(['path' => '$.items[0]']);
        $flattener->setJsonData(json_encode($payload));

        $flatData = $flattener->getFlatData();

        self::assertCount(1, $flatData);
        self::assertSame(10, $flatData[0]['id']);
        self::assertSame('books', $flatData[0]['meta.category']);
        self::assertSame('en', $flatData[0]['meta.details.lang']);
        self::assertSame('a,b', $flatData[0]['tags']);
        self::assertArrayNotHasKey('ignored', $flatData[0]);
    }

    public function testMaxDepthStopsRecursion(): void
    {
        $data = [
            'person' => [
                'name' => 'Jane Doe',
                'address' => [
                    'street' => 'Main St',
                    'geo' => ['lat' => 1.23, 'lng' => 4.56],
                ],
            ],
        ];

        $flattener = new Flattener(['maxDepth' => 1]);
        $flattener->setArrayData($data);

        $flatData = $flattener->getFlatData();

        self::assertCount(1, $flatData);
        self::assertSame('Jane Doe', $flatData[0]['person.name']);
        self::assertArrayNotHasKey('person.address.street', $flatData[0]);
        self::assertArrayNotHasKey('person.address.geo.lat', $flatData[0]);
    }
}
