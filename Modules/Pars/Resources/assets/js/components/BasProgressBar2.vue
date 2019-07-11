<template>
    <div class="form_">
        <!--<dx-button-->
                <!--id="progress-button"-->
                <!--:text="buttonText"-->
                <!--:width="200"-->
                <!--:on-click="onButtonClick"-->
        <!--/>-->
        <div class="dx-fieldset-header" width="90%">Категория: {{categoryName}}[{{categoryId}}]. Кол-во товаров:{{categoryCurrCnt}} из {{categoryMaxCnt}}</div>
        <div class="progress-info">
            Time left {{ seconds | time }}
        </div>

        <dx-progress-bar
                id="progress-bar-status"
                :class="{complete: seconds == 0}"
                :min="0"
                :max="categoryMaxCnt"
                :status-format="statusFormat"
                :value="progressValue"
                width="90%"
                :on-initialized="initp"
        />
    </div>
</template>
<script>

    import {DxButton} from 'devextreme-vue/button';
    import {DxProgressBar} from 'devextreme-vue/progress-bar';
    import {DxTextBox} from 'devextreme-vue';

    const maxValue = 60;

    function statusFormat(value) {
        return `Loading: ${ value * 100 }%`;
    }

    export default {
        name: "BasProgressBar",
        props: {
            propsName: String,
            propsCategoryId: Number,
            propsMaxCnt: Number
        },
        components: {
            DxButton,
            DxTextBox,
            DxProgressBar
        },
        filters: {
            time(value) {
                return `00:00:${ (`0${ value}`).slice(-2)}`;
            }
        },
        data() {
            return {
                maxValue,
                seconds: 0,
                buttonText: 'Start progress',
                categoryName: this.propsName,
                categoryId: this.propsCategoryId,
                categoryMaxCnt: this.propsMaxCnt,
                categoryCurrCnt:0,
                inProgress: false,
                statusFormat
            };
        },
        computed: {
            progressValue() {
                return this.categoryCurrCnt;
            }
        },
        created() {
            console.log('created');
        },
        mounted() {

            console.log(this.$el.itextContent) // I'm text inside the component.
            this.onButtonClick();
        },
        methods: {

            initp() {
                console.log('initp');
            },
           async  getCurrerentCnt(){
               this.categoryCurrCnt = await   axios.get(`http://datascrap.bas/pars/api/products_category_cnt/`+this.categoryId).then(response => {
                    return response.data
                });
            },

            onButtonClick() {
                this.maxValue = this.propsMaxValue;
                if (this.inProgress) {
                    this.buttonText = 'Continue progress';
                    clearInterval(this.intervalId);
                } else {
                    this.buttonText = 'Stop progress';

                    if (this.seconds === 0) {
                        this.seconds = maxValue;
                    }

                    this.intervalId = setInterval(() => this.timer(), 5000);
                }
                this.inProgress = !this.inProgress;
            },
            timer() {
                this.seconds = this.seconds + 5;
                if (this.categoryCurrCnt==this.categoryMaxCnt){

                    this.buttonText = 'Restart progress';
                    this.inProgress = !this.inProgress;
                    clearInterval(this.intervalId);
                } else {
                   this.getCurrerentCnt();
                }
            }
        },
    };
</script>
<style scoped>
    .form {
        padding: 20% 0;
        text-align: center;
    }

    #progress-bar-status {
        display: inline-block;
    }

    .complete .dx-progressbar-range {
        background-color: green;
    }
</style>
