<?php

namespace Saaze\Tests\Collections;

use Saaze\Interfaces\CollectionManagerInterface;
use Saaze\Interfaces\EntryManagerInterface;
use Saaze\Tests\TestCase;

class EntryManagerTest extends TestCase
{
    /**
     * @var \Saaze\Interfaces\CollectionInterface
     */
    protected $collection;

    /**
     * @var EntryManagerInterface
     */
    protected $entryManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->entryManager = $this->getEntryManager('posts');
    }

    /**
     * @param string $collectionSlug
     * @return EntryManagerInterface
     */
    private function getEntryManager($collectionSlug)
    {
        $collectionManager = $this->container->get(CollectionManagerInterface::class);
        $collection        = $collectionManager->getCollection($collectionSlug);
        $entryManager      = $this->container->get(EntryManagerInterface::class);
        $entryManager->setCollection($collection);

        return $entryManager;
    }

    public function testGetEntries()
    {
        $entries = $this->entryManager->getEntries();

        $this->assertIsArray($entries);
        $this->assertCount(20, $entries);
    }

    public function testGetEntry()
    {
        $entry = $this->entryManager->getEntry('example-post-1');
        $this->assertInstanceOf(\Saaze\Interfaces\EntryInterface::class, $entry);

        $entry = $this->entryManager->getEntry('nonexistent');
        $this->assertNull($entry);

        $entryManager = $this->getEntryManager('pages');
        $this->assertNotNull($entryManager->getEntry('index'));
        $this->assertNotNull($entryManager->getEntry('level1'));
        $this->assertNotNull($entryManager->getEntry('level1/page'));
        $this->assertNotNull($entryManager->getEntry('level1/level2/page'));

        $entryManager = $this->getEntryManager('docs');
        $this->assertNotNull($entryManager->getEntry('index'));
        $this->assertNotNull($entryManager->getEntry('level1'));
        $this->assertNotNull($entryManager->getEntry('level1/page'));
        $this->assertNotNull($entryManager->getEntry('level1/level2/page'));
    }

    public function testGetEntriesSortedByDate()
    {
        $entries   = $this->entryManager->getEntries();
        $sortField = 'date';

        $this->assertTrue(strtotime($entries[0]->data()[$sortField]) > strtotime($entries[1]->data()[$sortField]));
        $this->assertTrue(strtotime($entries[1]->data()[$sortField]) > strtotime($entries[2]->data()[$sortField]));
    }

    public function testGetEntriesSortedMissingSortField()
    {
        $entryManager = $this->getEntryManager('docs');
        $entries      = $entryManager->getEntries();

        $this->assertEquals('Order Missing', $entries[0]->data()['title']);
    }

    public function testGetEntriesForTemplate()
    {
        $entries = $this->entryManager->getEntriesForTemplate(1, 10);

        $this->assertEquals(1, $entries['currentPage']);
        $this->assertEquals(1, $entries['prevPage']);
        $this->assertEquals(2, $entries['nextPage']);
        $this->assertEquals('', $entries['prevUrl']);
        $this->assertEquals('/blog/page/2', $entries['nextUrl']);
        $this->assertEquals(10, $entries['perPage']);
        $this->assertEquals(20, $entries['totalEntries']);
        $this->assertEquals(2, $entries['totalPages']);
        $this->assertIsArray($entries['entries']);
        $this->assertEquals(10, count($entries['entries']));

        $entries = $this->entryManager->getEntriesForTemplate(3, 5);

        $this->assertEquals(3, $entries['currentPage']);
        $this->assertEquals(2, $entries['prevPage']);
        $this->assertEquals(4, $entries['nextPage']);
        $this->assertEquals('/blog/page/2', $entries['prevUrl']);
        $this->assertEquals('/blog/page/4', $entries['nextUrl']);
        $this->assertEquals(5, $entries['perPage']);
        $this->assertEquals(20, $entries['totalEntries']);
        $this->assertEquals(4, $entries['totalPages']);
        $this->assertIsArray($entries['entries']);
        $this->assertEquals(5, count($entries['entries']));
    }

    public function testGetEntryForTemplate()
    {
        $entries = $this->entryManager->getEntries();
        $entry = $this->entryManager->getEntryForTemplate($entries[0]);

        $this->assertArrayHasKey('title', $entry);
        $this->assertArrayHasKey('date', $entry);
        $this->assertArrayHasKey('content_raw', $entry);
        $this->assertArrayHasKey('content', $entry);
        $this->assertArrayHasKey('url', $entry);
        $this->assertArrayHasKey('excerpt', $entry);
        $this->assertArrayHasKey('title', $entry);
    }
}
