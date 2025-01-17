'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselPage
 * @description     Module configuration
 */

define(['app'], function(app) {
    app
        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state("pages", {
                    url: "/:locale/pages/",
                    templateUrl: '/app/Aisel/Kernel/views/collection.html',
                    controller: 'PageCtrl'
                })
                .state("pageEdit", {
                    url: "/:locale/page/edit/:id/",
                    templateUrl: '/app/Aisel/Page/views/edit.html',
                    controller: 'PageDetailsCtrl'
                })
                .state("pageNew", {
                    url: "/:locale/page/new/",
                    templateUrl: '/app/Aisel/Page/views/edit.html',
                    controller: 'PageDetailsCtrl'
                })
                .state("pageNode", {
                    url: "/:locale/page/node/:lang/",
                    templateUrl: '/app/Aisel/Kernel/views/node.html',
                    controller: 'PageNodeCtrl'
                })
                .state("pageNodeEdit", {
                    url: "/:locale/page/node/edit/:lang/:id/",
                    templateUrl: '/app/Aisel/Page/views/edit-node.html',
                    controller: 'PageNodeDetailsCtrl'
                })
        }])

        .run(['$rootScope', 'Environment', function($rootScope, Environment) {
            $rootScope.topMenu.push({
                "ordering": 100,
                "title": 'Pages',
                "children": {
                    "pages": {
                        "ordering": 100,
                        "slug": '/pages/',
                        "title": 'Pages'
                    },
                    "pageNode": {
                        "ordering": 200,
                        "slug": '/page/node/' + Environment.currentLocale() + '/',
                        "title": 'Nodes'
                    }
                }
            });
        }]);
});
