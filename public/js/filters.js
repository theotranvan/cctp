//une fois que le dom est chargé
window.onload = function () {
    
    var lot = document.querySelector("#produit_search_nom_lot");
    var newProd = null;

    lot.addEventListener("change", function(){
        
        var form1 = this.closest("form");

        var data1 = this.name + "=" + this.value;
        console.log(data1);
        fetch(form1.action, {
            method: form1.getAttribute("method"),
            body: data1,
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
            var content1 = document.createElement("html");
            content1.innerHTML = html;
            //ciblage du nouveau sélect, et modification du contenu en fonction de la réponse
            var nouveauSelect1 = content1.querySelector("#produit_search_nom_produit");
            document.querySelector("#produit_search_nom_produit").replaceWith(nouveauSelect1);
            newProd = document.querySelector("#produit_search_nom_produit");
            newProd.addEventListener("change", function(){
                console.log("ok");
        
                var form = this.closest("form");
        
                var data = this.name + "=" + this.value;
                console.log(data);
                fetch(form.action, {
                    method: form.getAttribute("method"),
                    body: data,
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded, charset:utf-8"
                    }
                })
                //traitement de la reponse
                .then(function (response1) {
                    
                    return response1.text();
                })
                //récupération de la réponse en html
                .then(function (html1) {
                    //traitement de la réponse en html
                    var content = document.createElement("html");
                    console.log(content);
                    content.innerHTML = html1;
                    
                    //ciblage du nouveau sélect, et modification du contenu en fonction de la réponse
                    var nouveauSelect = content.querySelector("#produit_search_types");
                    document.querySelector("#produit_search_types").replaceWith(nouveauSelect);
                    console.log(nouveauSelect);
                });
            });
            
        });
        
    });
    
    
};