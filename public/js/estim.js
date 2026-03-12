//une fois que le dom est chargé
window.onload = function () {
    
    
    var catUsage = document.querySelector("#catusage_nom_catusage");

    catUsage.addEventListener("change", function(){
        
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
        .then(function (response) {
            
            return response.text();
        })
        //récupération de la réponse en html
        .then(function (html) {
            
            //traitement de la réponse en html
            var content = document.createElement("html");
            content.innerHTML = html;
            //ciblage du nouveau sélect, et modification du contenu en fonction de la réponse
            var nouveauSelect = content.querySelector("#catusage_usage");
            
            document.querySelector("#catusage_usage").replaceWith(nouveauSelect);
           
            
        });
        
    });
    
    
};