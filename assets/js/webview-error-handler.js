(function () {
    'use strict';

    var config = window.WEBVIEW_ERROR_CONFIG || {};
    var noInternetUrl = config.noInternetUrl || '/error/no-internet';
    var appErrorUrl = config.appErrorUrl || '/error/app';
    var notifyTarget = document.getElementById(config.notifyElementId || 'internetStatus');
    var timeoutMs = parseInt(config.timeoutMs, 10);
    var captureRuntimeErrors = config.captureRuntimeErrors === true;
    var captureAjax5xx = config.captureAjax5xx === true;
    var onlyWebView = config.onlyWebView !== false;

    function isLikelyWebView() {
        var ua = navigator.userAgent || '';
        var isAndroidWebView = /; wv\)/i.test(ua) || /Version\/[\d.]+/i.test(ua) && /Android/i.test(ua);
        var isIOSWebView = /iPhone|iPad|iPod/i.test(ua) && /AppleWebKit/i.test(ua) && !/Safari/i.test(ua);
        return isAndroidWebView || isIOSWebView;
    }

    if (onlyWebView && !isLikelyWebView()) {
        return;
    }

    if (isNaN(timeoutMs) || timeoutMs <= 0) {
        timeoutMs = 20000;
    }

    function alreadyOnErrorPage() {
        var path = window.location.pathname || '';
        return path.indexOf('/error/') !== -1;
    }

    function showNoInternet() {
        if (!notifyTarget) {
            return;
        }

        notifyTarget.textContent = 'No Internet Access';
        notifyTarget.style.backgroundColor = '#ea4c62';
        notifyTarget.style.boxShadow = '0 .5rem 1rem rgba(0,0,0,.15)';
        notifyTarget.style.display = 'block';
    }

    function redirect(url) {
        if (alreadyOnErrorPage()) {
            return;
        }
        window.location.href = url;
    }

    window.addEventListener('offline', function () {
        showNoInternet();
        redirect(noInternetUrl);
    });

    if (window.jQuery) {
        jQuery.ajaxSetup({
            timeout: timeoutMs
        });

        jQuery(document).ajaxError(function (_event, jqXHR, _settings, thrownError) {
            var isTimeout = jqXHR && jqXHR.statusText === 'timeout';
            var isNetworkError = jqXHR && jqXHR.status === 0;
            var isServerError = jqXHR && jqXHR.status >= 500;
            var timeoutByThrown = String(thrownError || '').toLowerCase() === 'timeout';

            if (isTimeout || timeoutByThrown || isNetworkError) {
                showNoInternet();
                redirect(noInternetUrl);
                return;
            }

            if (captureAjax5xx && isServerError) {
                redirect(appErrorUrl);
            }
        });
    }

    if (captureRuntimeErrors) {
        window.addEventListener('error', function () {
            redirect(appErrorUrl);
        });

        window.addEventListener('unhandledrejection', function () {
            redirect(appErrorUrl);
        });
    }
})();
