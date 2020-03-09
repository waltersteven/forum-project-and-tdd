<template>
	<li class="nav-item dropdown" v-if="notifications.length">
		<a
			class="nav-link dropdown-toggle"
			href="#"
			id="navbarDropdown"
			role="button"
			data-toggle="dropdown"
			aria-haspopup="true"
			aria-expanded="false"
		>
			<font-awesome-icon icon="bell" />
		</a>
		<div
			class="dropdown-menu"
			aria-labelledby="navbarDropdown"
			v-for="notification in notifications"
			v-bind:key="notification.id"
		>
			<a
				class="dropdown-item"
				:href="notification.data.link"
				v-text="notification.data.message"
				@click="markAsRead(notification)"
			></a>
		</div>
	</li>
</template>

<script>
export default {
	data() {
		return {
			notifications: []
		};
	},

	created() {
		axios
			.get("/profiles/" + window.App.user.name + "/notifications")
			.then(response => {
				this.notifications = response.data;
			})
			.catch(err => {});
	},

	methods: {
		markAsRead(notification) {
			axios.delete(
				"profiles/" +
					window.App.user.name +
					"/notifications/" +
					notification.id
			);
		}
	}
};
</script>

<style>
</style>