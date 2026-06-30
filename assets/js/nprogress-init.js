(function() {
    'use strict';

    if (typeof NProgress === 'undefined') return;

    NProgress.configure({ showSpinner: false });

    function isInternalLink(link) {
        if (!link || !link.href) return false;
        if (link.protocol !== 'http:' && link.protocol !== 'https:') return false;
        if (link.hash && link.href === link.ownerDocument.location.href) return false;
        if (link.getAttribute('target') === '_blank') return false;
        return link.hostname === window.location.hostname;
    }

    document.addEventListener('click', function(e) {
        var link = e.target.closest('a');
        if (link && isInternalLink(link)) {
            NProgress.start();
        }
    }, true);

    document.addEventListener('DOMContentLoaded', function() {
        NProgress.done();
    });

    window.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            NProgress.remove();
        }
    });
})();
