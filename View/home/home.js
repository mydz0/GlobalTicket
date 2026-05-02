function updateCountdown() {
    var el = document.getElementById('countdown');
    var parts = el.textContent.split(':').map(Number);
    var h = parts[0], m = parts[1], s = parts[2];
    if (--s < 0) { s = 59; if (--m < 0) { m = 59; if (--h < 0) { h = 0; m = 0; s = 0; } } }
    el.textContent = (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
}
setInterval(updateCountdown, 1000);

$(function () {
    var CONSENT_KEY = 'cookieConsent';

    function applyConsent(consent) {
        if (consent === 'accepted') {
            $('#cookie-overlay').hide();
            $('#cookie-banner').hide();
            $('#btn-review').hide();
            $('#btn-login').css('display', 'inline-block');
        } else if (consent === 'rejected') {
            $('#cookie-overlay').hide();
            $('#cookie-banner').hide();
            $('#btn-login').hide();
            $('#btn-review').css('display', 'block');
        } else {
            $('#cookie-overlay').show();
            $('#cookie-banner').css('display', 'flex');
            $('#btn-login').hide();
            $('#btn-review').hide();
        }
    }

    // Show modal on page load if no decision stored yet (#7, #11)
    applyConsent(localStorage.getItem(CONSENT_KEY));

    // Accept (#8, #10)
    $('#btn-accept').on('click', function () {
        localStorage.setItem(CONSENT_KEY, 'accepted');
        applyConsent('accepted');
    });

    // Reject (#9, #10)
    $('#btn-reject').on('click', function () {
        localStorage.setItem(CONSENT_KEY, 'rejected');
        applyConsent('rejected');
    });

    // Review – show banner again (#9)
    $('#btn-review').on('click', function () {
        localStorage.removeItem(CONSENT_KEY);
        applyConsent(null);
    });
});
