<template>
    <div id="data-grid-shops">
        <dx-data-grid
                :data-source="dataSource"
                :remote-operations="remoteOperations"
                :columns="columns"
                :show-borders="true"
        >
            <dx-column
                    data-field="active"
                    caption="Статус"
                    data-type="boolean"
            >
                <dx-lookup
                        :data-source="statuses"
                        value-expr="id"
                        display-expr="name"
                />
            </dx-column>
            <dx-editing

                    :select-text-on-edit-start="selectTextOnEditStart"
                    :start-edit-action="startEditAction"
                    :allow-updating="true"
                    :allow-adding="true"
                    :allow-deleting="true"
                    mode="batch"/>

            <dx-search-panel
                    :visible="true"
                    :highlight-case-sensitive="true"
            />
            <dx-group-panel :visible="true"/>
            <dx-grouping :auto-expand-all="false"/>
            <dx-pager
                    :allowed-page-sizes="pageSizes"
                    :show-page-size-selector="true"
            />
            <dx-paging :page-size="3"/>
        </dx-data-grid>
        <div class="options">
            <div class="caption">Options</div>
            <div class="option">
                <dx-check-box
                        v-model="selectTextOnEditStart"
                        text="Поиск..."
                />
            </div>
            <div class="option">
                <span>Start Edit Action</span>
                <dx-select-box
                        :items="['click', 'dblClick']"
                        v-model="startEditAction"
                />
            </div>
        </div>
    </div>

</template>
<script>

    window.axios = require('axios');

    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }

    import 'devextreme/dist/css/dx.common.css';
    import 'devextreme/dist/css/dx.material.teal.dark.compact.css';
    import {DxCheckBox, DxSelectBox} from 'devextreme-vue';
    import {
        DxDataGrid,
        DxColumn,
        DxPaging,
        DxPager,
        DxEditing,
        DxLookup,
        DxGroupPanel,
        DxGrouping,
        DxScrolling,
        DxSearchPanel
    } from 'devextreme-vue/data-grid';
    import {DxSwitch} from 'devextreme-vue/switch';
    import CustomStore from 'devextreme/data/custom_store';
    import 'whatwg-fetch';

    function handleErrors(response) {
        if (!response.ok)
            throw Error(response.statusText);
        return response;
    }

    function isNotEmpty(value) {
        return value !== undefined && value !== null && value !== "";
    }

    const gridDataSource = {
        store: new CustomStore({
            load: (loadOptions) => {
                let params = "?";
                [
                    "skip",
                    "take",
                    "requireTotalCount",
                    "requireGroupCount",
                    "sort",
                    "filter",
                    "totalSummary",
                    "group",
                    "groupSummary"
                ].forEach(function (i) {
                    if (i in loadOptions && isNotEmpty(loadOptions[i]))
                        params += `${i}=${JSON.stringify(loadOptions[i])}&`;
                });
                console.log(params);
                params = params.slice(0, -1);
                return fetch(`api/shops${params}`)
                    .then(handleErrors)
                    .then(response => response.json())
                    .then((result) => {
                        return {
                            key: result.data.id,
                            data: result.data,
                            totalCount: result.totalCount,
                            summary: result.summary,
                            groupCount: result.groupCount
                        }
                    });
            },
            insert: (values) => {
                return axios.post(`api/shops_insert`, values);//.then(handleErrors);
            },

            remove: (key) => {
                return axios.delete(`api/shops_delete/` + encodeURIComponent(key.id), {
                    method: "DELETE"
                });//.then(handleErrors);
            },
            update: (key, values) => {
                return axios.put(`api/shops_update/` + encodeURIComponent(key.id), values);//.then(handleErrors);
            }
        })
    };
    export default {
        components: {
            DxSwitch,
            DxDataGrid,
            DxEditing,
            DxCheckBox,
            DxSelectBox,
            DxGroupPanel,
            DxGrouping,
            DxScrolling,
            DxLookup,
            DxPaging,
            DxPager,
            DxColumn,
            DxSearchPanel
        },
        data() {
            return {

                columns: ['id', 'name', 'url'],
                statuses: [{
                    'id': 1,
                    'name': 'Активный'
                }, {
                    'id': 2,
                    'name': 'Неактивный'
                }],
                dataSource: gridDataSource,
                select: [
                    'id',
                    'name',
                    'url'],
                keyExpr: 'id',
                key: 'id',
                remoteOperations: {
                    filtering: true,
                    sorting: true,
                    summary: true,
                    paging: true,
                    grouping: true,
                    groupPaging: true,
                },
                pageSizes: [3, 5, 10, 15],
                selectTextOnEditStart: true,
                startEditAction: 'click'
            };
        }
    };
</script>
<style>
    #data-grid-shops {
        min-height: 700px;
    }

    .options {
        margin-top: 20px;
        padding: 20px;
        /*background: #f5f5f5;*/
    }

    .options .caption {
        font-size: 18px;
        font-weight: 500;
    }

    .option {
        margin-top: 10px;
    }

    .option > span {
        width: 120px;
        display: inline-block;
    }

    .option > .dx-widget {
        display: inline-block;
        vertical-align: middle;
        width: 100%;
        max-width: 350px;
    }
</style>
