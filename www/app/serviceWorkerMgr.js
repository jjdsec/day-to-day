
// from https://developers.google.com/web/ilt/pwa/introduction-to-service-worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('serviceWorker.js', { scope: '/app/' })
    .then(function(registration) {
      console.log('Registration successful, scope is:', registration.scope);
    })
    .catch(function(error) {
      console.log('Service worker registration failed, error:', error);
    });
  }
  