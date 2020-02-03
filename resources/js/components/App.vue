<template>
    <div>
        <h1>Vue Home Pages </h1>

        <p>
            <router-link :to="{ name: 'home' }">Homew</router-link> |
            <router-link :to="{ name: 'about' }">About ssUs</router-link>
        </p>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {}
</script>
