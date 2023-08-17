# Hibrido_MetaTag_MultiSite
<h3>Desafio Hibrido - Teste #1 - Meta Tag em Multi-site</h3>

- [Instalação](#instalação)
- [Adicionar o Bloco ao head](#adicionar-o-bloco-ao-head)
- [Personalizar o Bloco](#personalizar-o-bloco)
- [Limpar o Cache](#limpar-o-cache)
- [Explicações Gerais](#explicações-gerais)

## Instalação

Siga esses passos para instalar o módulo:

1. Copie os arquivos do módulo para o diretório `app/code`.
2. Habilite o módulo usando o comando: `php bin/magento module:enable Hibrido_MetaTag`.
3. Execute a atualização do setup: `php bin/magento setup:upgrade`.
4. Em seguida é necessário compilar os arquivos: `php bin/magento setup:di:compile`

## Adicionar o Bloco ao head

Para incluir o bloco na seção head da sua página, é necessário modificar o arquivo de layout `default.xml` no seu tema. dessa forma:

1. Navegue até `app/design/frontend/Vendor/Theme/Magento_Theme/layout/default.xml`.
2. Encontre o elemento `<referenceContainer name="head.additional">`.
3. Adicione o seguinte código para incluir o seu bloco:

```
<block class="Hibrido\MetaTag\Block\Metatag" name="hibrido_metatag" template="metatag.phtml"/>
```

4. Caso seu tema não tenha o arquivo `default.xml`, é necessário criar dentro de `app/design/frontend/Vendor/Theme/Magento_Theme/layout` e inserir o código.

```
<head>
    <referenceBlock name="head.additional">
        <block class="Hibrido\MetaTag\Block\Metatag" name="hibrido_metatag" template="metatag.phtml"/>
    </referenceBlock>
</head>
```
   
5. Salve o arquivo

## Personalizar o Bloco

Se precisar personalizar/customizar o bloco, é possível fazer isso modificando o arquivo template do módulo, localizado em `app/code/Hibrido/MetaTag/view/frontend/templates/metatag.phtml`.

## Limpar o Cache

Depois de fazer alterações, sempre lembrar de limpar o cache para garantir que o bloco seja renderizado corretamente. 
Você pode fazer isso executando: 
```php bin/magento cache:clean```



## Explicações Gerais
Após explicar a parte de instalação do módulo, vou estar colocando aqui meus pontos sobre o módulo e como foi a experiência de desenvolver.

Comecei criando os arquivos basicos do módulo como `registration.php, module.xml`.
Depois criei o arquivo de **template** e o **block** para ser chamado no `default.xml`.

Feito isso fiz a configuração da loja dentro do administrativo, onde criei as store_views, coloquei os parâmetros de linguagem, região, etc. Tudo para simular o máximo possível o ambiente da tarefa.
Criei a página CMS -> about-us e comecei a realizar os testes.

Então fui indo por partes conforme o desafio solicita:

`1. Adicione um bloco ao head`
Configurei o layout para inserir o meu block dentro do head.

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/89ffdbc4-4b3e-48c7-9433-b1af4d97f1df)


`2. O bloco deve ser capaz de identificar o ID da página CMS e verificar se a
página é usada em múltiplas store-views`
Dentro do meu block criei duas funções, sendo elas getStoreCode() e a getCurrentPage(), onde elas são responsáveis por pegar o ID da página atual e carregar a mesma, sendo assim eu consigo ter acesso ao storeIds que essa página está vinculada.

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/338c6490-3053-463d-914c-6cc3e72da07b)


`3. Nesse caso, deve adicionar uma Meta Tag hreflang ao head para cada
store-view que a página esteja ativa`
Com isso em mente, criei no arquivo de template o `for` para gerar um `<link hreflang>` para cada storeView detectado na página. 

**Observação**: Imagino que o melhor cenário seria ter criado um foreach para exibir dessa forma, mas logo vou explicar o porque foi necessário fazer dessa forma.

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/284a7122-c6a6-4011-8a2d-d3e7404abf31)


`4. As Meta Tag’s devem exibir o idioma da loja (exemplo: en-gb, en-us, pt-br,
etc...)`
Para realizar essa funcionalidade, aproveitei as configurações do code dos storeviews, que já estavão determinando qual seria a linguagem da página e eu já havia feito a instância e injeção no block para carregar essas informações.

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/94ea96a5-b9d1-4ce5-ac2a-e9c58c74066d)


Com os passos feitos, comecei a validar as outras informações do desafio, como a estrutura da tag e o resultado final esperado.
No momento de configurar as storeviews conforme o desafio me solicitou:

```
Existem três store-views configuradas na instalação Magento, uma para o Brasil,
outra para os EUA e outra para a Inglaterra, o idioma do Brasil está definido como
pt-br, o dos EUA está definido como en-us e o da Inglaterra como en-gb, todos
configurados como o idioma padrão da store-view.
As URL’s base estão configuradas igualmente (exemplo: https://www.hibrido.com.br/)
e a configuração de adicionar o código das store-views na URL está habilitada,
exemplos abaixo:
● Brasil: https://www.hibrido.com.br/pt-br/
● EUA: https://www.hibrido.com.br/en-us/
● Inglaterra: https://www.hibrido.com.br/en-gb/
```

Tive um pequeno problema... O Magento 2 não aceita `-` no code dos seus storeviews, e o desafio foi feito baseado nisso. **G.G**

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/ecaa3a3a-ac4f-4cde-89bf-85996f8c08bf)


