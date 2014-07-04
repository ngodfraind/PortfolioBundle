'use strict';

angular.module('ui.resourcePicker', [])
    .value('uiResourcePickerConfig', {})
    .directive('uiResourcePicker', ['uiResourcePickerConfig', function (uiResourcePickerConfig) {
        uiResourcePickerConfig = uiResourcePickerConfig || {};

        // Set some default options
        var options = {
            isPickerMultiSelectAllowed: false,
            isPickerOnly:               true,
            isWorkspace:                true,
            appPath:                    window.appPath,
            webPath:                    window.webPath,
            resourceTypes:              window.resourceTypes,
            pickerCallback: function (nodes) {
                console.log(nodes);
                return null;
            }
        };

        return {
            restrict: "A",
            link: function ($scope, element, attrs) {
                if (attrs.uiResourcePicker) {
                    var expression = $scope.$eval(attrs.uiResourcePicker);
                } else {
                    var expression = {};
                }

                angular.extend(options, uiResourcePickerConfig, expression);

                $scope.resourcePickerOpen = function () {
                    Claroline.ResourceManager.initialize(options);
                    Claroline.ResourceManager.picker('open');
                }

                $scope.resourcePickerClose = function () {
                    Claroline.ResourceManager.picker('close');
                }

                element[0].onclick = function(event){
                    event.preventDefault();
                    $scope.resourcePickerOpen();
                };
            }
        };
    }]);