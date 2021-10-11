<template>
    <div class="message">
        <p>
            <small class="text-muted">{{ message.created_at }}</small>
            <strong v-if="userIsNull">Бот: </strong>
            <strong v-else>{{ message.user.name }}: </strong>
            <span class="body">{{ message.body }}</span>
        </p>
    </div>
</template>

<script>
    export default {
        props: [
            'message'
        ],
        computed: {
            userIsNull: function() {
                return (! this.message.user) ? true : false;
            }
        }
    }
</script>
