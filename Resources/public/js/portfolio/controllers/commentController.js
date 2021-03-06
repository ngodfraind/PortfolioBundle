'use strict';

portfolioApp
    .controller("commentController", ["$scope", "portfolioManager", "commentsManager", "$timeout",
                              function($scope, portfolioManager, commentsManager, $timeout) {
        $scope.message = "";
        $scope.tinyMceConfig = {
            forced_root_block : "",
            force_br_newlines : true,
            force_p_newlines : false
        };

        $scope.create = function() {
            if (this.message) {
                var comment = commentsManager.create(portfolioManager.portfolioId, {
                    'message' : this.message
                })
                this.message = '';
            }
        };

        $scope.updateCountViewComments = function () {
            $scope.displayComment= !$scope.displayComment;

            if ($scope.displayComment) {
                if (0 < portfolioManager.portfolio.unreadComments) {
                    portfolioManager.updateViewCommentsDate();
                }
            }
        }
    }]);