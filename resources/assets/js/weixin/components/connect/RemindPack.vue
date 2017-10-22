<template>
	<div>
		<div class="panel" v-if="item">
			<div class="msg-list">
				<div class="msg-item">
					<div class="msg-author">
						<div class="author-avatar">
							<span class="reply_person">回复人：
								<a  class="author_name">{{ item.author }}</a>
								<span class="reply_time">时间:{{ item.time }}</span>
				                        		</span>
						</div>
						<span class="comment-res" v-on:click="submitMsg">
			                                		<!-- <i slot="icon" class="ion-ios-close-empty"></i> -->
			                                		回复
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
		    submitMsg: function () {
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
		font-size: 12px;
		margin: 14px 0 10px 20px;
		border-bottom: 1px #e0e0e0 solid;
	}
	.msg-author {
		font-size: 12px;
		.author-avatar {
			margin-right: 5px;
			display: inline-block;
		}
		.comment-res{
			color: #969696;
		    	position:absolute;
		    	right: 20px;
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
				padding-left: 5px;
			}
			.author_name{color: black}
			.reply_time{
				color: #969696;
			}
		}
	}
	.reply_topic {
		color: #969696;
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
		display: inline-block;
		padding: 3px 0;
	}
	.reply_content {
		padding: 3px;
		color: #969696;
		line-height: 1.7;
		margin-top: 5px;
		margin-left: 40px;
		border-radius: 5px;
	       	word-break: break-word;
	}
}
</style>