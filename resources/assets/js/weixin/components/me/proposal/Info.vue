<template>
  <div>
        <group title="个人信息">
            <x-input title="姓名" name="username" v-model="studentName" readonly></x-input>
            <x-input title="学号" name="usernum" v-model="studentNum" readonly></x-input>
            <x-input title="性别" name="usersex" v-model="studentSex" readonly></x-input>
            <x-input title="身份证号" name="usercode" v-model="studentCode" readonly></x-input>
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
            }
        },
        methods:{
            getInfo: function(){
                
                axios.post('/wx/set/get_info',{'school_id':this.$route.params.id}).then(res => {
                    // this.$vux.toast.text(res.data.result)
                    
                    var info = res.data.result
                    console.log(info)
                    this.studentName = info.name
                    this.studentNum = info.student_num
                    this.studentCode = info.code
                    this.studentDep = info.department
                    this.studentClass = info.student_class
                    this.studentSex = info.sex
                    
                }).catch(err => {
                    this.$vux.toast.text('网络异常!', 'top')
                })
            }
        },
        mounted() {
            this.getInfo()
        }
    }
</script>
