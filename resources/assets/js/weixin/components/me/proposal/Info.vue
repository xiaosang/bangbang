<template>
  <div>
      <div style="position: fixed;width: 100%">
          <x-header :left-options="{backText: ''}">身份认证</x-header>
      </div>
        <group style="padding-top: 47px" title="个人信息">
            <x-input title="姓名" name="username" v-model="studentName" readonly></x-input>
            <x-input title="学号" name="usernum" v-model="studentNum" readonly></x-input>
            <x-input title="性别" name="usersex" v-model="studentSex" readonly></x-input>
            <x-input title="身份证号" name="usercode" v-model="studentCode" readonly @click.native="show_code"></x-input>
            <x-input title="院系" name="userdep" v-model="studentDep" readonly></x-input>
            <x-input title="班级" name="userclass" v-model="studentClass" readonly></x-input>
        </group>
  </div>
</template>


<script>

import { XHeader ,XImg,XInput,Group , ToastPlugin,XTextarea , Checker , CheckerItem , Datetime , XSwitch ,XAddress , TransferDom , Popup ,XButton , Picker } from 'vux'
    
    Vue.use(ToastPlugin)

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
            Picker,
            XImg,
            ToastPlugin,
        },
        data(){
            return {
                studentName: '',
                studentSex: '',
                studentNum: '',
                studentCode: '',
                studentDep: '',
                studentClass: '',
                info :[],
                isShow : false,
            }
        },
        methods:{
            getInfo: function(){
                
                axios.post('/wx/set/get_info').then(res => {
                    // this.$vux.toast.text(res.data.result)
                    
                    this.info = res.data.result
                    // console.log(info)
                    this.studentName = this.info.name
                    this.studentNum = this.info.student_num
                    this.studentCode = this.info.code.substr(0, 3) + '***********' + this.info.code.substr(14)
                    this.studentDep = this.info.department
                    this.studentClass = this.info.student_class
                    this.studentSex = this.info.sex
                    this.$vux.loading.hide();
                }).catch(err => {
                    this.$vux.toast.text('网络异常!', 'top')
                })
            },
            show_code:function(){
                // console.log('.....')
                if(this.isShow){
                    this.studentCode = this.info.code.substr(0, 3) + '***********' + this.info.code.substr(14)
                    this.isShow = !this.isShow
                }else{
                    this.studentCode = this.info.code
                    this.isShow = !this.isShow
                }
                
            }
        },
        mounted() {
            this.$vux.loading.show({
                  text: '加载中...'
            });
            this.getInfo()
        }
    }
</script>
