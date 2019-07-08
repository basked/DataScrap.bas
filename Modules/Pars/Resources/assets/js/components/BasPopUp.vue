<!--suppress ALL -->
<template>
    <div id="popup">
        <dx-popup
                :visible="popupVisible"
                :drag-enabled="false"
                :close-on-outside-click="true"
                :show-title="true"
                :width="300"
                :height="250"
                class="popup"
                title="Information"

        >
        </dx-popup>
    </div>
</template>

<script>
    import {DxPopup} from 'devextreme-vue/popup';
    import {DxButton} from 'devextreme-vue/button';

    export default {
        name: "BasPopUp",
        props: {
            showInfo: {
                type: Function,
                required: true,
                default: () => {
                }
            }
        },
        components: {DxPopup, DxButton},
        data() {
            return {
                popupVisible : false
            }
        },
        methods: {
            showEmployeeInfo() {
                // console.log( this.popupVisible);
                // console.log( this.shop);
                this.showInfo();
            }
        }
    }
</script>

<style scoped>

</style>