'use strict';

portfolioApp
    .factory("widgetsManager", ["$http", "widgetsConfig", "widgetFactory", function($http, widgetsConfig, widgetFactory){
        return {
            widgets: [],
            emptyWidgets: [],
            forms:   [],
            init: function(portfolioId, widgets) {
                angular.forEach(widgets, function(rawWidget) {
                    var widget = widgetFactory.getWidget(portfolioId, rawWidget.type);
                    this.widgets.push(new widget(rawWidget).setNewMode(false));
                }, this);
            },
            edit: function(widget) {
                widget.copy = angular.copy(widget);
                if (!widget.isEditing()) {
                    widget.setEditMode(true);
                    this.loadForm(widget);
                }
            },
            loadForm: function(widget) {
                if (this.forms[widget.getType()]) {
                    widget.setFormView(this.forms[widget.getType()]);
                    return true;
                }
                var self = this;

                return $http.get(widget.getFormUrl()).success(function(formViewData) {
                    widget.setFormView(formViewData.form);
                    self.forms[widget.getType()] = formViewData.form;
                });
            },
            cancelEditing: function(widget, rollback) {
                if (rollback) {
                    angular.copy(widget.copy, widget);
                }
                widget.setEditMode(false);

                if (widget.isNew()) {
                    this.widgets.remove(widget);
                }
            },
            save: function(widget) {
                if (null == widget.row) {
                    widget.row = 0;
                }
                if (null == widget.col) {
                    widget.col = 0;
                }

                widget.setUpdatingMode(true);
                delete widget.copy;

                widget.deleteChildren();

                var self = this;
                var success = function() {
                    widget.setNewMode(false);
                    self.cancelEditing(widget);
                    widget.setUpdatingMode(false);
                };
                var failed = function(error) {
                    console.error('Error occured while saving widget');
                    console.log(error);
                }

                if (widget.isNew()) {
                    delete widget.id;
                }

                return widget.isNew() ? widget.$save(success, failed) : widget.$update(success, failed);
            },
            create: function(portfolioId, type, column) {
                var isTypeUnique = widgetsConfig.config[type].isUnique;
                if (isTypeUnique && 0 < this.findWidgetsByType(type).length) {
                    this.edit(this.findWidgetsByType(type)[0]);
                }
                else {
                    this.createEmptyWidget(portfolioId, type, isTypeUnique, column);
                }
            },
            findWidgetsByType: function(type) {
                var widgets = [];

                for (var index = 0; index < this.widgets.length; index++) {
                    if (type == this.widgets[index].type) {
                        widgets.push(this.widgets[index]);
                    }
                }

                return widgets;
            },
            createEmptyWidget: function(portfolioId, type, istypeUnique, column) {
                var newWidget;
                var widget = widgetFactory.getWidget(portfolioId, type);
                if (this.emptyWidgets[type]) {
                    newWidget = new widget(angular.copy(this.emptyWidgets[type]));
                    newWidget.id = new Date().getTime();
                    this.edit(newWidget);
                }
                else {
                    newWidget = widget.create();
                    var self = this;
                    newWidget.$promise.then(function() {
                        self.emptyWidgets[type] = angular.copy(newWidget);
                        newWidget.column = column;
                        self.edit(newWidget);
                    });
                }

                newWidget.column  = column;
                newWidget.isEditing = true;

                this.widgets.push(newWidget);
            },
            isDeletable: function(widget) {
                return widgetsConfig.isDeletable(widget.getType());
            },
            delete: function(widget) {
                if (this.isDeletable(widget)) {
                    widget.isDeleting = true;
                    var self = this;
                    var success = function() {
                        self.widgets.remove(widget);
                    };
                    var failed = function(error) {
                        console.error('Error occured while deleting widget');
                        console.log(error);
                    }
                    widget.$delete(success, failed).then(function() {
                        delete widget.isDeleting;
                    });
                }
            }
        };
    }]);