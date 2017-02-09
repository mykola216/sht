function buttons(){
    var kCanonical = document.querySelector("link[rel='canonical']").href;
    window.kCompositeSlug = kCanonical.replace('https://','http://');
    return;
}
buttons();