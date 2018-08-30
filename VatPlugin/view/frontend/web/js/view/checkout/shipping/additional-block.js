define([
    'uiComponent',
    'jquery',
    'Magento_Checkout/js/model/cart/cache',
    'Magento_Checkout/js/action/get-totals',
    'Magento_Checkout/js/model/quote',
    'ko',
    'Magento_Customer/js/model/customer'

], function (Component, $, cartCache,getTotalsAction,quote,ko,customer) {
    'use strict';
    return Component.extend({
        defaults: {
            imports: {
                update: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id:value'
            },
            template: window.checkoutConfig.Rahul.VatPlugin,
        },
        shouldShowDiv: ko.observable(false),
        showerror:ko.observable(false),
        showsuccess:ko.observable(false),
        errormessage:ko.observable(),
        vatdiscounttextbox:ko.observable(''),
        update: function (value) {
            this.showhidediv(value);
        },
        validatevat:function(){
            var vatcode =this.vatdiscounttextbox();
            var msg = this.errormessage;
            var err  = this.showerror;
            var success = this.showsuccess;

            if (vatcode==='') {
                msg('Vat Number can not be empty !');
                err(true);
                setTimeout(function() { err(false); }, 5000);
                return false;
            } else {
                var res = vatcode.substring(0, 2);
                if (res.toUpperCase()!=='GB') {
                    msg('The provided CountryCode is invalid or the VAT number is empty');
                    err(true);
                    setTimeout(function() { err(false); }, 5000);
                    return false;
                }
            }
            var deferred = $.Deferred();
            $.ajax({
                url: '/vatplugin/index/validationcall',
                showLoader: true,
                data:  {
                    'vatnumber':vatcode
                },
                type: "post",
                cache: false,
                success: function (data) {
                    if (data.valid) {
                        err(false);
                        success(true);
                        setTimeout(function() { success(false); }, 5000);
                        getTotalsAction([], deferred);
                    } else {
                        msg('Please enter valid Vat Number!');
                        success(false);
                        err(true);
                        setTimeout(function() { err(false); }, 5000);
                        getTotalsAction([], deferred);
                    }
                }
            });
        },
        showhidediv:function(value){
            if (value==='GB') {
                this.shouldShowDiv(true);
            } else {
                this.shouldShowDiv(false);
            }
        }
    });
});