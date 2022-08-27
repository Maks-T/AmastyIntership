define([
    'uiComponent',
    'jquery',
    'mage/url'
], function (
    Component,
    $,
    urlBuilder
) {
    return function (Component) {
        return Component.extend({
            initialize: function () {
                this._super();
                this.minChar = 5;
            }
        });
    }
});