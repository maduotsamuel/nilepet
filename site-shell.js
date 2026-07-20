document.addEventListener("DOMContentLoaded", function () {
    document.documentElement.classList.add("animations-enabled");

    const activePage = document.body.getAttribute("data-page") || "home";
    const headerHost = document.getElementById("site-header");
    const footerHost = document.getElementById("site-footer");

    const navItems = [
        { key: "home", label: "Home", href: "index.html" },
        { key: "about", label: "About us", href: "about.html" },
        { key: "business", label: "Our business", href: "business.html" },
        { key: "operations", label: "Operations", href: "operations.html" },
        { key: "gallery", label: "Gallary", href: "gallary.html" },
        { key: "news", label: "News and events", href: "news.html" },
        { key: "contacts", label: "Contacts", href: "contacts.html" },
    ];

    function buildNav() {
        const links = navItems
            .map(function (item) {
                const activeClass = item.key === activePage ? " active" : "";
                return (
                    '<li class="nav-item">' +
                        '<a class="nav-link' + activeClass + '" href="' + item.href + '">' +
                            item.label +
                        '</a>' +
                    '</li>'
                );
            })
            .join("");

        return [
            '<nav class="navbar navbar-expand-xl site-navbar sticky-top">',
            '  <div class="container">',
            '    <a class="navbar-brand" href="index.html">',
            '      <img src="images/logo.png" alt="Nile Petroleum Corporation logo">',
            '      <span class="brand-text">',
            '        <span>Nile Petroleum Corporation</span>',
            '        <small>National Oil and Gas Company</small>',
            '      </span>',
            '    </a>',
            '    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavbar" aria-controls="siteNavbar" aria-expanded="false" aria-label="Toggle navigation">',
            '      <span class="navbar-toggler-icon"></span>',
            '    </button>',
            '    <div class="collapse navbar-collapse" id="siteNavbar">',
            '      <ul class="navbar-nav ms-auto align-items-lg-center">',
                    links,
            '      </ul>',
            '    </div>',
            '  </div>',
            '</nav>',
        ].join("\n");
    }

    function buildFooter() {
        const footerLinks = navItems
            .map(function (item) {
                return '<li><a href="' + item.href + '">' + item.label + '</a></li>';
            })
            .join("");

        return [
            '<footer class="site-footer">',
            '  <div class="container">',
            '    <div class="row g-4">',
            '      <div class="col-lg-4">',
            '        <a class="footer-brand" href="index.html">',
            '          <img src="images/logo.png" alt="Nile Petroleum Corporation logo">',
            '          <span>Nile Petroleum Corporation</span>',
            '        </a>',
            '        <p>',
            '          Nile Petroleum Corporation is the national oil and gas company of the Republic of South Sudan, participating across the petroleum value chain on behalf of the nation.',
            '        </p>',
            '        <div class="social-links mt-3">',
            '          <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>',
            '          <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>',
            '          <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>',
            '          <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>',
            '        </div>',
            '      </div>',
            '      <div class="col-6 col-lg-2">',
            '        <h5>Quick Links</h5>',
            '        <ul class="footer-links">',
                    footerLinks,
            '        </ul>',
            '      </div>',
            '      <div class="col-lg-3">',
            '        <h5>Contact Information</h5>',
            '        <div class="footer-contact-item">',
            '          <i class="bi bi-geo-alt-fill"></i>',
            '          <span>Plot 496, Block 3-K, Opposite Arkel Restaurant, Off Airport-Ministries Road, Juba, South Sudan</span>',
            '        </div>',
            '        <div class="footer-contact-item">',
            '          <i class="bi bi-telephone-fill"></i>',
            '          <span>+211 955 668 869</span>',
            '        </div>',
            '        <div class="footer-contact-item">',
            '          <i class="bi bi-envelope-fill"></i>',
            '          <span>info@nilepet.com</span>',
            '        </div>',
            '        <div class="footer-contact-item">',
            '          <i class="bi bi-mailbox2"></i>',
            '          <span>P.O. Box 390, Juba, South Sudan</span>',
            '        </div>',
            '      </div>',
            '      <div class="col-lg-3">',
            '        <h5>About Nilepet</h5>',
            '        <p>',
            '          Established in 2003 and built to represent South Sudan’s commercial interests in petroleum, Nilepet supports upstream, downstream, logistics, and strategic partnerships.',
            '        </p>',
            '      </div>',
            '    </div>',
            '    <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">',
            '      <span>© <span id="currentYear"></span> Nile Petroleum Corporation. All rights reserved.</span>',
            '      <span>National Oil and Gas Company of South Sudan</span>',
            '    </div>',
            '  </div>',
            '</footer>',
        ].join("\n");
    }

    if (headerHost) {
        headerHost.innerHTML = buildNav();
    }

    if (footerHost) {
        footerHost.innerHTML = buildFooter();
    }

    const currentYear = document.getElementById("currentYear");
    if (currentYear) {
        currentYear.textContent = new Date().getFullYear();
    }

    const revealItems = Array.from(document.querySelectorAll(".fade-up"));
    if (revealItems.length && "IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.2,
                rootMargin: "0px 0px -8% 0px"
            }
        );

        revealItems.forEach(function (item, index) {
            item.style.transitionDelay = (index * 120) + "ms";
            revealObserver.observe(item);
        });
    } else {
        revealItems.forEach(function (item) {
            item.classList.add("is-visible");
        });
    }
});