<template>
    <div class="timeline-demo">
        <div style="margin:20px;" v-for="item,index in announcement_list" :key="index">
                <div style="height: 8px;background: url('img/wx/announcementTop.png') no-repeat;background-size:100% 8px;"></div>
                <h4 style="background: url('img/wx/announcementMiddle.png');padding: 20px;">{{ item.content }}</h4>
                <!--<br>-->
                <div class="team" style="background: url('img/wx/announcementMiddle.png');">
                    <p>帮帮团队</p><br>
                    <p>{{ item.create_time | date}}</p>
                </div>
                <div style="height: 55px;background: url('img/wx/announcementBottom.png') no-repeat;background-size:100% 100%;"></div>
        </div>
        <!--<timeline>
            <timeline-item>
                <div>
                    <p>2016-04-17 12:00:00</p>
                    <h4>商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件商家正在通知快递公司揽件</h4>
                </div>
            </timeline-item>
            <timeline-item>
                <h4> 申通快递员 广东广州 收件员 xxx 已揽件</h4>
                <p>2016-04-16 10:23:00</p>
            </timeline-item>
            <timeline-item>
                <h4> 商家正在通知快递公司揽件</h4>
                <p>2016-04-15 9:00:00</p>
            </timeline-item>
        </timeline>-->
    </div>
</template>

<script>
    import { Timeline, TimelineItem } from 'vux'
    export default {
        components: {
            Timeline,
            TimelineItem
        },
        data(){
            return {
                announcement_list:[]
            }
        },
        methods:{
            get_announcement_list(){
                axios.get('/wx/announcement/get_announcement_list')
                    .then((res)=>{
                        this.announcement_list = res.data.result
                        this.$vux.loading.hide()
                    })
                    .catch(()=>{

                    })
            }
        },
        watch:{

        },
        mounted() {
            this.$vux.loading.show({
                text: 'Loading'
            })
            this.get_announcement_list()
        }
    }
</script>

<style scoped lang="less">
    .timeline-demo {
        p {
            color: #fff;
            font-size: 0.8rem;
            width:150px;
            text-align: center;
            float: right;
        }
        h4 {
            color: #fff;
            font-weight: normal;
            text-indent:2em;
            font-size: 14px;
            line-height: 1.5;
            min-height:40px;
        }
        .team {
            text-align: right;
            overflow: hidden;
        }
    }
</style>