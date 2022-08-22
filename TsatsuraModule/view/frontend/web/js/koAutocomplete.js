define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlBuilder) {
    return Component.extend({
        searchText: '',
        searchResult: [],
        searchUrl: urlBuilder.build('tsatsura/index/search'),
        minChar: 3,
        timerId: null,
        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);
            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.handleAutocomplete.bind(this));
        },
        handleAutocomplete: function (searchValue) {
            if (searchValue.length >= this.minChar) {
                $('.autocomplete-list').removeClass('hide').addClass('load');
                this.delayQuery(function () {
                    $.getJSON(this.searchUrl, {
                        sku: searchValue,
                    }, function (data) {
                        $('.autocomplete-list').removeClass('load');
                        this.searchResult(data);
                    }.bind(this));
                }.bind(this), 1500);
            } else {
                $('.autocomplete-list').addClass('hide');
                this.searchResult([]);
            }
        },
        delayQuery(ajaxQuery, delay) {
            if (this.timerId) {
                clearTimeout(this.timerId);
            }
            this.timerId = setTimeout(function(){
                ajaxQuery();
            }.bind(this), delay);
        }
    });
});