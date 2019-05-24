define([
    'uiComponent',
    'jquery',
    'ko'
], function (Component, $, ko) {
    'use strict';
    return Component.extend({

        defaults: {
            productId: null,
            productIsSimple: false
        },
        qty: ko.observable(0),
        issetQty: ko.observable(false),
        isLoading: ko.observable(false),
        url: '',
        initialize: function () {
            this._super();
            return this;
        },
        isSimpleProduct: function () {
            return this.productIsSimple;
        },
        getStock: function () {
            this.isLoading(true);
            var self = this;

            $.ajax({
                url: self.url,
                type: 'post',
                data: {
                    'product_id': this.productId
                },
                dataType: 'json'})
                .done(function (data) {
                    if (data.qty) {
                        self.qty(data.qty);
                        self.issetQty(true);
                    }
                }).always(function () {
                self.isLoading(false);
            });
        }
    });
});
