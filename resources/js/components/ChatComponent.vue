<template>
    <div class="col-md-9 h-100">
        <div class="h-85 mb-4">
            <chat-messages-component></chat-messages-component>
        </div>
        <div class="h-15">
            <chat-form-component></chat-form-component>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

