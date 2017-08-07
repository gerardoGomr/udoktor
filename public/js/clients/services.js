'use strict';

jQuery(document).ready(function($) {
    let $loader               = $('#loader'),
        $services             = $('#services'),
        $helpText             = $('#help-text'),
        $offeredServices      = $('#offeredServices'),
        $dataServiceProviders = $('#data-service-providers'),
        servicesList          = JSON.parse(atob($services.val())),
        services              = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local:          servicesList
        });

    $('.instructions').tooltip({
        container: 'body'
    });

    services.initialize();
    $offeredServices.tagsinput({
        itemValue: 'value',
        itemText:  'text',
        typeaheadjs: {
            name:       'services',
            displayKey: 'text',
            source:     services.ttAdapter()
        }
    });
});