<template>
    <div class="col-md-3 h-100">
        <div class="card">
            <div class="card-header">
                <span>Онлайн</span>
                <span class="float-right text-muted">Очки</span>
            </div>
            <ul class="list-group list-group-flush">
                <user-component
                    v-for="user in sortedUsers"
                    :key="user.id"
                    :user="user">
                </user-component>
            </ul>
        </div>
    </div>
</template>

<script>
import Event from '../event.js';

export default {

    data() {
        return {
            users: []
        }
    },

    mounted() {
        Event.$on('users.here', (users) => {
            this.users = users;
        })
        .$on('users.joined', (user) => {
            this.users.unshift(user);
        })
        .$on('users.left', (user) => {
            this.users = this.users.filter(u => {
                return u.id != user.id;
            });
        })
        .$on('users.score', (user) => {
            this.users.find((u) => u.id === user.id).score = user.score;
        })
    },

    computed: {
        sortedUsers: function() {
            return this.users.sort((a, b) => a.score > b.score);
        }
    }

}
</script>
