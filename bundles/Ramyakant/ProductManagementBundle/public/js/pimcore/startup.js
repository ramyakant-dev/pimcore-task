pimcore.registerNS("pimcore.plugin.RamyakantProductManagementBundle");

pimcore.plugin.RamyakantProductManagementBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("RamyakantProductManagementBundle ready!");
    }
});

var RamyakantProductManagementBundlePlugin = new pimcore.plugin.RamyakantProductManagementBundle();
