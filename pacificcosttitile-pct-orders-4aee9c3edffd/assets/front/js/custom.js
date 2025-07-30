$( document ).ready(function() {
	/* Admin sidebar */
    $('#sidebarCollapse').on('click', function () {
       $('#sidebar').toggleClass('active');
    });
    
    /* Admin sidebar */
});

function formToggle(ID)
{
   var element = document.getElementById(ID);
   if(element.style.display === "none"){
      element.style.display = "block";
   }else{
      element.style.display = "none";
   }
}