<template lang="pug">
v-container(fluid, grid-list-lg style={backgroundColor: 'gray'})
    v-layout
        v-flex
            v-card.white--text
                v-card-title Disponibles
                <div v-for="n,i in 4" :key="i">usuario{{n}}</div>  
</template>

<script>
export default {
}
</script>

<style lang="css">
</style>
