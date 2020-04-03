<template>
	<div :id="'reply' + id" class="card mt-4">
		<div class="card-header">
			<div class="level">
				<h5 class="flex">
					<a :href="'/profiles/' + data.owner.name " v-text="data.owner.name"></a>
					said
					<span v-text="ago"></span>
				</h5>

				<div v-if="signedIn">
					<favorite :reply="data"></favorite>
					<!-- {{-- <form method="POST" action="/replies/{{ $reply->id }}/favorites">
						@csrf
						<button type="submit" class="btn btn-outline-secondary" {{ $reply->isFavorited() ? 'disabled' : ''}}>
							{{ $reply->favorites_count }} {{ Str::plural('Favorite', $reply->favorites_count )}}
						</button>
					</form> --}}-->
				</div>
			</div>
		</div>
		<div class="card-body">
			<div v-if="editing">
				<form @submit.prevent="update">
					<div class="form-group">
						<textarea class="form-control" v-model="body" required></textarea>
					</div>

					<button class="btn btn-xs btn-primary">Update</button>
					<button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
				</form>
			</div>

			<div v-else v-html="body"></div>
		</div>

		<!-- @can('update', $reply) -->
		<div class="card-footer level" v-if="canUpdate">
			<button class="btn btn-secondary btn-xs mr-1" @click="editing = true">Edit</button>

			<button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>

			<!-- {{-- <form action="/replies/{{$reply->id}}" method="post">
					@csrf
					@method('delete')
					
					<button type="submit" class="btn btn-danger btn-xs">Delete</button>
			</form> --}}-->
		</div>
		<!-- @endcan -->
	</div>
</template>

<script>
import Favorite from "./Favorite";
import moment from "moment";

export default {
	props: ["data"],

	components: { Favorite },

	data() {
		return {
			editing: false,
			body: this.data.body,
			id: this.data.id
		};
	},

	computed: {
		ago() {
			return moment(this.data.created_at).fromNow() + "...";
		},

		signedIn() {
			return window.App.signedIn;
		},

		canUpdate() {
			// return this.data.user_id == window.App.user.id;
			return this.authorize(user => this.data.user_id == user.id);
		}
	},

	methods: {
		update() {
			axios
				.patch("/replies/" + this.data.id, {
					body: this.body
				})
				.then(() => {
					this.editing = false;

					flash("Updated!");
				})
				.catch(error => {
					flash(error.response.data, "danger");
				});
		},

		destroy() {
			axios.delete("/replies/" + this.data.id);

			this.$emit("deleted", this.data.id);

			// $(this.$el).fadeOut(300, () =>
			//     flash("The reply has been deleted!")
			// );
		}
	}
};
</script>