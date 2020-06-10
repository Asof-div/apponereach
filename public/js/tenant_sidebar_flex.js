jQuery(document).ready(function() {
    //cache DOM elements
	var mainContent = $('#content_layout'),
		contentWrapper = $('#content'),
		//header = $('.cd-main-header'),
		sidebar = $('.sidebar-nav-wrapper'),
		sidebarTrigger = $('.tf-nav-trigger');
		//topNavigation = $('.cd-top-nav'),

    
    //mobile only - open sidebar when user clicks the hamburger menu
	sidebarTrigger.on('click', function(event) {
		event.preventDefault();
		$([sidebar, sidebarTrigger, contentWrapper]).toggleClass('nav-is-visible');
	});

    //click on item and show submenu
	$('.has-children > a').on('click', function(event){
		var mq = checkMQ(),
			selectedItem = $(this);
		if( mq == 'mobile' || mq == 'tablet' ) {
			event.preventDefault();
			if( selectedItem.parent('li').hasClass('selected')) {
				selectedItem.parent('li').removeClass('selected');
			} else {
				sidebar.find('.has-children.selected').removeClass('selected');
				selectedItem.parent('li').addClass('selected');
			}
		}
	});

    //on desktop - differentiate between a user trying to hover over a dropdown item vs trying to navigate into a submenu's contents
	sidebar.children('ul').menuAim({
        activate: function(row) {
        	$(row).addClass('hover');
        },
        deactivate: function(row) {
        	$(row).removeClass('hover');
        },
        exitMenu: function() {
        	sidebar.find('.hover').removeClass('hover');
        	return true;
        },
        submenuSelector: ".has-children",
    });

    function checkMQ() {
		//check if mobile or desktop device
		return window.getComputedStyle(document.querySelector('.tf-main-content'), '::before').getPropertyValue('content').replace(/'/g, "").replace(/"/g, "");
	}
});