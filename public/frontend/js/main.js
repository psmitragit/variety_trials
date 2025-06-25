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

})
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
      console.log('Service Worker registered with scope:', registration.scope);
    }, function (err) {
      console.log('Service Worker registration failed:', err);
    });
  });
}


const toggleBtn = document.getElementById('sidenavToggle');
const closeBtn = document.getElementById('iconSidenav');
const sidenavBar = document.getElementById('sidenav-main');

toggleBtn.addEventListener('click', function () {
  document.body.classList.toggle('g-sidenav-pinned');

  const icon = toggleBtn.querySelector('i');
  if (document.body.classList.contains('g-sidenav-pinned')) {
    icon.classList.remove('fa-bars');
    icon.classList.add('fa-xmark');
  } else {
    icon.classList.remove('fa-xmark');
    icon.classList.add('fa-bars');
  }
});

if (closeBtn) {
  closeBtn.addEventListener('click', function () {
    document.body.classList.remove('g-sidenav-pinned');

    const icon = toggleBtn.querySelector('i');
    icon.classList.remove('fa-xmark');
    icon.classList.add('fa-bars');
  });
}

// ✅ Close sidebar on outside click
document.addEventListener('click', function (e) {
  const isSidebarOpen = document.body.classList.contains('g-sidenav-pinned');
  const clickedInsideSidebar = sidenavBar.contains(e.target);
  const clickedToggleBtn = toggleBtn.contains(e.target);

  if (isSidebarOpen && !clickedInsideSidebar && !clickedToggleBtn) {
    document.body.classList.remove('g-sidenav-pinned');

    const icon = toggleBtn.querySelector('i');
    icon.classList.remove('fa-xmark');
    icon.classList.add('fa-bars');
  }
});