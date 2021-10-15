<template>
    <li class="list-group-item">
        <span>{{ user.name }}</span>
        <span class="float-right text-muted">{{ user.score }}</span>
    </li>
</template>

<script>
    export default {
        props: ['user']
    }
</script>
