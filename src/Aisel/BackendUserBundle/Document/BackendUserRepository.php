<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\BackendUserBundle\Document;

use Aisel\ResourceBundle\Repository\CollectionRepository;

/**
 * BackendUserRepository
 *
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class BackendUserRepository extends CollectionRepository
{
    protected $model = 'AiselBackendUserBundle:BackendUser';
}