'use strict';
(
    function () {
        //update ajax de products by category id
        let selectCategory = $('[name="select-category"]');
        selectCategory.on('change', onSelectCategory);

        function onSelectCategory(e) {
            let value = $(this).val();
            $.ajax({
                dataType: 'json',
                data: {'selectValue' : value},
                type: 'post',
                url:'/fr/ajax/search',
                success: onSuccessSelectCategory
            });
        }

        function onSuccessSelectCategory(response){
            let searchResults = $('.search-results');
            searchResults.empty();

            let products = response.products;

            let html = '';

            products.map(function(product){
                html += `
                <div class="row my-2">
                    <col-sm-2>
                        <div class="col-sm-2">
                            <img src="/img/product/${product.image}" class="img-fluid" alt="">
                        </div>
                    </col-sm-2>
                    <col-sm-2>
                        <div class="col-sm-8">${product.translations.fr.name}</div>
                    </col-sm-2>
                    <col-sm-2>
                        <div class="col-sm-2">
                            ${product.price}
                        </div>
                    </col-sm-2>
                </div>
                `
                ;
            });
            searchResults.append(html)
        }

    }
)();