'use strict';

(
    function () {
        //switch to selected currency
        let selectCurrency = $('.currencies span.btn');
        selectCurrency.on('click', onSelectCurrency);

        function onSelectCurrency(e) {
            let value = $(this).data("currency");
            selectCurrency.removeClass('active');
            $(this).addClass('active');
            console.log("currency = " + value);

            $.ajax({
                dataType: 'json',
                data: {'selectValue' : value},
                type: 'post',
                url:'/fr/ajax/currency',
                success: onSuccessSelectCurrency
            });
        }

        function onSuccessSelectCurrency(response){
            console.log(response);

            window.location.href = window.location.href;

        }
    }
)();