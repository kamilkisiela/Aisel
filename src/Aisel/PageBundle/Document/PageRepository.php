<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\PageBundle\Document;

use Aisel\ResourceBundle\Repository\CollectionRepository;
use Aisel\PageBundle\Document\Page;

/**
 * PageRepository
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class PageRepository extends CollectionRepository
{

    /**
     * Get pages based on limit, current pagination and search query
     *
     * @param  array $params
     * @return Page
     */
    public function searchFromRequest($params)
    {
        $this->mapRequest($params);

        $query = $this
            ->getDocumentManager()
            ->createQueryBuilder($this->getDocumentName())
            ->field('status')->equals(true)
            ->field('locale')->equals($this->locale)
            ->field('content')->equals(new \MongoRegex('/.*' . $this->search . '.*/i'));

//        $query->expr()->operator('content', array(
//            '$search' => $this->search,
//        ));

        $collection = $query
            ->limit($this->pageLimit)
            ->skip($this->pageSkip)
            ->sort($this->order, $this->orderBy)
            ->getQuery()
            ->toArray();

//        var_dump(count($collection));
//        exit();

        return $collection;
    }


}
