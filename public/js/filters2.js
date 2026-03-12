window.onload = function () {
    //je cible le champ produit, par l'id de la liste déroulante contenant les produits
    var produit = document.querySelector("#produit_search_produit");
    //écouteur d'évènements "change" sur l'input ciblé plus haut
    produit.addEventListener("change", function () {
        console.log("ok");
        //récupération du contenu du formulaire
        var form = this.closest("form");
        //préparation de la queryString à envoyer en ajax
        var data = this.name + "=" + this.value;
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
};