pimcore.registerNS("pimcore.plugin.RamyakantProductManagementBundleForm");

pimcore.plugin.RamyakantProductManagementBundleForm = {
    openProductForm: function () {
        var formPanel = new Ext.form.FormPanel({
            title: t('Update or Create Product'),
            bodyStyle: 'padding:10px;',
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: t('SKU'),
                    name: 'sku',
                    allowBlank: false,
                    width: 400
                },
                {
                    xtype: 'textfield',
                    fieldLabel: t('Name'),
                    name: 'name',
                    width: 400
                },
                {
                    xtype: 'numberfield',
                    fieldLabel: t('Price'),
                    name: 'price',
                    allowBlank: false,
                    decimalPrecision: 2,
                    minValue: 0,
                    width: 400
                },
                {
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        pack: 'end'
                    },
                    

                    items: [
                        {
                            xtype: 'button',
                            text: t('Submit'),
                            handler: function (btn) {
                                var form = btn.up('form').getForm();
                                if (form.isValid()) {
                                    form.submit({
                                        url: '/api/product/update',
                                        method: 'POST',
                                        waitMsg: t('Saving...'),
                                        success: function (form, action) {
                                            Ext.Msg.alert(t('Success'), action.result.message);
                                        },
                                        failure: function (form, action) {
                                            Ext.Msg.alert(t('Error'), action.result.message);
                                        }
                                    });
                                }
                            }
                        }
                    ]
                }
            ]
        });

        var window = new Ext.Window({
            width: 425,
            height: 260,
            modal: true,
            layout: 'fit',
            items: [formPanel]
        });

        window.show();
    }
};