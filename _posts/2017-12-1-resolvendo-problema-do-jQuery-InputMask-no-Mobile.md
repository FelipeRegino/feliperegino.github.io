---
layout: post
title: "Resolvendo o problema do jQuery InputMask no Mobile"
date: 1/12/2017
author: Felipe Regino
excerpt: "Qual solução encontrei para bug gerado pelo inputMask no mobile"
tags: [jQuery, mobile, javascript, opensource]
feature: https://i.imgur.com/G9AZEZo.jpg
comments: true
---

Olá, como vão?

Recentemente me deparei com um problema pequeno, já havia sofrido com ele em outros sites, mas como estava apenas no papel de usuário não sabia quem era o real causador.

- Mas que problema é esse maluco?

Calma vou dizer. Vocês já tentaram escrever no campo de CPF, Data de nascimento, telefone, em um formulário e ele ficou todo bagunçado? Tipo isso aqui:

<figure>
	<img src="https://i.imgur.com/3vu59jS.gif">
</figure>

Então, me encontrei novamente com esse forasteiro problemático, dessa vez como desenvolvedor, e acabei descobrindo que isso é causado pela lib InputMask jQuery, que estava sendo usado no sistema onde eu estava trabalhando. O que acontece é que o inputMask mexe com uma propriedade relacionada ao ponteiro, que é a barrinha que fica piscando indicando onde você vai escrever, e essa propriedade funciona de forma um pouco diferente no mobile e acaba não funcionando da melhor forma.

- Sim e como que resolve esse negócio?

A melhor solução foi trocar de lib. Depois de um tempo pesquisando encontrei a lib [jQuery-Mask-Plugin](https://github.com/igorescobar/jQuery-Mask-Plugin), que é um projeto open source desenvolvido pelo [Igor Escobar](https://twitter.com/igorescobar) um brasileiro, que pasmem, tem uma historia hilária com o Donald Trump (sim, O PRESIDENTE DOS EUA), você pode entender um pouco dessa historia lendo [esse artigo do medium](https://blog.chibicode.com/you-can-submit-a-pull-request-to-inject-arbitrary-js-code-into-donald-trumps-site-here-s-how-782aa6a17a56) ou o [próprio post do Igor sobre isso.](http://www.igorescobar.com/blog/2016/08/21/ive-the-chance-to-troll-donald-trump-but-i-didnt/)  

Enfim, jQuery-Mask-Plugin vai aplicando mascara conforme o usuário digita e com isso nosso problema está resolvido.

Sua utilização é bem simples, caso esteja utilizando um projeto com o composer, que era o meu caso, basta rodar:
``` bash
$ composer require igorescobar/jquery-mask-plugin
```

Você pode utilizar outros gerenciadores de pacotes como o Bower, NPM, RubyGems, ou simplesmente baixar a pasta do projeto e carregar o ```jquery.mask.js``` no seu arquivo.

Com seu jQuery-Mask-Plugin carregado basta você utilizar um seletor e aplicar a mascara, dessa forma:

{% highlight javascript %}
$("#nascimento").mask("00/00/0000");
{% endhighlight %}

Você pode também gerar um placeholder para seu campo

{% highlight javascript %}
$("#nascimento").mask("00/00/0000", {placeholder: "__/__/____"});
{% endhighlight %}

Entre outras coisas. Tudo isso está descrito na [DOCUMENTAÇÃO](https://igorescobar.github.io/jQuery-Mask-Plugin/docs.html) do projeto. Leiam e utilizem o jQuery-Mask-Plugin.

Muito obrigado por acessarem meu blog.

Grande Abraço!😄
