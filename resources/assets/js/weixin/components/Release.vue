<template>
    <div class="release">
        <x-header :left-options="{ showBack:false,backText:'' }">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>
                <span style="font-size: 18px;">新建任务</span>
            </span>
            <span slot="right" style="padding: 2px 6px;background: rgb(241,241,241);border-radius:5%;font-size: 18px;">发布</span>
        </x-header>

        <group>
            <x-input title='标题' :max="15" @on-change="change" v-model="name" placeholder="请输入任务的名称，不超过十五字" :show-clear="false"></x-input>
        </group>

        <group>
            <x-textarea  v-model="content" placeholder="输入任务描述..." :max="300" :rows="6" ></x-textarea>
        </group>

        <group>
            <x-input title="任务类型" disabled >
                <checker
                        v-model="type"
                        default-item-class="demo5-item"
                        selected-item-class="demo5-item-selected"
                        slot="right"
                        :radio-required="true"
                >
                    <checker-item value="1">无偿</checker-item>
                    <checker-item value="0">有偿</checker-item>
                </checker>
            </x-input>

        </group>

        <group>
            <datetime v-model="complete_time" format="YYYY年MM月DD日 HH:mm"  title="截止时间" placeholder="设置任务完成时间"></datetime>
            <datetime v-model="expected_time" format="YYYY年MM月DD日 HH:mm"  title="撤回时间" placeholder="设置任务撤回时间"></datetime>
        </group>

        <group>
            <x-switch title="开启匿名" :v-model="is_hide"></x-switch>
        </group>


    </div>
</template>

<script>
    import { XHeader ,XInput,Group , XTextarea , Checker , CheckerItem , Datetime , XSwitch } from 'vux'
    export default {
        components: {
            XHeader,
            XInput,
            Group,
            XTextarea,
            Checker,
            CheckerItem,
            Datetime,
            XSwitch
        },
        data(){
            return {
                name: '',
                content:'',
                type:'1',
                complete_time:'',
                expected_time:'',
                is_hide:'true'
            }
        },
        methods:{
            change (val) {
                console.log('on change', val)
            },

        },
        mounted() {

        }
    }
</script>

<style scoped>
    .demo5-item {
        width: 80px;
        text-align: center;
        border-radius: 3px;
        border: 1px solid #ccc;
        background-color: #fff;
        margin-right: 6px;
    }
    .demo5-item-selected {
        background: #ffffff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAMAAACecocUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTZDOEJBQ0E3NkIxMTFFNEE3MzJFOUJCMEU5QUM0QkIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTZDOEJBQ0I3NkIxMTFFNEE3MzJFOUJCMEU5QUM0QkIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBNkM4QkFDODc2QjExMUU0QTczMkU5QkIwRTlBQzRCQiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBNkM4QkFDOTc2QjExMUU0QTczMkU5QkIwRTlBQzRCQiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnMGp3kAAAAJUExURf9KAP///////4Jqdw0AAAADdFJOU///ANfKDUEAAAAuSURBVHjaTMpBDgAABAPB5f+PlhLUpZMWuQcYMWLEyDN4ymqa5KS4+3G+KAEGACQmAGlKzr56AAAAAElFTkSuQmCC) no-repeat right bottom;
        border-color: #ff4a00;
        color: #ff4a00;
    }
</style>

<style>
    .release .weui-cells{
        margin-top:.4em;
    }
    .release .weui-switch:checked, .release .weui-switch-cp__input:checked ~ .release .weui-switch-cp__box {
        border-color: #ff4a00;
        background-color: #ff4a00;
    }
</style>
