<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\CartBundle\Manager;

use LogicException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Aisel\ProductBundle\Document\Product;
use Aisel\FrontendUserBundle\Document\FrontendUser;
use Aisel\CartBundle\Document\Cart;

/**
 * CartManager
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class CartManager
{

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * Constructor
     *
     * @param DocumentManager $DocumentManager
     */
    public function __construct(DocumentManager $DocumentManager)
    {
        $this->dm = $DocumentManager;
    }

    /**
     * Get get cart products for given $userId
     *
     * @param int $productId
     *
     * @return Product $product
     */
    public function loadProductById($productId)
    {
        $product = $this->dm->find('Aisel\ProductBundle\Document\Product', $productId);

        if (!$product) {
            throw new LogicException('Product was not found');
        }

        return $product;
    }

    /**
     * Get get cart products for given $userId
     *
     * @param FrontendUser $user
     *
     * @return Cart $cartItems
     */
    public function getUserCart($user)
    {
        $cartItems = $this
            ->dm
            ->getRepository('Aisel\CartBundle\Document\Cart')
            ->findBy(array('frontenduser' => $user));

        return $cartItems;
    }

    /**
     * Adds product to cart by given $id and $qty
     *
     * @param FrontendUser $user
     * @param int          $productId
     * @param int          $qty
     *
     * @return Cart $cartItem
     *
     * @throws LogicException
     */
    public function addProductToCart($user, $productId, $qty = 1)
    {
        if (!$user) {
            throw new LogicException('User object is missing');
        }

        $product = $this->loadProductById($productId);
        $cartItem = $this
            ->dm
            ->getRepository('Aisel\CartBundle\Document\Cart')
            ->addProduct($user, $product, $qty);

        return $cartItem;
    }

    /**
     * Updates product item inside cart by given $id and $qty
     *
     * @param FrontendUser $user
     * @param int          $productId
     * @param int          $qty
     *
     * @return Cart $cartItem
     *
     * @throws LogicException
     */
    public function updateProductInCart($user, $productId, $qty = null)
    {
        if (!$user) {
            throw new LogicException('User object is missing');
        }
        $product = $this->loadProductById($productId);
        $cartItem = $this
            ->dm
            ->getRepository('Aisel\CartBundle\Document\Cart')
            ->updateProduct($user, $product, $qty);

        return $cartItem;
    }

}
