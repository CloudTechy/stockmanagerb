
const version = "v1:1:11"; //Change if you want to regenerate cache
const staticCacheName = `${version}static-resources`;

self.addEventListener('install', event => {
  event.waitUntil(
    caches
      .open('stockmanager')
      .then(cache =>
        cache.addAll([
          'favicon.ico',
           '/css/app.css',
          // '/css/style.css',
          // '/css/main-style.css',
          // '/font-awesome/css/font-awesome.css',
          // '/font-awesome/css/font-awesome.min.css',
          // '/font-awesome/fonts/font-awesome.otf',
          // '/font-awesome/fonts/font-awesome.webfont.eot',
          // '/font-awesome/fonts/font-awesome.webfont.svg',
          // '/font-awesome/fonts/font-awesome.webfont.ttf',
          // '/font-awesome/fonts/font-awesome.webfont.woff',
          // '/fonts/font-awesome.webfont.eot',
          // '/fonts/font-awesome.webfont.svg',
          // '/fonts/font-awesome.webfont.woff2',
          // '/fonts/glyphicons-halfings-regular.eot',
          // '/fonts/glyphicons-halfings-regular.svg',
          // '/fonts/glyphicons-halfings-regular.ttf',
          // '/fonts/glyphicons-halfings-regular.woff',
          '/fonts/Montserrat-Bold.ttf',
          '/fonts/Poppins-Bold.ttf',
          '/fonts/Poppins-Medium.ttf',
          '/fonts/Poppins-Regular.ttf',
          // '/img/avatar.png',
          // '/img/boxed-bg.png',
          // '/img/boxed-bg.jpg',
           '/img/default-150x150.png',
           '/img/img-01.png',
          // '/img/loader.gif',
          // '/img/logo.png',
           '/img/medium2.png',
           '/img/medium.png',
           '/img/user.png',
           '/img/users.png',
          '/fonts/Poppins-Bold.ttf?7940efc40d8e3b477e16cc41b0287139',
          '/fonts/Poppins-Medium.ttf?a4e11dda40531debd374e4c8b1dcc7f4',
          '/fonts/Poppins-Regular.ttf?731a28a413d642522667a2de8681ff35',
          '/fonts/Montserrat-Bold.ttf?88932dadc42e1bba93b21a76de60ef7a',
          '/login',
           '/js/app.js',
           '/script/main.js',
           '/script/tilt.jquery.min.js',
           '/script/popper.js',
           '/script/jquery-3.2.1.min.js',
           '/script/bootstrap.min.js',
           '/script/select2.min.js',
           '/fonts/vendor/@fortawesome/fontawesome-free/webfa-solid-900.woff2?64b3e814a66c2719b15abf8f7998bd73',
           //'/fonts/Poppins-Medium.ttf?a4e11dda40531debd374e4c8b1dcc7f4',
          // 'http://localhost:8000/',
          'https://fonts.googleapis.com/css?family=Nunito',
          'https://fonts.googleapis.com/css?family=Raleway:300,400,600',
          'https://fonts.gstatic.com/s/nunito/v10/XRXV3I6Li01BKofINeaB.woff2'
        ])
      )
  )
})

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      if (response) {
        //we found an entry in the cache!
        return response
      }
      return fetch(event.request)
    })
  )
})