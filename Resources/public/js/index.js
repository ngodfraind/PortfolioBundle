(function() {
    $(function() {
        var locationhash = window.location.hash;
        if (locationhash.substr(0,2) == "#!") {
            $("#portfolio_space_tabs a[href='#" + locationhash.substr(2) + "']").tab("show");
        }

        function initEvents() {
            $('.exchange_link').click(function(event) {
                var scope = angular.element($("#exchange_space_container")).scope();
                scope.$apply(function(){
                    var portfolioId = $(event.delegateTarget).val();
                    if (portfolioId !== scope.selectedPortfolioId) {

                        scope.clickOnPortolio(portfolioId);
                    }
                    $('#portfolio_space_tabs a[href="#exchange_space"]').tab('show');
                });
            });
            $('.delete').confirmModal({
                confirmCallback: deleteConfirmCallback,
                confirmDismiss: false
            });
        }
        initEvents();

        function deleteConfirmCallback(target, modal) {
            $.ajax({
                url: $(target).attr('href'),
                success: function(data, textStatus, jqXHR) {
                    $("#list_content").html(data);
                    initEvents();
                    toastr.success(Translator.trans('portfolio_deleted_ajax_notification', {}, 'icap_portfolio'));
                    modal.modal('hide');
                }
            });
        }

        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-center",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        var modal = window.Claroline.Modal;

        $("a.modal_action, #no_portfolio a").click(function (event) {
            event.preventDefault();
            modal.fromUrl(
                event.delegateTarget.href,
                function(modalElement) {
                    var modalForm = $("form", modalElement);

                    modalElement.on('click', 'button[type="submit"]', function (event) {
                        event.preventDefault();
                        submitForm(modalElement, modalForm);
                    });

                    modalElement.on('keypress', function (event) {
                        if (event.keyCode === 13 && e.target.nodeName !== 'TEXTAREA') {
                            event.preventDefault();
                            submitForm(modalElement, modalForm);
                        }
                    });
                }
            );
        });

        function submitForm(modalElement, form) {
            $.ajax({
                url: form.attr('action'),
                data: form.serializeArray(),
                type: 'POST',
                success: function(data, textStatus, jqXHR) {
                    $("#list_content").html(data);
                    initEvents();
                    modalElement.modal('hide');
                    toastr.success(Translator.trans('portfolio_added_ajax_notification', {}, 'icap_portfolio'));
                }
            });
        }
    });
})();