Mas para não parar por ae, cadastrei elas com o `_` que é aceito pela plataforma e adicionei o code dos storeview em suas urls, no caso a minha url basica da loja de teste é `http://nogaromerce.com.br/`, fazendo com que ficassem:

```
http://nogaromerce.com.br/pt_br/
http://nogaromerce.com.br/en_us/
http://nogaromerce.com.br/en_gb/
```

E na hora de exibir na metatag, fiz a conversão utilizando o implode e o explode do PHP:

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/c351ad1a-ed62-4abb-8520-a3ab26a7dd9a)


E como eu estava utilizando o $block->getBaseUrl() nativo do Magento 2, ele estava me retornando todos os links dessa forma:

```
<link rel="alternate" hreflang="**pt-br**" href="http://nogaramagento.com.br/**pt_br**/about-us">
<link rel="alternate" hreflang="**en-gb**" href="http://nogaramagento.com.br/**pt_br**/about-us">
<link rel="alternate" hreflang="**en-gb**" href="http://nogaramagento.com.br/**pt_br**/about-us">
```

Onde o href sempre estava pegando a página atual, por isso houve a necessidade de eu criar a função $block->getStoreViewBaseUrl(), que pega todos os stores views e me retorna as suas urls basicas:

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/95e6c103-4b77-477a-914b-f9c6054b3cc2)

O retorno dela:

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/f4404883-9b38-4e1a-a41c-ebfbd1714e1b)


E foi por esse mótivo que utilizei o for ao invés do foreach, dessa forma consigo correr os dois arrays de uma vez:

```
<?php for ($i = 0; $i < count($storeViews); $i++): ?>
    <link rel="alternate" hreflang="<?= $storeLanguages[$i] ?>" href="<?= $baseUrls[$i] . $cmsPageUrl ?>">
<?php endfor ?>
```

Fazendo com que o resultado final fosse conforme o esperado:

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/174b71b8-de89-430e-b195-a1a484994016)


Sendo que o code do storeview só não está com o `-` pois não é aceitado pelo Magento 2 dessa forma.
E o código também está otimizado para receber infinitas linguagens e paises, tomei a liberdade de realizar alguns testes com mais storeviews:

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/9ed2bd87-6237-4269-86bb-03cdd9c0dff0)


E o resultado dos links foram:

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/925b5cfd-02fc-4011-929f-d0fc66146fa2)


E para finalizar, criei algumas validações no código para caso o cliente acesse uma página que não é um CMS, ele não ocasionar em nenhum erro!

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/69cf563a-e02a-4cae-af07-c42d2ef4c506)

![image](https://github.com/ElNogara/Hibrido_MetaTag_MultiSite/assets/50090354/0066b97d-658c-450a-99b8-b26ce9caf1eb)















