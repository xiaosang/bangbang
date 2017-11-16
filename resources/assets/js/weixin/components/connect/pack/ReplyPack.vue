<template>
	<div>
		<div class="panel" v-if="item">
			<div class="msg-list">
				<div class="msg-item">
					<div class="msg-author">
						<div class="author-avatar">
							<!-- <img :src="item.author" alt=" "> -->
							<span class="reply_person">
								回复帖子<a  @click="$router.push('/note/detail/'+item.note_id)" class="author_name"><span class="scolor">：</span>{{ item.title }}</a>
								用户<a  class="author_name"><span class="scolor">：</span>{{ item.author }}</a>
				                        		</span>
						</div>
						<span class="comment-res" v-on:click="deleteMsg">
			                                		<i slot="icon" class="ion-ios-close-empty"></i>
			                        		</span>
					</div>
					<div class="reply_topic">
						时间<span class="reply_time">：{{ item.time }}</span>
					</div>
					<div class="reply-contain">
						<span class="reply-lab">内容</span>
						<p class="reply_content"><span class="scolor">：</span>{{item.content}}</p>
					</div>

	      			</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['item'],
		methods: {
		    deleteMsg: function () {
		     	this.$emit('increment')
		    }
		},
	}
</script>

<style lang="less" scoped>
.scolor{
	color: #969696
}
.msg-list {
	margin-bottom: 5px;
	background-color: #fff;
	.msg-item {
		font-size: 14px;
		padding: 14px 15px 10px;
		border-bottom: 1px #e0e0e0 solid;
	}
	.msg-author {
		.author-avatar {
			margin-right: 5px;
			display: inline-block;
		}
		.comment-res{
			float: right;
		    	i{
				display: inline-block;
			    	transform: scale(1.2);
			    	cursor: pointer;
			}
			i:hover{
			    	color: #e63030;
			}
		}
		.reply_person{
			color: #969696;
			padding: 8px 0 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			.author_name, .reply_time {
				padding-right: 5px;
			}
			.author_name{color: #4b84af}
			.reply_time{
				color: #969696;
			}
		}
	}
	.reply_topic {
		color: #969696;
		font-size: 14px;
		padding: 8px 0 0;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	    	a {
	    		color: black;
	    	}
	}
	.reply-contain{
		padding: 8px 0 0 0;
		.reply-lab{
			color: #969696;
			float: left;
			display: inline-block;
		}
		.reply_content {
			color: #969696;
			margin-left: 28px;
			border-radius: 5px;
		       	word-break: break-word;
		}
	}
}
</style>