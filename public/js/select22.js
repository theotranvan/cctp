$(function(){
    $('.select-prod').select2()
    .on('change', function(e){
        var label = $('.select-prod').select2('data');
        console.log(label[0].id);

        //récupération du contenu du formulaire
        var form = this.closest("form");

        var data = "produit_search[produit]=" + label[0].id;

        //envoi en ajax
        fetch(form.action, {
            //configuration des options d'envoi
            method: form.getAttribute("method"),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded, charset:utf-8"
            }
        })
        //traitement de la reponse
        .then(function (response) {
            return response.text();
        })
        //récupération de la réponse en html
        .then(function (html) {
            //traitement de la réponse en html
            var content = document.createElement("html");
            content.innerHTML = html;
            //ciblage du nouveau sélect, et modification du contenu en fonction de la réponse
            var nouveauSelect = content.querySelector("#produit_search_types");
            document.querySelector("#produit_search_types").replaceWith(nouveauSelect);
        });
    });
    
});