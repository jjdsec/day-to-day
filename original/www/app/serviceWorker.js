// from https://developers.google.com/web/ilt/pwa/lab-scripting-the-service-worker
self.addEventListener('install', event => {
    console.log('Service worker installing...');
    // Add a call to skipWaiting here
    // self.skipWaiting();
    // to activate new version without waiting for the next session
  });
  
self.addEventListener('activate', event => {
    console.log('Service worker activating...');
});
// self.addEventListener('fetch', event => {
//     console.log('Event: Fetch:', event.request.url);
// });
  
  