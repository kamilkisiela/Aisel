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
 * @description     ...
 */

define(['app'], function(app) {
    app.directive('aiselNewPages', ['$compile', 'pageService', function($compile, pageService) {
        return {
            restrict: 'EA',
            link: function($scope, element, attrs) {
                var pageLimit = attrs.limit;

                var params = {
                    limit: pageLimit,
                    order: 'id',
                    orderBy: 'DESC',
                    page: 1
                };
                pageService.getPages(params).success(
                    function(data, status) {
                        $scope.newPages = data;
                        $scope.newPages.limit = pageLimit;
                    }
                );

            },
            templateUrl: '/app/Aisel/Page/views/directives/new-pages.html'
        };
    }]);
});
