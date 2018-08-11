ShopifyApp.init({
    apiKey: key,
    shopOrigin: 'https://' + shop,
    debug: false,
    forceRedirect: true
});

ShopifyApp.Bar.initialize({
    title: title,
    icon: ''
});