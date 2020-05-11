(function($){
    
    var Product = {
        _container: null,
        _products: null,
        init: function() {
            this.initProduct();
        },
        addContainer: function(container) {
            this._container = container;
        },
        initProduct: function() {
            var _this = this;
            
            $.get("/product", function( data ) {
                _this._products = data;
                _this._renderHtml();
            });
        },
        _renderHtml: function() {
            
            if(this._products.length) {
                
                for (var index in this._products) {
                    var product = this._products[index];
                    var content = this._renderProduct(product);
                    this._addContentInWrap(content);
                }
                
                $(".product-buy").on("click", this.buy);
            }
        },
        _renderProduct: function(product) {
            var content = '';
            content += '<p>Name: '+product.name+' </p>';
            content += '<p>Price: '+product.price+' </p>';
            
            if ('discount' in product) {
                content += '<p>Discount: '+product.discount+'%</p>';
            }
            
            if ('discount_price' in product) {
                content += '<p>Price With Discount: '+product.discount_price+'</p>';
            }
            
            content += '<p><a class="product-buy" href="/product/buy/'+product.id+'">Buy</a></p>';
            return content;
        },
        _addContentInWrap: function(content) {
            this._container.append('<div>'+content+'</div>');
        },
        buy: function(element) {
            var link = $(this).attr('href');
            $.get(link, function(data) {
                if ('success' in data) {
                    Product.clearWrap();
                    Product.init();
                }
            }).fail(function(xhr) {
                var result = JSON.parse(xhr.responseText);
                alert(result.error);
            });
            
            return false;
        },
        clearWrap: function() {
            this._container.html('');
        }
    };
    
    $(document).ready(function() {
        Product.addContainer($('.content'));
        Product.init();
    });
    
})(jQuery);
