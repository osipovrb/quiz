<template>
    <li class="list-group-item">
        <span>{{ user.name }}</span>
        <span class="float-right text-muted">0</span>
    </li>
</template>

<script>
    export default {
        props: ['user']
    }
</script>
