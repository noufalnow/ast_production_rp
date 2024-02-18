(function($) {
	"use strict";


// ______________ SWITCHER-toggle ______________//

$('.layout-setting').on("click", function(e) {
	if (!(document.querySelector('body').classList.contains('dark-mode'))) {
		$('body').addClass('dark-mode');
		$('body').removeClass('light-mode');
		

		localStorage.setItem('astdarkMode', true);
		localStorage.removeItem('astlightMode');

	} else {
		$('body').removeClass('dark-mode');
		$('body').addClass('light-mode');

		localStorage.setItem('astlightMode', true);
		localStorage.removeItem('astdarkMode');
	}
	

// localStorageBackup();
// checkOptions();
});
   


  
})(jQuery);

$(function () {
	
	/***************** Horizontal Hover HAs Class START *********************/
	let bodyhorizontalHover = $('body').hasClass('horizontal-hover');
	if (bodyhorizontalHover) {
		if(window.innerWidth>=992){
			let li = document.querySelectorAll('.side-menu li')
			li.forEach((e, i) => {
				e.classList.remove('is-expanded')
			})
			var animationSpeed = 300;
			// first level
			var parent = $("[data-bs-toggle='sub-slide']").parents('ul');
			var ul = parent.find('ul:visible').slideUp(animationSpeed);
			ul.removeClass('open');
			var parent1 = $("[data-bs-toggle='sub-slide2']").parents('ul');
			var ul1 = parent1.find('ul:visible').slideUp(animationSpeed);
			ul1.removeClass('open');
		}
		$('body').addClass('horizontal-hover');
		$('body').addClass('horizontal');
		// $('#slide-left').addClass('d-none');
		// $('#slide-right').addClass('d-none');
		document.querySelector('.horizontal .side-menu')?.classList.add('flex-wrap');
		// $('#slide-left').addClass('d-none');
		// $('#slide-right').addClass('d-none');
		document.querySelector('.horizontal .side-menu')?.classList.add('flex-nowrap');
		$(".main-content").addClass("hor-content");
		$(".main-content").removeClass("app-content");
		$(".main-container").addClass("container");
		$(".main-container").removeClass("container-fluid");
		$(".app-header").addClass("hor-header");
		$(".app-header").removeClass("app-header");
		$(".app-sidebar").addClass("horizontal-main")
		$(".main-sidemenu").addClass("container")
		$('body').removeClass('sidebar-mini');
		$('body').removeClass('sidenav-toggled');
		$('body').removeClass('default-menu');
		$('body').removeClass('icontext-menu');
		$('body').removeClass('icon-overlay');
		$('body').removeClass('closed-leftmenu');
		$('body').removeClass('hover-submenu');
		$('body').removeClass('hover-submenu1');
		// checkHoriMenu();
		// responsive();
	}
	
	/***************** Horizontal Hover HAs Class END *********************/


	/***************** CLOSEDMENU HAs Class *********************/
	let bodyclosed = $('body').hasClass('closed-leftmenu');
	  if (bodyclosed) {
		  $('body').addClass('closed-leftmenu');
		  $('body').addClass('sidenav-toggled');
	  if(document.querySelector('body').classList.contains('login-img') !== true){
		hovermenu();
	  }
	  }
	/***************** CLOSEDMENU HAs Class *********************/
  
	/***************** ICONTEXT MENU HAs Class *********************/
	  let bodyicontext = $('body').hasClass('icontext-menu');
	  if (bodyicontext) {
		  $('body').addClass('icontext-menu');
		  $('body').addClass('sidenav-toggled');
		if(document.querySelector('body').classList.contains('login-img') !== true){
			icontext();
		}
	  }
	  /***************** ICONTEXT MENU HAs Class *********************/
  
	  /***************** ICONOVERLAY MENU HAs Class *********************/
	  let bodyiconoverlay = $('body').hasClass('icon-overlay');
	  if (bodyiconoverlay) {
		  $('body').addClass('icon-overlay');
		  $('body').addClass('sidenav-toggled');
	  if(document.querySelector('body').classList.contains('login-img') !== true){
		hovermenu();
	  }
	  }
	  /***************** ICONOVERLAY MENU HAs Class *********************/
  
	  /***************** HOVER-SUBMENU HAs Class *********************/
	  let bodyhover = $('body').hasClass('hover-submenu');
	  if (bodyhover) {
		  $('body').addClass('hover-submenu');
		  $('body').addClass('sidenav-toggled');
	  if(document.querySelector('body').classList.contains('login-img') !== true){
		hovermenu();
	  }
	  }
	  /***************** HOVER-SUBMENU HAs Class *********************/
  
	  /***************** HOVER-SUBMENU HAs Class *********************/
	  let bodyhover1 = $('body').hasClass('hover-submenu1');
	  if (bodyhover1) {
		  $('body').addClass('hover-submenu1');
		  $('body').addClass('sidenav-toggled');
	  if(document.querySelector('body').classList.contains('login-img') !== true){
		hovermenu();
	  }
	  }
	  /***************** HOVER-SUBMENU HAs Class *********************/
});


// checkOptions()
	
	//Light-mode & Dark-mode
	if (!localStorage.getItem('astlightMode') && !localStorage.getItem('astdarkMode')) {
		/***************** Light THEME *********************/
		$('body').addClass('light-mode');
		/***************** Light THEME *********************/

		/***************** DARK THEME *********************/
		$('body').addClass('dark-mode');
		$('body').removeClass('light-mode');
		/***************** Dark THEME *********************/
	}

	