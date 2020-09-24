<?php

namespace Saaze\Collections;

use Symfony\Component\Finder\Finder;

class CollectionManager
{
    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @return array
     */
    public function getCollections()
    {
        if (empty($this->collections)) {
            return $this->loadCollections();
        }

        return $this->collections;
    }

    /**
     * @param string $slug
     * @return \Saaze\Collections\Collection|null
     */
    public function getCollection($slug)
    {
        $this->getCollections();

        if (empty($this->collections[$slug])) {
            return null;
        }

        return $this->collections[$slug];
    }

    /**
     * @return array
     */
    protected function loadCollections()
    {
        $paths = (new Finder())->in(SAAZE_CONTENT_PATH)->files()->name('*.yml')->depth(0);

        foreach ($paths as $file) {
            $this->loadCollection($file->getPathname());
        }

        return $this->collections;
    }

    /**
     * @param string $filePath
     * @return \Saaze\Collections\Collection|null
     */
    protected function loadCollection($filePath)
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $collection = new Collection($filePath);

        $this->collections[$collection->slug()] = $collection;

        return $collection;
    }
}