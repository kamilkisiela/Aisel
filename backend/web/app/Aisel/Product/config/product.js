'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselProduct
 * @description     Module configuration
 */

define(['app'], function(app) {
    app
        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state("products", {
                    url: "/:locale/products/",
                    templateUrl: '/app/Aisel/Kernel/views/collection.html',
                    controller: 'ProductCtrl'
                })
                .state("productEdit", {
                    url: "/:locale/product/edit/:id/",
                    templateUrl: '/app/Aisel/Product/views/edit.html',
                    controller: 'ProductDetailsCtrl'
                })
                .state("productNew", {
                    url: "/:locale/product/new/",
                    templateUrl: '/app/Aisel/Product/views/edit.html',
                    controller: 'ProductDetailsCtrl'
                })
                .state("productNode", {
                    url: "/:locale/product/node/:lang/",
                    templateUrl: '/app/Aisel/Kernel/views/node.html',
                    controller: 'ProductNodeCtrl'
                })
                .state("productNodeEdit", {
                    url: "/:locale/product/node/edit/:lang/:id/",
                    templateUrl: '/app/Aisel/Product/views/edit-node.html',
                    controller: 'ProductNodeDetailsCtrl'
                })
        }])
        .run(['$rootScope', 'Environment', function($rootScope, Environment) {
            $rootScope.topMenu.push({
                "ordering": 200,
                "title": 'Products',
                "children": {
                    "products": {
                        "ordering": 100,
                        "slug": '/products/',
                        "title": 'Products'
                    },
                    "productNode": {
                        "ordering": 200,
                        "slug": '/product/node/' + Environment.currentLocale() + '/',
                        "title": 'Nodes'
                    }
                }
            });
        }]);
});
