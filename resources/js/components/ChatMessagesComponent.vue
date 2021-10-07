<template>
    <div class="card h-100 messages" @scroll="scrollHandler" >
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
                messages: [],
                scrolledToBottom: true,
            }
        },
        updated() {
            if (this.scrolledToBottom) {
                this.scroll();
            }
        },
        mounted() {
            this.scroll();
            axios.get('/messages').then((response) => {
                this.messages = response.data.reverse();
            });
            Event.$on('message.created', (message) => {
                this.messages.push(message);
            });
        },
        methods: {
            'scroll': function () {
                const messagesContainer = document.querySelector('div.messages');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            },
            'scrollHandler': function (el) {
                this.scrolledToBottom = (el.target.offsetHeight + el.target.scrollTop) >= el.target.scrollHeight;
            },

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
