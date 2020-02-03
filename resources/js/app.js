import Vue from 'vue'
import VueRouter from 'vue-router'


Vue.use(VueRouter);


import App from './views/App'
import MyPokemon from './views/MyPokemon'
import PokemonList from './views/PokemonList'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'pokemonlist',
            component: PokemonList
        },
        {
            path: '/mypokemon',
            name: 'mypokemon',
            component: MyPokemon,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
