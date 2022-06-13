function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
   $("#main").hide();
}
  
function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    $("#main").show();
}