<template>
    <div id="data-grid-shops">
        <bas-pop-up
                :show-info="showInfo"
        >
        </bas-pop-up>
        <dx-data-grid
                :data-source="dataSource"
                :remote-operations="remoteOperations"
                :columns="columns"
                :show-borders="true"
                :allow-column-resizing="true"
                :allow-column-reordering="true"
                @context-menu-preparing="addMenuItems"
        >
            <dx-column
                    data-field="active"
                    caption="Статус"
                    data-type="boolean"
                    :allow-grouping="false"
            >
                <dx-lookup
                        :data-source="statuses"
                        value-expr="id"
                        display-expr="name"
                />
            </dx-column>
            <dx-column
                    caption="Операции"
                    :width="110"
                    :buttons="editButtons"
                    type="buttons"
            />
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
            <dx-filter-row :visible="true"/>
            <dx-header-filter :visible="true"/>
            <dx-group-panel :visible="true"/>
            <dx-grouping :auto-expand-all="false"
                         :context-menu-enabled="true"
                         expand-mode="rowClick"
            />
            <dx-pager
                    :allowed-page-sizes="pageSizes"
                    :show-page-size-selector="true"
            />
            <dx-paging :page-size="10"/>
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
        DxSearchPanel,
        DxFilterRow,
        DxHeaderFilter
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
            DxSearchPanel,
            DxFilterRow,
            DxHeaderFilter
        },
        props: ['homeRoute'],
        data() {
            return {
                // для popup
                shops : [{
                    'ID': 7,
                    'FirstName': 'Sandra',
                    'LastName': 'Johnson',
                    'Prefix': 'Mrs.',
                    'Position': 'Controller',
                    'Picture': 'images/employees/06.png',
                    'BirthDate': '1974/11/15',
                    'HireDate': '2005/05/11',
                    'Notes': "Sandra is a CPA and has been our controller since 2008. She loves to interact with staff so if you've not met her, be certain to say hi.\r\n\r\nSandra has 2 daughters both of whom are accomplished gymnasts.",
                    'Address': '4600 N Virginia Rd.'
                }, {
                    'ID': 10,
                    'FirstName': 'Kevin',
                    'LastName': 'Carter',
                    'Prefix': 'Mr.',
                    'Position': 'Shipping Manager',
                    'Picture': 'images/employees/07.png',
                    'BirthDate': '1978/01/09',
                    'HireDate': '2009/08/11',
                    'Notes': 'Kevin is our hard-working shipping manager and has been helping that department work like clockwork for 18 months.\r\n\r\nWhen not in the office, he is usually on the basketball court playing pick-up games.',
                    'Address': '424 N Main St.'
                }],

                popupVisible: true,

                // кнопки для грида
                editButtons: ['edit', 'delete', {
                    caption: 'Операции',
                    hint: 'Обновить данные',
                    icon: 'repeat',
                    onClick: this.updateProductsCnt
                }],
                // столбцы

                columns: [{
                    dataField: "id", caption: 'Код на сайте',
                    width: 150,
                    visible: false
                }, {
                    width: 400,
                    dataField: 'name',
                    caption: 'Наименование'
                }, {
                    dataField: 'url',
                    caption: 'Url',
                    width: 400
                }],
                statuses: [{
                    "id": 1,
                    "name": "Активный"
                }, {
                    "id": 0,
                    "name": "Неактивный"
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
                pageSizes: [10, 25, 50],
                selectTextOnEditStart: true,
                startEditAction: 'click'
            };
        },
        methods: {
            route: route,
            showInfo() {
                this.popupVisible = true;
                console.log('basket');
            },
            updateProductsCnt() {
               this.popupVisible=true;
               this.showInfo();

            },
            addMenuItems(e) {
                console.log(e.target);

                // добавим меню при вызове из header
                if (e.target == 'header') {
                    // e.items can be undefined
                    if (!e.items) e.items = [];

                    // Add a custom menu item
                    e.items.push({
                        text: 'Log Column Caption',
                        onItemClick: () => {
                            console.log(e.column.caption);
                        }
                    });
                }
                if (e.target == 'content') {
                    // e.items can be undefined
                    if (!e.items) e.items = [];
                    console.log(e);
                    // Add a custom menu item
                    e.items.push({
                        text: 'Перейти к категориям',
                        onItemClick: () => {
                            //  console.log(e.row);
                            location.href = route('CategoryShow', e.row.key.id);
                        }
                    });
                }
            }
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
