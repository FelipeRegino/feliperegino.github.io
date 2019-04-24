---
layout: post
title: "Como transformar um site no github pages feito com jekyll em uma pwa"
date: 24/4/2019
author: Felipe Regino
excerpt: "O que fiz para transformar esse site em uma pwa"
tags: [PWA, mobile, javascript, github, github-pages, jekyll, google, web, cache]
feature: https://i.imgur.com/PEGiWkR.jpg
comments: true
---

Olá você, tudo em cima?

Nesse post, irei comentar o processo que fiz, para transformar esse site no qual você está agora em uma [PWA](https://developers.google.com/web/progressive-web-apps/).

Antes de mais nada, gostaria de pedir desculpas por não colocar o link dos lugares onde pesquisei pois acabei perdendo meu histórico :(

Sem mais delongas vamos direto ao ponto agora. Primeiramente indico fortemente que instalem uma extensão para chrome chamada [Lighthouse](https://developers.google.com/web/tools/lighthouse/), ela vai nos ajudar a ver se estamos fazendo tudo direitinho, retornando para a gente uma tela com dados como esta:

<figure>
	<img src="https://i.imgur.com/osap8XI.png">
</figure>
Como podem ver, preciso melhor a acessibilidade do meu site.

## Manifest.json

A partir dai, precisamos criar um ```manifest.json``` na pasta raiz do seu projeto, que vai ficar mais ou menos como isso aqui:

``` bash
{
  "name": "Felipe Regino",
  "short_name": "Felipe Regino",
  "theme_color": "#333333",
  "background_color": "#333333",
  "display": "standalone",
  "Scope": "/",
  "start_url": "/",
  "icons": [
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-72x72.png",
      "sizes": "72x72",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-96x96.png",
      "sizes": "96x96",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-128x128.png",
      "sizes": "128x128",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-144x144.png",
      "sizes": "144x144",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-152x152.png",
      "sizes": "152x152",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-192x192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-384x384.png",
      "sizes": "384x384",
      "type": "image/png"
    },
    {
      "src": "https://feliperegino.github.io/assets/img/icons/icon-512x512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ],
  "splash_pages": null
}
```

Para facilitar, utlizei o site [Web App Manifest Generator](https://app-manifest.firebaseapp.com/) que já gera o ```manifest.json``` e todos os icones nos tamanhos corretos para você, só é preciso atualizar no ```manifest.json``` para os caminhos corretos onde você colocou seus icones.

Com o ```manifest.json``` e os icones prontos, você precisa declarar no <head> do seu site. Adicione a seguinte tag dentro do seu <head>:
```html
  <link rel="manifest" href="/manifest.json">
```

## Service Worker

Precisamos agora criar um service worker para nos ajudar com o cache!

Crie um arquivo na raiz do seu projeto como o nome ```serice-worker.js```, cole esse código e faça as alterações necessarias para as especificidades do seu site.

{% highlight javascript %}
const VERSION = "1.0.0";

const APP_CACHE_NAME = 'felipe-regino-app';
const STATIC_CACHE_NAME = 'felipe-regino-static';

//rotas dos arquivos estáticos
const CACHE_STATIC = [
    '/assets/css/main.css',
    '/assets/img/icons/icon-72x72.png',
    '/assets/img/icons/icon-96x96.png',
    '/assets/img/icons/icon-128x128.png',
    '/assets/img/icons/icon-144x144.png',
    '/assets/img/icons/icon-152x152.png',
    '/assets/img/icons/icon-192x192.png',
    '/assets/img/icons/icon-384x384.png',
    '/assets/img/icons/icon-512x512.png',
    '/assets/img/logo.jpg',
 ];

//rotas das paginas do site
 const CACHE_APP = [
    '/',
    '/404',
    '/about/',
    '/tags/',
    '/posts/',
    '/resolvendo-problema-do-jQuery-InputMask-no-Mobile/',
    '/motivacao-do-blog/',
 ];

self.addEventListener('install',function(e){
    e.waitUntil(
        Promise.all([
            caches.open(STATIC_CACHE_NAME),
            caches.open(APP_CACHE_NAME),
            self.skipWaiting()
          ]).then(function(storage){
            var static_cache = storage[0];
            var app_cache = storage[1];
            return Promise.all([
              static_cache.addAll(CACHE_STATIC),
              app_cache.addAll(CACHE_APP)]);
        })
    );
});

self.addEventListener('activate', function(e) {
    e.waitUntil(
        Promise.all([
            self.clients.claim(),
            caches.keys().then(function(cacheNames) {
                return Promise.all(
                    cacheNames.map(function(cacheName) {
                        if (cacheName !== APP_CACHE_NAME && cacheName !== STATIC_CACHE_NAME) {
                            console.log('deleting',cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
        ])
    );
});

this.addEventListener('fetch', function(event) {
  var response;
  event.respondWith(caches.match(event.request)
    .then(function (match) {
      return match || fetch(event.request);
    }).catch(function() {
      return fetch(event.request);
    })
    .then(function(r) {
      response = r;
      caches.open(APP_CACHE_NAME).then(function(cache) {
        cache.put(event.request, response);
      });
      return response.clone();
    })
  );
});
{% endhighlight %}
Essa é uma adaptação minha de um código que achei na internet, novamente desculpem por não dar os créditos. Tentei alguns outros códigos mas esse foi o que funcionou melhor no meu caso, que tenho um blog estático no github pages.

Agora que ja temos nosso Service Worker, precisamos registra-lo! Volte no seu <head> e cole o seguinte código:

{% highlight javascript %}
<script type="text/javascript">
	if ("serviceWorker" in navigator) {
		navigator.serviceWorker.register("/service-worker.js").then(function(registration) {
			console.log("Service Worker registration successful with scope: ", registration.scope);
		}).catch(function(err) {
			console.log("Service Worker registration failed: ", err);
		});
	}
</script>
{% endhighlight %}

Com isso o seu site já funcionará como uma PWA!

hasta la vista!
