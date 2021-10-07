<template>
    <div class="h-15">
        <input type="text"
            class="form-control"
            id="message"
            placeholder="[Enter] для отправки"
            @keydown="keydown"
            v-model="body"
        >
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: null
            }
        },
        methods: {
            keydown(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.sendMessage();
                }
            },
            sendMessage() {
                if(!this.body || this.body.trim() === '') {
                    return
                }
                axios.post('/message', { body: this.body });
                this.body = null;
            }
        }
    }
</script>
