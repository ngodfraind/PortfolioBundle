(function($) {
    "use strict";

    $(function() {
        var evaluatorsFormName    = "icap_portfolio_evaluators_form";
        var evaluatorsForm        = $("#evaluators_form");
        var userSelect2Field      = $("#icap_portfolio_evaluators_form_search_evaluator");
        var addingEvaluatorButton = $('.form-collection-add-evaluator');

        ZenstruckFormHelper.initSelect2Helper()

        $('.form-collection').on('click', '.form-collection-element a.remove', function(e) {
            e.preventDefault();
            $(this).parents('.form-collection-element').remove();
        });

        // form collection prototype creation
        addingEvaluatorButton.on('click', function(event) {
            addingButtonClick(event, $(this), userSelect2Field);
        });

        var addingButtonClick = function (event, element, select2Field) {
            event.preventDefault();

            var $container = element.siblings('div[data-prototype]').first();
            var count = $('.form-collection-element', $container).length;
            var prototype = $container.data('prototype');

            // set count, used as id in DOM
            prototype = prototype.replace(/__name__/g, count);
            // set label
            prototype = prototype.replace(/__value__/g, select2Field.select2('data').text);

            // create dom element
            var $newWidget = $(prototype.trim());
            // set user id
            $('input', $newWidget).val(select2Field.select2('data').id);

            $container.children('.form-collection').removeClass('hide').append($newWidget);

            select2Field.select2('data', null);
            element.attr('disabled', 'disabled');
        };

        userSelect2Field.on("change", function(event) {
            select2FieldChange(event, addingEvaluatorButton);
        });

        var select2FieldChange = function (event, button) {
            //console.log("change " + JSON.stringify({val: event.val, added: event.added, removed: event.removed}));
            button.removeAttr('disabled');
        };

        function parseFormValue(formValue)
        {
            var parsedFormValue = {};
            $.each(formValue, function(index, element) {
                var parsedName = element.name;
                var parsedName = parsedName.substring(evaluatorsFormName.length + 1, parsedName.length - 1);
                if ('_token' != parsedName) {
                    parsedFormValue[parsedName] = element.value;
                }
            });

            return parsedFormValue;
        }
    });
})(jQuery);
