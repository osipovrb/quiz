<template>
    <div class="card h-100 messages">
        <chat-message-component
            v-for="message in messages"
            :key="message.id"
            :message="message">
        </chat-message-component>
    </div>
</template>

<script>
    import Event from '../event.js';

    export default {
        data() {
            return {
                messages: []
            }
        },
        mounted() {
            axios.get('/messages').then((response) => {
                this.messages = response.data.reverse();
            });
            Event.$on('message.created', (message) => {
                this.messages.push(message);
            });
        }
    }
</script>

<style>
    .messages {
        overflow-y: scroll;
        max-height: 85vh;
        padding: 10px;
    }
</style>
