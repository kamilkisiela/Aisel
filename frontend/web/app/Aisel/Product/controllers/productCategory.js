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
 * @description     ...
 */

define(['app'], function(app) {
    app.controller('ProductCategoryCtrl', ['$location', '$scope', '$stateParams', 'productCategoryService', 'Environment',
        function($location, $scope, $stateParams, productCategoryService, Environment) {
            $scope.media = Environment.settings.media;
            $scope.pageLimit = 5;
            $scope.paginationPage = 1;

            $scope.pageChanged = function(page) {
                $scope.paginationPage = page;
                productCategoryService.getCategories($scope).success(
                    function(data, status) {
                        $scope.categories = data;
                    }
                );
            };

            // Categories
            productCategoryService.getCategories($scope).success(
                function(data, status) {
                    $scope.categories = data;
                }
            );
        }
    ]);
});
