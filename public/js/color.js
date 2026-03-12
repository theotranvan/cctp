window.onload = function () {
    var activer = document.querySelectorAll("[type=checkbox]");
    for(var bouton of activer){
        bouton.addEventListener("click", function (){
            var xmlhttp = new XMLHttpRequest;

            xmlhttp.open("get", `/admin/cctp/activer/${this.dataset.id}`);
            xmlhttp.send();
        })
    }
}
