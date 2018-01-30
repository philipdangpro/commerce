'use strict';

(
    function () {
        let inputSearch = $(".input-search");

        inputSearch.on('keyup', onKeyUpInputSearch);

        function onKeyUpInputSearch(e) {
            let value = $(this).val();
            console.log(value);

            $.ajax({
                dataType: 'json',
                data: {'selectValue' : value},
                type: 'post',
                url:'/fr/ajax/datalist',
                success: onSuccessKeyUpInputSearch
            });
        }

        function onSuccessKeyUpInputSearch(response){
            console.log(response);
            let datalistSearch = $('#datalist-search');
            datalistSearch.empty();
            let html = '';
            response.map(function(el){
                html+= `<option value="${el.name}">`;
            });
            datalistSearch.append(html);

        }
    }



)();