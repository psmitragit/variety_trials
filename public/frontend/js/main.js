$(function () {
    $('.need-validation').each((i, ele) => {
        $(ele).validate();
    });
    $('.select2').each((i, ele) => {
        $(ele).select2();
    })


    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
          navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
            console.log('Service Worker registered with scope:', registration.scope);
          }, function(err) {
            console.log('Service Worker registration failed:', err);
          });
        });
      }
      
})