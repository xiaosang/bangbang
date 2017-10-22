<template>
	<div>
		<div class="panel" v-if="item">
			<div class="msg-list">
				<div class="msg-item">
					<div class="msg-author">
						<div class="author-avatar">
							<!-- <img :src="item.author" alt=" "> -->
							<span class="reply_person">被回复:
								<a  class="author_name">{{ item.author }}</a>
								<span class="reply_time">回复时间:{{ item.time }}</span>
				                        		</span>
						</div>
						<span class="comment-res" v-on:click="deleteMsg">
			                                		<i slot="icon" class="ion-ios-close-empty"></i>
			                        		</span>
					</div>
					<div class="reply_topic">
						帖子：<a  @click="$router.push('/note/detail/'+item.note_id)">{{ item.title }}</a>
					</div>
					<div>
						<span class="reply-lab">内容：</span>
						<p class="reply_content">{{item.content}}</p>
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
.msg-list {
	margin-bottom: 5px;
	background-color: #fff;
	.msg-item {
		padding: 14px 15px 10px;
		border-bottom: 1px #e0e0e0 solid;
	}
	.msg-author {
		font-size: 14px;
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
			font-size: 12px;
			padding: 8px 0 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			.author_name, .reply_time {
				padding-right: 5px;
			}
			.author_name{color: black}
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
	.reply-lab{
		color: #969696;
		float: left;
		font-size: 14px;
		display: inline-block;
		padding: 3px 0;
	}
	.reply_content {
		padding: 3px;
		font-size: 14px;
		color: #484848;
		line-height: 1.7;
		margin-top: 5px;
		margin-left: 40px;
		border-radius: 5px;
	       	word-break: break-word;
	}
}
</style>