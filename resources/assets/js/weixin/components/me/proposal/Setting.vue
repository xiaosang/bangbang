<template>
    <div class="release">
       
        
        <group>
            <x-input title='选择学校'  :show-clear="false" text-align="right"  placeholder="选择学校" disabled @click.native="selSchool"></x-input>
        </group>
        <div v-transfer-dom>
            <popup v-model="addressOnOff">
                <div class="popup1">
                    <group>
                        <x-input v-model="school_select_one" :show-clear="false" text-align="left"  placeholder="选择学校" readonly>
                            <x-button mini type="primary" slot="right" @click.native="school_enter">确认</x-button>
                        </x-input>
                    </group>
                    <p class="prompt">选择学校</p>
                    <group>
                        <picker :data='school_all' v-model='school_select' @on-change='select_school'></picker>
                    </group>
                </div>
            </popup>
        </div>


    </div>
</template>

<script>
    import { XHeader ,XInput,Group , XTextarea , Checker , CheckerItem , Datetime , XSwitch ,XAddress , TransferDom , Popup ,XButton , Picker } from 'vux'
    

    export default {
        directives: {
            TransferDom
        },
        components: {
            XHeader,
            XInput,
            Group,
            XTextarea,
            Checker,
            CheckerItem,
            Datetime,
            XSwitch,
            XAddress,
            Popup,
            XButton,
            Picker
        },
        data(){
            return {
                addressOnOff: false,
                // school : [],
                inputSchool : '',
                school_select: [],
                school_select_one:'',
                school_all: [
                //    [//历史地址   个人中心希望有添加删除
                //        {
                //            name: '快速选择',
                //            value: 1
                //        },
                //        {
                //            name: '河南科技学院',
                //            value: 2
                //        },
                //        {
                //            name: '河南师范大学',
                //            value: 3
                //        }
                //    ]
                ],


            }
        },
        methods:{
            selSchool : function(){
                axios.post('/wx/set/school').then(res => {
                    this.school_all.push(res.data.result)
                    this.addressOnOff = true
                }).catch(err => {
                    this.$message('网络错误')
                })

                

            },
            select_school: function(value){
                // console.log(value,this.school_select)
                for(let i=0;i<this.school_all[0].length;i++){
                    // console.log(parseInt(this.school_all[0][i].value)==parseInt(vaule[0]),parseInt(vaule[0])==parseInt(this.school_all[0][i].value))
                    if(parseInt(this.school_all[0][i].value)==parseInt(value[0])){
                        // console.log(this.school_all[0])
                        this.school_select_one = this.school_all[0][i].name
                        break;
                    }
                    
                }
                
            },
            school_enter:function(){

            }
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
    .prompt{
        opacity: 0.2;
        font-size: 12px;
        text-align: center;
        margin-top: .4em
    }
    #release-text{
        padding: 2px 12px;
        background: rgb(241,241,241);
        /*background: #ff4a00;*/
        /*color: #fff;*/
        border-radius:5%;
        font-size: 18px;
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
