pimcore.registerNS("pimcore.plugin.JImportData.settings");

pimcore.plugin.CustomMenu.settings = Class.create({

    activate: function () {
        let tabPanel = Ext.getCmp('pimcore_panel_tabs');

        if (tabPanel.items.keys.indexOf("jImportData_settings") > -1) {
            tabPanel.setActiveItem('jImportData_settings');
        } else {
            throw "need to reload";
        }
    },

    initialize: function () {
        this.getData();
    },

    getData: function () {
        console.log('Getting the data');
        // Ext.Ajax.request({
        //     url: '/admin/custom-menu/get-custommenu-settings-attributes',
        //     success: function (response) {
        //         this.data = Ext.decode(response.responseText).data;
        //         this.getTabPanel();
        //     }.bind(this)
        // });
    },

    getTabPanel: function () {
        console.log('Getting the tab panel');
        //Stay Tuned...
    }
});