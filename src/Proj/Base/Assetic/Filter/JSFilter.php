<?php

namespace Proj\Base\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Class MyFilter
 * @package LimeThinking\SpringBundle\Assetic\Filter
 */
class JSFilter implements FilterInterface {

    //=====================================================
    //== Verejne metody ===================================
    //=====================================================

    /**
     * @param AssetInterface $asset
     */
    public function filterLoad(AssetInterface $asset) {

    }

    /**
     * @param AssetInterface $asset
     */
    public function filterDump(AssetInterface $asset) {
        $content = $asset->getContent();
        $content = str_replace('/lib/nil/', '/bundles/nil/', $content);
        $asset->setContent($content);
    }

}