<template>
	<div class="event">
		<h3>{{ event.title }}</h3>
		<slot></slot>
		<details class="event-detail">
			<summary>詳細</summary>
			<div class="discription" v-html="event.description"></div>
		</details>
	</div>
</template>

<script>
export default {
	props: [ 'event' ]
}
</script>

<style scoped>
	.event {
		border: solid 1px #2c3e50;
	}

	.event-detail {
		padding: 10px
	}

	.discription {
		height: 400px;
		overflow: scroll;
	}
</style>