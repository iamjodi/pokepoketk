<template>
    <div>
        <table>
            <tr>
               <td><h1>Catch'em All</h1></td>
            </tr>
            <tr>
                <td>
                    <div class="nav-container ">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 text-left text-left-xs text-left-sm">
                                        <div class="bar__module">
                                            <router-link :to="{ name: 'pokemonlist' }">
                                                <a class="btn btn--sm type--uppercase">
                                                    <span class="btn__text">
                                                       Pokemon List
                                                    </span>
                                                </a>
                                            </router-link> |
                                            
                                            <router-link :to="{ name: 'mypokemon' }">
                                                <a class="btn btn--sm btn--primary type--uppercase">
                                                    <span class="btn__text">
                                                        My Pokemon
                                                    </span>
                                                </a>
                                            </router-link>
                                            
                                        </div>
                                        <!--end module-->
                                    </div>
                                </div>
                                <!--end of row-->
                            </div>
                            <!--end of container-->
                        <!--end bar-->
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="container">
                        <router-view></router-view>
                    </div>
                </td>
            </tr>

        </table>
        

        
    </div>
</template>
<script>
    export default {}
</script>


