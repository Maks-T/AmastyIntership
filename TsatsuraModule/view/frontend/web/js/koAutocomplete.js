define(['uiComponent', 'jquery', 'mage/url'], function (Component, $, urlBuilder) {
    return Component.extend({
        searchText: '',
        searchResult: [],
        searchUrl: urlBuilder.build('tsatsura/index/search'),
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
            if (searchValue.length >= 3) {
                this.delayQuery(function () {
                    $.getJSON(this.searchUrl, {
                        sku: searchValue,
                    }, function (data) {
                        this.searchResult(data);
                    }.bind(this));
                }.bind(this), 1500);
            } else {
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