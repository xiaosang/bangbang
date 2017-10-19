<!-- 发表帖子记录的显示块 -->
<template>
	<div class="m-piece">
		<div class="m-cell-media-wrap">
			<div class="m-cell-media-top">
				<span v-if="label" class="label">{{label}}</span>
				<span> ·最新动态</span>
				<span v-if="time" class="top-time">{{time}}</span>
			</div>
			<div class="m-cell-media-middle">
				<div class="m-cell-media">
					<div class="m-cell-title m-ellipsis-2">
						<slot name="title"></slot>
					</div>
					<div class="m-cell-media-bottom">
						<span v-if="read>=0">
							<i slot="icon" class="ion-ios-glasses"></i>
							<span>{{read}}</span>
						</span>
						<span v-if="comment>=0">
							<i slot="icon" class="ion-chatbox-working"></i>
							<span>{{comment}}</span>
						</span>
						<span v-if="createTime">
							<i slot="icon" class="ion-calendar"></i>
							<span>{{createTime}}</span>
						</span>
					</div>
				</div>

			</div>

		</div>
	</div>
</template>

<script>
	export default {
		props: ['author', 'time','read','comment','label','createTime']
	}
</script>
<style lang="less">
	.m-piece{
		background-color: white;
		border-bottom: 1px solid #eff2f7;
	}
	a{text-decoration:none;}
	.m-cell-media-wrap {
		display: flex;
		font-size: 14px;
		flex-direction: column;
		padding: 15px 20px;
		position: relative;
		&:after {
			content: '';
			position: absolute;
			left: 0;
			bottom: 0;
			right: 0;
			height: 1px;
			background: #eee;
			transform: scaleY(0.5);
		}
		.m-cell-media-top{
			font-size: 12px;
			.label{color: #20a0ff}
		}
		.m-cell-media-middle {
			display: flex;
			flex-direction: row;
			.m-cell-media {
				flex: 1;
			}
			.m-cell-title {
				font-size: 17px;
				line-height: 22px;
				color: #333;
				font-weight: bold;
				p{padding: 10px 60px 10px 0;}
			}
			.m-pull-right {
				width: 94px;
				height: 94px;
				overflow: hidden;
				background-position: center center;
				background-size: cover;
				img {
					width: 100%;
				}
			}
		}
		.m-cell-media-bottom {
			display: flex;
			justify-content: left;
			color: #888585;
			position: relative;
			span{
				padding: 0 5px;
				i{
					transform: scale(1.2);
					display: inline-block;
				}
				span{color: #3a3939};
			}
		}
	}

</style>