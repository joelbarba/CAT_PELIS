require.config({
    baseUrl     : "scripts",     // Directori base per els paths de fitxers
    paths       : {},            // Aliases and paths of modules
    map         : {},            // Maps
    shim        : {},            // Modules and their dependent modules
    waitSeconds : 0
});

// This code depends on the "purchase" module
require(["purchase"],
        function(purchase) {
            // alert(purchase.purchaseProduct());
            alert("hello");
        });
