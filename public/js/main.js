(function () {
	"use strict";

	var treeviewMenu = $('.app-menu');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

	feather.replace();

	var elem = document.documentElement;

	$(".maximize").click(function(e){
		e.preventDefault();

		$(this).hide()

		$(".minimize").show()

		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} 
		else if (elem.webkitRequestFullscreen) { /* Safari */
			elem.webkitRequestFullscreen();
		} 
		else if (elem.msRequestFullscreen) { /* IE11 */
			elem.msRequestFullscreen();
		}
	})


	$(".minimize").click(function(e){

		$(this).hide()

		$(".maximize").show()

		if (document.exitFullscreen) {
			document.exitFullscreen();
		} 
		else if (document.webkitExitFullscreen) { /* Safari */
			document.webkitExitFullscreen();
		} 
		else if (document.msExitFullscreen) { /* IE11 */
			document.msExitFullscreen();
		}
	})

    
	

})();
