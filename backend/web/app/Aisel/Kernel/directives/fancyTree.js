'use strict';

/**
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @name            AiselKernel
 * @description     Display tree collections with fancyTree
 */

define(['app'], function(app) {
    angular.module('app')
        .directive('aiselFancyTree', ['$timeout', function($timeout) {
            return {
                restrict: 'EA',
                scope: {
                    lang: "@lang"
                },
                templateUrl: '/app/Aisel/Kernel/views/fancyTree.html'
            };
        }]);
});
