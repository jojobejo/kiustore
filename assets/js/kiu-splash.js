(function() {
  var splash = document.getElementById('kiuSplashScreen');

  if (!splash) {
    return;
  }

  var duration = parseInt(splash.getAttribute('data-splash-duration'), 10);
  var hideDelay = isFinite(duration) && duration > 0 ? duration : 5000;

  function closeSplash() {
    splash.classList.add('is-hiding');
    document.body.classList.remove('kiu-splash-active');

    setTimeout(function() {
      if (splash && splash.parentNode) {
        splash.parentNode.removeChild(splash);
      }
    }, 560);
  }

  window.setTimeout(closeSplash, hideDelay);
})();
