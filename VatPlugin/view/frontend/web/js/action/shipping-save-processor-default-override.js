define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer',
        'mage/cookies'
    ],
    function (ko, $, quote, resourceUrlManager, storage, paymentService, methodConverter, errorProcessor,customer) {
        'use strict';
        return {
            saveShippingInformation: function() {
                var payload = {

                    addressInformation: {
                        shipping_address: quote.shippingAddress(),
                        shipping_method_code: quote.shippingMethod().method_code,
                        shipping_carrier_code: quote.shippingMethod().carrier_code,
                    }
                };

                return storage.post(
                    resourceUrlManager.getUrlForSetShippingInformation(quote),
                    JSON.stringify(payload)
                ).done(
                    function (response) {
                        var countryvalue='';
                       if(customer.isLoggedIn()){
                           countryvalue = quote.shippingAddress().countryId;
                       }else{
                           countryvalue = $("#co-shipping-form").find("[name='country_id']").val();
                       }
                        if(countryvalue=="GB"){
                            $("#vatdiscount-block").show();
                            $('.vatmodule').hide();
                        }else{
                            $("#vatdiscount-block").hide();
                        }
                        quote.setTotals(response.totals);
                        paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                    }
                );
            }
        };
    }
);
