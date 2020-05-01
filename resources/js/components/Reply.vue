<template>
    <div :id="'reply' + id" class="card mt-4">
        <div class="card-header" :class="isBest ? 'bg-success' : 'bg-light'">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name " v-text="reply.owner.name"></a>
                    said
                    <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
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
        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-secondary btn-xs mr-1" @click="editing = true">Edit</button>

                <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-dark btn-xs ml-auto" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?</button>

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
        props: ["reply"],

        components: {Favorite},

        data() {
            return {
                editing: false,
                body: this.reply.body,
                id: this.reply.id,
                isBest: this.reply.isBest,
            };
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + "...";
            }

            // canUpdate() {
            //     // return this.reply.user_id == window.App.user.id;
            //     return this.authorize(user => this.reply.user_id == user.id);
            // }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = id === this.id;
            })
        },

        methods: {
            update() {
                axios
                    .patch("/replies/" + this.id, {
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
                axios.delete("/replies/" + this.id);

                this.$emit("deleted", this.id);

                // $(this.$el).fadeOut(300, () =>
                //     flash("The reply has been deleted!")
                // );
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    };
</script>
