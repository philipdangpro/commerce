'use strict';

(function(){
    //cibler la croix du cookies disclaimer
    let cookiesDisclaimerButton = $('.close-cookies-disclaimer');

    // tester l'existence du bouton
    if(cookiesDisclaimerButton){
        // console.log('cookie disclaimer existe');
        //écouteur d'evt
        cookiesDisclaimerButton.on('click', onClickCloseCookiesDisclaimer)
    }

    //clic sur la croix du cookies-disclaimer
    function onClickCloseCookiesDisclaimer(e) {
        // alert('onClickCloseCookiesDisclaimer');
        //requête AJAX
        $.ajax({
            dataType: 'json',
            url: '/fr/ajax/cookies-disclaimer',
            type: 'POST',
            data: {'disclaimerValue' : false},
            success: onSuccessCloseCookiesDisclaimer
        })
    }

    //si la requête a réussi, par convention, on dit que la réponse de l'ajax s'appelle response
    function onSuccessCloseCookiesDisclaimer(response){
        console.log('dfsdf');
        console.log(response);
    }
})();