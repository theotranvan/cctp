/*window.onload = function () {
  //var table = document.getElementById("table");

  var ok = document.querySelectorAll('.qte');
  var nameElem = document.querySelectorAll('.nameP');


  for (i = 0; i < ok.length; i++) {
    ok[i].classList.add('green');
    ok[i].onclick = () => {
      ok[i].classList.remove('green');
      console.log("ok");


    };
  }



};*/
function getId(monId){
  idProd = monId;
  document.getElementById(monId).className += "green";
  console.log(idProd);
}
/*function changerClasse()
{
var ligne = document.getElementById("clickLigne") ;
ligne.querySelector(".nameP").className += "green";
}
window.onload = function()
{
document.getElementById("clickLigne").addEventListener("click" , changerClasse);
};*/




