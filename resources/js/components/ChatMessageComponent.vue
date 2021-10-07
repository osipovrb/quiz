<template>
    <div class="message">
        <p>
            <small class="text-muted">{{ message.created_at }}</small>
            <strong>{{ message.user.name }}</strong>:
            <span class="body">{{ message.body }}</span>
        </p>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>
