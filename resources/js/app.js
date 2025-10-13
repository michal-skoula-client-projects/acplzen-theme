document.addEventListener("DOMContentLoaded", () => {
    console.log('loaded')

    /*
     * ------------------
     *   Elements setup
     * ------------------
     */

    /**
     * @type {HTMLElement} The document's body
     */
    const body = document.querySelector("body");

    /**
     * @type {HTMLElement} Wrapper containing all page content other than the header with navigation itself
     */
    const content = document.getElementById("content");
    if (!content)
        throw new Error("[navbar] No element with id of content found! Focus trap will not work.");

    /**
     * @type {HTMLElement} Top bar with the logo, links, call to action etc.
     */
    const navbar = document.getElementById("navbar");
    if (!navbar) throw new Error("[navbar] No element with id of navbar found!");

    /**
     * @type {HTMLElement[]} Clickable links inside of the entire navigation
     */
    const navLinks = document.querySelectorAll("#slideover li");
    if (!navLinks.length)
        throw new Error("[navbar] No navLinks found. Is your configuration correct?");

    /**
     * @type {HTMLElement} Sidebar that opens when you click the hamburger menu
     */
    const slideover = document.getElementById("slideover");
    if (!slideover) throw new Error("[navbar] No element with id of slideover found!");

    /**
     * @type {HTMLElement} Element that goes in-between the navbar and slideover, provides visual separation and click-away to close functionality
     */
    const backdrop = document.getElementById("backdrop");
    if (!backdrop) throw new Error("[navbar] No element with id of backdrop found!");

    /**
     * @type {HTMLElement} Button that opens the slideover; inside of the navbar
     */
    const openBtn = document.getElementById("slideoverOpenBtn");
    if (!openBtn) throw new Error("[navbar] No element with id of slideoverOpenBtn found!");

    /**
     * @type {HTMLElement} Button that closes the slideover; inside of the slideover itself
     */
    const closeBtn = document.getElementById("slideoverCloseBtn");
    if (!closeBtn) throw new Error("[navbar] No element with id of slideoverCloseBtn found!");

    const skipNavigation = document.getElementById("skipNavigation");

    /**
     * Breakpoint from where to start switch to and from mobile navigation properties
     */
    const navBreakpoint = getComputedStyle(document.documentElement)
        .getPropertyValue("--breakpoint-nav")
        .trim();
    const media = window.matchMedia(`(width <= ${navBreakpoint})`);

    // Hide navbar on scroll
    // let lastScrollPos = 0;
    // window.addEventListener("scroll", () => {
    //     let currScrollPos = window.scrollY;
    //
    //     if(currScrollPos > 50) {
    //         lastScrollPos < currScrollPos
    //             ? navbar.classList.add("collapsed")
    //             : navbar.classList.remove("collapsed");
    //     }
    //     else {
    //         navbar.classList.remove("collapsed")
    //     }
    //
    //
    //     lastScrollPos = currScrollPos;
    // });
    skipNavigation.addEventListener("click", closeSlideover);

    /*
     * -------------------
     *   Slideover logic
     * -------------------
     */

    openBtn.addEventListener("click", () => {
        openSlideover();
        updateState();
    });
    closeBtn.addEventListener("click", () => {
        closeSlideover();
        updateState();
    });
    backdrop.addEventListener("click", () => {
        closeSlideover();
        updateState();
    });
    navLinks.forEach((item) => {
        item.addEventListener("click", () => {
            closeSlideover();
            updateState();
        });
    });
    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeSlideover();
            updateState();
        }
    });
    window.addEventListener("resize", updateState);

    // Waits for transition to finish, then focus button to change state
    slideover.addEventListener("transitionend", () => {
        slideover.classList.contains("open") ? closeBtn.focus() : openBtn.focus();
    });

    function openSlideover() {
        slideover.classList.add("open");
        backdrop.classList.add("shown");
        openBtn.setAttribute("aria-expanded", "true");
    }
    function closeSlideover() {
        slideover.classList.remove("open");
        backdrop.classList.remove("shown");
        openBtn.setAttribute("aria-expanded", "false");
    }

    // Update navigation based on breakpoint
    function updateState() {
        const isMobile = media.matches;
        const isOpen = slideover.classList.contains("open");
        setNavStates({ isMobile, isOpen });

        if (isMobile) navbar.classList.remove("collapsed");
    }

    function setNavStates({ isMobile, isOpen }) {
        slideover.toggleAttribute("inert", !(isMobile && isOpen));
        backdrop.toggleAttribute("inert", !(isMobile && isOpen));
        content.toggleAttribute("inert", isMobile && isOpen);
        navbar.toggleAttribute("inert", isMobile && isOpen);

        isMobile && isOpen ? (body.style.overflow = "hidden") : (body.style.overflow = "");
    }

    /*
     * ------------------
     *   Dropdown logic
     * ------------------
     */

    class Dropdown {
        /** @type {HTMLElement} */
        dropdown;

        /** @type {HTMLElement} */
        dButton;

        /** @type {HTMLElement} */
        dList;

        /** @type {NodeListOf<HTMLElement>} */
        links;

        /** @type boolean */
        isMobile;

        constructor(dropdownElem, id = null, isMobile = false) {
            this.dropdown = dropdownElem;
            this.dButton = this.dropdown.querySelector(":scope > a");
            this.dList = this.dropdown.querySelector(":scope > ul");
            this.links = this.dList.querySelectorAll(":scope > li > a");
            this.menuId = `nav-dropdown-menu-${id}` ?? null;
            this.isMobile = isMobile;

            this.modifyDefaultWordpressMarkup();
            this.registerBasicEventListeners();
            this.registerKeyboardInputEventListeners();
            this.setAriaAttributes();
        }

        isOpen() {
            return this.dropdown.classList.contains("open");
        }

        openDropdown(onlyAria = false) {
            if (!onlyAria) {
                closeAllDropdowns();

                this.dList.addEventListener("transitionend", this.setFocusOnFirstLink, {
                    once: true,
                });
                // Fallback if there is no transition set
                this.setFocusOnFirstLink();

                this.dropdown.classList.add("open");
            }

            this.dList.setAttribute("aria-hidden", "false");
            this.dButton.setAttribute("aria-expanded", "true");
        }

        closeDropdown(shouldFocus = true, onlyAria = false) {
            if (!onlyAria) {
                this.dropdown.classList.remove("open");
                this.dropdown.classList.remove("hover");
            }

            if (shouldFocus) {
                this.dButton.focus();
            }

            this.dList.setAttribute("aria-hidden", "true");
            this.dButton.setAttribute("aria-expanded", "false");
        }

        setFocusOnFirstLink = () => {
            setTimeout(() => this.links[0]?.focus(), 10);
        };

        setAriaAttributes() {
            this.dButton.setAttribute("aria-haspopup", "true");
            this.dButton.setAttribute("aria-expanded", "false");

            this.dList.setAttribute("aria-hidden", "true");
            this.dList.setAttribute("role", "menu");

            if (this.menuId) {
                this.dList.setAttribute("id", this.menuId);
                this.dButton.setAttribute("aria-controls", this.menuId);
            }

            this.links.forEach((link) => {
                link.setAttribute("role", "menuitem");
            });
        }

        registerBasicEventListeners() {
            // If any dropdown is toggled open, don't open another one on hover
            this.dropdown.addEventListener("mouseenter", () => {
                for (const d of dropdowns) {
                    if (d.isOpen()) return;
                }

                this.dropdown.classList.add("hover");
                this.openDropdown(true);
            });

            this.dropdown.addEventListener("mouseleave", () => {
                this.dropdown.classList.remove("hover");
                this.closeDropdown(false, true);
            });

            // Close when any link is clicked
            this.links.forEach((link) => {
                link.addEventListener("click", () => {
                    this.closeDropdown();
                });
            });

            // Toggle open when clicked
            this.dButton.addEventListener("click", () => {
                this.dropdown.classList.contains("open") ? this.closeDropdown() : this.openDropdown();
            });
        }

        registerKeyboardInputEventListeners() {
            // Handle key inputs
            const events = ["keydown", "touchstart"];

            events.forEach((eventName) => {
                this.dButton.addEventListener(eventName, (e) => {
                    if (e.key === " " || e.key === "Enter") {
                        e.preventDefault();
                        e.stopPropagation();
                        this.isOpen() ? this.closeDropdown() : this.openDropdown();
                    } else if (e.key === "Escape") {
                        this.closeDropdown();
                    }
                });

                this.dList.addEventListener(eventName, (e) => {
                    if (e.key === "Escape") this.closeDropdown();
                });
            });
        }

        modifyDefaultWordpressMarkup(isMobile = false) {
            // Swap default <a> tag for a proper button
            let button = document.createElement("button");
            button.innerText = this.dButton.innerText;
            button.setAttribute("nav-sub-menu-toggle", "true");

            if(! this.isMobile) {
                button.classList.add("ac-nav-dropdown-button");

                this.dropdown.prepend(button);
                this.dButton.remove();
                this.dButton = this.dropdown.querySelector(":scope > button[nav-sub-menu-toggle]");

                // Add necessary classes and groupings to the parent <ul>
                this.dropdown.classList.add("group", "relative");
                console.log(this.dropdown.classList.contains("group"))
            }

            else {
                // remove the duplicate for the first element that is needed as on desktop its replaced with a open button
                this.dList.querySelector(":scope > li:first-child").remove();
            }

            // Add necessary classes to the submenu <ul>
            this.dList.classList.add("ac-nav-submenu");
        }
    }

    /** @type {HTMLElement[]} */
    const dropdownElems = document.querySelectorAll("#header li:has(ul.sub-menu)"); // Wordpress' conventions

    /** @type {Dropdown[]} */
    let dropdowns = [];
    let idCounter = 1;
    dropdownElems.forEach((d) => {
        const isMobile = d.classList.contains("ac-is-mobile");
        // Remove ARIA from <li>, move to button

        // Pass unique id to Dropdown
        dropdowns.push(new Dropdown(d, idCounter, isMobile));
        idCounter++;
    });

    // Close all created dropdowns
    function closeAllDropdowns() {
        dropdowns.forEach((d) => {
            d.closeDropdown(false);
        });
    }

    // Closes dropdown automatically when clicking or focusing elsewhere
    const events = ["click", "focusin"];

    events.forEach((eventType) => {
        document.addEventListener(eventType, (e) => {
            dropdowns.forEach((d) => {
                if (d.isOpen() && !(e.target === d.dropdown || d.dropdown.contains(e.target))) {
                    d.closeDropdown(false);
                }
            });
        });
    });



    // Pagination buttons
    const disabledPaginationButtons = document.querySelectorAll("a[pagination-button-disabled]")
    disabledPaginationButtons.forEach((btn) => {
        btn.removeAttribute('href');
        btn.addEventListener('click', (e) => e.preventDefault());
    });

    // Add the chevron to desktop nav dropdown list elems
    document.querySelectorAll(".ac-nav-dropdown-button").forEach(btn => {
        const chevron = document.createElement("i");
        chevron.className = "fa fa-angle-down fa-xs";
        btn.append(chevron);
    });

});