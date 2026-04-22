(function ($) {
    $(document).ready(function () {

    const menuTrigger = document.querySelector('.mobile-menu-trigger');
    const mainMenu = document.querySelector('#main-menu');
  
    menuTrigger.addEventListener('click', function() {
        mainMenu.style.display = (mainMenu.style.display === 'flex') ? 'none' : 'flex';
        this.classList.toggle('active')
    });
    const menuItems = document.querySelectorAll('#main-menu li.menu-item-has-children > a');
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (window.innerWidth <= 991) {
                e.preventDefault(); // prevent page jump
                const parentLi = this.parentElement;
                parentLi.classList.toggle('active');
            }
        });
    });

    const menuHeadings = document.querySelectorAll('#main-menu li h4');

    menuHeadings.forEach(heading => {
        heading.addEventListener('click', function() {
            const parentLi = this.parentElement;

            // Toggle active class to show/hide submenu
            parentLi.classList.toggle('active');
        });
    });

	document.querySelectorAll('ul li:first-child a').forEach(link => {
		if (link.textContent.trim() === "See All") {
			link.style.paddingBottom = "12px";
		}
	});
		
    const searchTrigger = document.querySelector(".search-trigger");
    const searchOverlay = document.getElementById("fullscreen-search");
    const searchClose = document.querySelector(".search-close");

    if (searchTrigger && searchOverlay) {
        searchTrigger.addEventListener("click", function(e) {
            e.preventDefault();
            searchOverlay.classList.add("active");
        });
    }

    if (searchClose) {
        searchClose.addEventListener("click", () => {
            searchOverlay.classList.remove("active");
        });
    }

    
    // Mega menu image swap on product-family hover
    const megaMenuImg = document.getElementById('mega-menu-dynamic-img');
    const productFamilyTerms = document.querySelectorAll('.product-family-term');

    if (megaMenuImg && productFamilyTerms.length) {
        const defaultImage = megaMenuImg.getAttribute('data-default');

        productFamilyTerms.forEach(term => {
            term.addEventListener('mouseenter', function() {
                const newImage = this.getAttribute('data-image');
                if (newImage) {
                    megaMenuImg.src = newImage;
                }
            });

            term.addEventListener('mouseleave', function() {
                megaMenuImg.src = defaultImage;
            });
        });
    }



    });
})(jQuery);
