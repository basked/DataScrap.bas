<template>
    <div>
         <dx-toolbar :items="items" />
    </div>
</template>

<script>

    import DxToolbar from 'devextreme-vue/toolbar';
    import notify from 'devextreme/ui/notify';

    export default {

        name: "BasToolBar",
        props: ['homeRoute'],
        components: {
            DxToolbar
        },
        data() {
            return {

                items: [{
                    location: 'before',
                    widget: 'dxButton',
                    options: {
                        type: 'back',
                        icon: 'edit',

                        text: 'Back',
                        onClick: (e) => {
                            notify(e.itemData+'Back button has been clicked!')
                        }
                    }
                },
                    {
                        location: 'before',
                        widget: 'dxButton',
                        locateInMenu: 'auto',

                        options: {
                            icon: 'refresh',

                            onClick: (e) => {
                                console.log(e);
                                notify(e.itemData + 'Refresh button has been clicked!')
                            }
                        }
                    }
                    ,
                    {
                        location: 'center',
                        widget: 'dxSelectBox',
                        locateInMenu: 'auto',
                        options: {
                            items:['All','Family','Favorites'],
                            icon: 'refresh',
                            onItemClick: (e) => {
                               // console.log(e);
                                notify(e.itemData+ ' Center button has been clicked!');
                            }
                        }
                    }
                ]
            }
        }
    }
</script>

<style scoped>
 .dx-toolbar{
     background-color: azure;
}
</style>