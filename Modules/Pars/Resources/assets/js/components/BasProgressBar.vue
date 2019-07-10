<template>
    <div class="form_">
        <dx-button
                id="progress-button"
                :text="buttonText"
                :width="200"
                :on-click="onButtonClick"
        />
        <div class="progress-info">
            Time left {{ seconds | time }}
        </div>
        <dx-progress-bar
                id="progress-bar-status"
                :class="{complete: seconds == 0}"
                :min="0"
                :max="maxValue"
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

    const maxValue = 60;

    function statusFormat(value) {
        return `Loading: ${ value * 100 }%`;
    }

    export default {
        name: "BasProgressBar",
        props: {propsMaxValue: Number},
        components: {
            DxButton,
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
                seconds: maxValue,
                buttonText: 'Start progress',
                inProgress: false,
                statusFormat
            };
        },
        computed: {
            progressValue() {
                return maxValue - this.seconds;
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
            initp(){
                console.log('initp');
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

                    this.intervalId = setInterval(() => this.timer(), 1000);
                }
                this.inProgress = !this.inProgress;
            },
            timer() {
                this.seconds = this.seconds - 1;
                if (this.seconds == 0) {
                    this.buttonText = 'Restart progress';
                    this.inProgress = !this.inProgress;
                    clearInterval(this.intervalId);
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