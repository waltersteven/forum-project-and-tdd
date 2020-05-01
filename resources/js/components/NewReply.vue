<template>
	<div>
		<div v-if="signedIn">
			<div class="form-group">
				<textarea
					name="body"
					id="body"
					class="form-control"
					placeholder="Have something to say?"
					cols="30"
					rows="5"
					required
					v-model="body"
				></textarea>
			</div>

			<button type="submit" class="btn btn-primary" @click="addReply">Post</button>
		</div>

		<p class="text-center" v-else>
			Please
			<a href="/login">sign in</a> to participate in this discussion.
		</p>
	</div>
</template>

<script>
import Tribute from "tributejs";

function fetchUsers(text, cb) {
	console.log("called fetchusers");
	axios
		.get("/api/users", {
			params: {
				name: text
			}
		})
		.then(response => {
			console.log("response :", response);

			var values = [];

			response.data.forEach(element => {
				let userObj = { value: element };
				values.push(userObj);
			});

			cb(values);
		})
		.catch(error => {
			console.log("error :", error);
			cb([]);
		});
}
export default {
	data() {
		return {
			body: ""
		};
	},

	methods: {
		addReply() {
			axios
				.post(location.pathname + "/replies", { body: this.body })
				.then(({ data }) => {
					this.body = "";

					flash("Your reply has been posted.");

					this.$emit("created", data);
				})
				.catch(error => {
					flash(error.response.data, "danger");
				});
		}
	},

	mounted() {
		var tribute = new Tribute({
			collection: [
				{
					values: function(text, cb) {
						fetchUsers(text, users => cb(users));
					},

					lookup: "value"
				}
			]
		});

		tribute.attach(document.getElementById("body"));
	}
};
</script>

<style>
</style>
