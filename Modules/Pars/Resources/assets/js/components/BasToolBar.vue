<template>
    <div>
        <dx-load-indicator
                id="large-indicator"
                :height="60"
                :width="60"
                :visible="loadIndicatorVisible"
        />
        <a class="nav-link" :href="route('CategoryIndex')">Home</a>
        <dx-toolbar :items="items"/>
    </div>
</template>

<script>

    import DxToolbar from 'devextreme-vue/toolbar';
    import {DxLoadIndicator} from 'devextreme-vue/load-indicator';
    import notify from 'devextreme/ui/notify';

    export default {

        name: "BasToolBar",
        props: ['homeRoute'],
        components: {
            DxToolbar,
            DxLoadIndicator
        },
        data() {
            return {
                loadIndicatorVisible: false,
                items: [{
                    location: 'before',
                    widget: 'dxButton',
                    options: {
                        type: 'back',
                        icon: 'repeat',
                        hint: 'Спарсить данные',
                        text: 'Back',
                        onClick: (e) => {
                            this.loadIndicatorVisible = true;
                            notify('Парсинг данных запущен!')
                            setTimeout(() => {
                                this.loadIndicatorVisible = false;
                            }, 360000);

                            return axios.get(route('ProdСategoriesPars'))
                                .then((response) => {
                                    return response.data;
                                });
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
                            items: ['All', 'Family', 'Favorites'],
                            icon: 'refresh',
                            onItemClick: (e) => {
                                // console.log(e);
                                notify(e.itemData + ' Center button has been clicked!');
                            }
                        }
                    }
                ]
            }
        },
        methods: {
            route: route
        }
    }
</script>

<style scoped>
    .dx-toolbar {
        background-color: azure;
    }
</style>