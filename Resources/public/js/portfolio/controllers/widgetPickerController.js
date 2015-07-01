portfolioApp
    .controller('widgetPickerController', ["$scope", "$modalInstance", "portfolioWidgets", "selectedPortfolioWidget",
    function ($scope, $modalInstance, portfolioWidgets, selectedPortfolioWidget) {
        $scope.portfolioWidgets = portfolioWidgets;
        $scope.portfolioWidgets.map(function(portfolioWidget) {
            portfolioWidget.isCollapsed = true;
        });
        $scope.selectedPortfolioWidget = selectedPortfolioWidget || $scope.portfolioWidgets[0];

        $scope.ok = function () {
            $modalInstance.close($scope.selectedPortfolioWidget);
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    }]);