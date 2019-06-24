<template>
    <div>
        <!--<form :action="this.route" method="POST">-->
        <!--@method('DELETE')-->
        <!--@csrf-->
        <!--</form>-->
        <dx-toolbar :items="items" />
    </div>
</template>

<script>

    import DxToolbar from 'devextreme-vue/toolbar';
    import notify from 'devextreme/ui/notify';

    export default {
        name: "BasToolBar",
        components: {
            DxToolbar
        },
        props: ['route'],
        data() {
            return {

                items: [{
                    location: 'before',
                    widget: 'dxButton',
                    options: {
                        type: 'back',
                        text: 'Back',
                        onClick: (e) => {
                            axios.get(`http://datascrap.bas/pars/shops`);//.then(handleErrors);

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