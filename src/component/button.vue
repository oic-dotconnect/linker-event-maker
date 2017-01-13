<template>
	<button class="button" @click="onClick" style="{ background-color: color }">
		<slot></slot>
	</button>
</template>

<script>
export default {
	props: {
		onClick: {
			type: Function,
			default: () => {
				return () => {
					console.log('click');				
				}
			}
		},
		color: {}
	}
}
</script>