pimcore.registerNS("pimcore.plugin.RamyakantProductManagementBundle");

pimcore.plugin.RamyakantProductManagementBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {

        var navigationUl = Ext.get(Ext.query("#pimcore_navigation UL"));
        var newMenuItem = Ext.DomHelper.createDom({
                tag: 'li',
                id: 'pimcore_menu_update_product',
                'data-menu-tooltip': t('Update Product'),
                cls: 'pimcore_menu_item pimcore_icon_product'
        });
        
        navigationUl.appendChild(newMenuItem);
        pimcore.helpers.initMenuTooltips();

        newMenuItem.onclick = function(){ 
            pimcore.plugin.RamyakantProductManagementBundleForm.openProductForm(); 
        };
        
    },

    openProductForm: function () {
        // Delegate to the form handler in a separate file
        pimcore.plugin.RamyakantProductManagementBundleForm.openProductForm();
    }
});

var RamyakantProductManagementBundlePlugin = new pimcore.plugin.RamyakantProductManagementBundle();
