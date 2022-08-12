<template>
    <div>
        <router-link :to="{name: 'index'}">Posts</router-link>
        <router-link :to="{name: 'store'}">Create</router-link>
        <router-view></router-view>
    </div>
</template>

<script>

export default {
    name: "Index",
}
</script>

<style scoped>

</style>
