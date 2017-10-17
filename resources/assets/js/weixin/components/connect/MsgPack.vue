<template>
	<div>
		<div class="panel" v-if="item">
			<div class="msg-list">
				<div class="msg-item">
					<div class="msg-author">
						<div class="author-avatar">
							<img :src="item.author" alt=" ">
							<a  class="author_name">{{ item.author }}</a>
							<span class="reply_time">回复时间:{{ item.time }}</span>
						</div>
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
		props: ['item']
	}
</script>

<style lang="less" scoped>
.msg-list {
	margin-bottom: 5px;
	background-color: #fff;
	.msg-item {
		padding: 15px 10px;
		border-bottom: 1px #e0e0e0 solid;
	}
	.msg-author {
		font-size: 14px;
		.author-avatar {
			margin-right: 5px;
			display: inline-block;
			img {
				border: none;
			    	width: 34px;
			    	height: 34px;
			    	border-radius: 17px;
			    	display: inline-block;
			    	vertical-align: text-top;
			    	background-color: black;
			}
		}
		.author_name, .reply_time {
			vertical-align: sub;
			padding-left: 5px;
		}
		.reply_time{
			color: #969696;
		}
	}
	.reply_topic {
		color: #969696;
		font-size: 14px;
		padding: 8px 0;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	    	a {
	    		color: #06c;
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
		background: #d4d4d4;
		border-radius: 5px;
	       word-break: break-word;
	}
}
</style>