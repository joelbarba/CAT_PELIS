// purchase module
// It depends on "credits" and "products" modules
define(["credits", "products"], 
       function(credits, products) {
            console.log("Function : purchaseProduct");

            return {
                purchaseProduct: function() {

                    var credit = credits.getCredits();
                    if(credit > 0){
                        products.reserveProduct();
                        return 'ok';
                    }
                    return 'error';
                }
            };
        });