<template>
    <div>
        <div class="row">

            <div class="col-lg-12">
                <div class="feature boxed boxed--border border--round">
                    <div class="row" style="margin-bottom:0px;">
                        <div class="col-sm-4">
                            <label><b>Pokemon Name:</b></label>
                            <input type="text" v-model="filterText.pokemon" class="form-control" @keyup.enter="doFilter" placeholder="">
                        </div>
                        <div class="col-sm-2">
                            <label></label>
                            <a class="btn btn--primary btn--icon" @click="doFilter" href="#">
                                <span class="btn__text">
                                    <i class="icon-Add-File"></i>Search</span>
                            </a>
                        </div>
                        <div class="col-sm-2">
                            <label></label>
                            <a class="btn btn--primary btn--icon" @click="resetFilter" href="#">
                                <span class="btn__text">
                                <i class="icon-Add-File"></i>Clear</span>
                            </a>
                        </div>
                        <div class="col-sm-1" style="float: left; margin-left: 25%">
                            <div class="form-line">
                                <label>Per Page:</label>
                                <br>
                                <select class="form-control" v-model="perPage">
                                    <option :value=10>10</option>
                                    <option :value=25>25</option>
                                    <option :value=50>50</option>
                                    <option :value=75>75</option>
                                    <option :value=100>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="feature boxed boxed--border border--round">
                    <div v-show="loading" class="overlay flex flex-column items-center justify-center">
                        <spinner size="medium" message="loading..."></spinner>
                    </div>
                    <div class="table-responsive" style="position: relative;">
                        <vuetable ref="vuetable" api-url="api/myPokemonPaginate" :fields="fields" pagination-path="" :per-page="perPage" :css="css.table" :sort-order="sortOrder" :multi-sort="true" :append-params="moreParams" @vuetable:cell-clicked="onCellClicked" @vuetable:pagination-data="onPaginationData" @vuetable:loading="onLoading" @vuetable:loaded="onLoaded" wrapper-class="vuetable-wrapper">

                        <template slot="actions" slot-scope="props">
                            <a @click="openDetail(props.rowData)" class="btn btn--primary" href="#">
                                <span class="btn__text">Detail</span>
                            </a>
                        </template>

                        </vuetable>
                        <div class="vuetable-pagination">
                            <vuetable-pagination-info ref="paginationInfo" info-class="pagination-info"></vuetable-pagination-info>
                            <vuetable-pagination ref="pagination" :css="css.pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
                        </div>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <modal name="detail" classes="demo-modal-class" height="auto" width="100%" :scrollable="true">
            <div class="size-modal-content">
                <table>
                    <tr>
                        <td><b style="font-size: 20px;">Detail Pokemon</b><hr></td>
                    </tr>
                    <tr>
                        <td><b style="font-size: 15px;">Name : {{this.detailPokemon.name}}</b></td>
                    </tr>
                    <tr>
                        <td v-if="detailPokemon.nickname!=null"><b style="font-size: 15px;">Nick Name : {{this.detailPokemon.nickname}}</b></td>
                        <td v-if="detailPokemon.nickname=='' || detailPokemon.nickname==null"><b style="font-size: 15px;">Nick Name : #Not Give#</b></td>
                    </tr>
                    <tr>
                        <td><b style="font-size: 15px;">Capture Date : {{this.detailPokemon.date}}</b></td>
                    </tr>
                    <tr>
                        <td><b style="font-size: 15px;">Moves: {{this.detailPokemon.moves}}</b></td>
                    </tr>
                    <tr>
                        <td>
                            <a @click="releasePokemon()" class="btn btn--primary" href="#">
                                <span class="btn__text">Let It Go</span>
                            </a> 
                            <a @click="hide()" class="btn btn--success" href="#">
                                <span class="btn__text">Close</span>
                            </a></td>
                    </tr>
                </table>
            </div>        
        </modal>

        <modal name="loading" classes="demo-modal-class" height="auto" width="100%" :scrollable="true">
            <div class="size-modal-content">
                <table>
                    <tr>
                        <td><hr><b style="font-size: 50px;">...Loading...</b><hr></td>
                    </tr>
                    <tr>
                        <img src="https://media.giphy.com/media/Sq6PcgS2UBrVK/giphy.gif" alt="this slowpoke moves"  width=250/>
                    </tr>
                </table>
            </div>        
        </modal>

        <modal name="release" classes="demo-modal-class" height="auto" width="100%" :scrollable="true">
            <div class="size-modal-content">
                <table>
                    <tr>
                        <td><hr><b style="font-size: 50px;">...Waiting For Release...</b><hr></td>
                    </tr>
                    <tr>
                        <img src="https://media.giphy.com/media/hR2FOszEa5kqI/giphy.gif" alt="this slowpoke moves"  width=250/>
                    </tr>
                </table>
            </div>        
        </modal>
    </div>
</template>

<script>
import Vue from 'vue'
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import VueEvents from 'vue-events'      
import VueSweetalert2 from 'vue-sweetalert2';
import VModal from 'vue-js-modal'
import Spinner from 'vue-simple-spinner'
 
import 'sweetalert2/dist/sweetalert2.min.css';

window.axios = require('axios')
Vue.use(VueEvents); 
Vue.use(VueSweetalert2);
Vue.use(VModal)
export default {
    components: {
      Vuetable,
      VuetablePagination,
      VuetablePaginationInfo,
      Spinner,
    },
    data () {
        return {
            detailPokemon:[],
            rowData: {
                type: Object, required: true
            },
            rowIndex: {
                type: Number
            },
            perPage: 10,
            loading: true,
            filterText: {},
            fields: [ {
                name: '__sequence', 
                title: '#', 
                titleClass: 'text-center', 
                dataClass: 'text-center'
            },
            {
                title: 'Name', 
                name: 'name', 
                sortField: 'name',
            },
            {
                title: 'Nick Name', 
                name: 'nickname', 
                sortField: 'nickname',
                callback: 'checkNick'
            },
            {
                name: '__slot:actions', 
                title: 'Actions', 
                titleClass: 'text-center', 
                dataClass: 'text-center'
            }
            ],
            css: {
                table: {
                    tableClass: 'table table-bordered table-striped table-hover', loadingClass: 'loading', ascendingIcon: 'glyphicon glyphicon-chevron-up', descendingIcon: 'glyphicon glyphicon-chevron-down'
                },
                pagination: {
                    wrapperClass: 'pagination',
                    activeClass: 'active',
                    disabledClass: 'disabled',
                    pageClass: 'page',
                    linkClass: 'link',
                    icons: {
                        first: '', prev: '', next: '', last: '',
                    },
                },
                icons: {
                    first: 'glyphicon glyphicon-step-backward', prev: 'glyphicon glyphicon-chevron-left', next: 'glyphicon glyphicon-chevron-right', last: 'glyphicon glyphicon-step-forward',
                },
            },
            sortOrder: [ {
                field: 'name', sortField: 'name', direction: 'asc'
            }
            ],
            moreParams: {}
        }
    },
    watch: {
        'perPage'(newValue, oldValue) {
            this.$events.fire('filter-set', this.filterText)
        },
        '$route' (to, from) {
            if(from.params.id !=to.params.id) {
                this.$router.push('/page-not-found')
            }
        }
    },
    methods: {
        checkNick(value){
            if(value=="" || value==null){
                return '#Not Give#';
            }else{
                return value;
            }
        },
        releasePokemon () {
            
            this.$swal({
              title: 'Are you sure to release '+this.detailPokemon.name+' to nature?',
              text: "You still be able to catch again.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, please!'
            }).then((result) => {
              if (result.value) {   

                this.$modal.show('release');
               
                setTimeout(() => {
                    axios.get('/api/releasePokemon/'+this.detailPokemon.id).then(response => {
                        this.playSound(3)
                        this.resetFilter();
                        this.$modal.hide('release');

                        this.$swal({
                          icon: 'success',
                          title: 'Success to release '+this.detailPokemon.name,
                          showConfirmButton: false,
                          timer: 2000
                        })

                        this.hide()
                    })
                }, 3000)

                

              }
            })
        },
        show () {
            this.$modal.show('detail');
        },
        hide (value) {
            this.$modal.hide('detail');
        },
        openDetail(value){
            this.playSound(2)
            this.$modal.show('loading');

            setTimeout(() => {
                axios.get('/api/getDetailMyPokemon/'+value.id).then(response => {
                    this.$modal.hide('loading');
                    this.detailPokemon = response.data
                    this.show()
                })
            }, 3000)
            
        },
        doFilter() {
            this.$events.fire('filter-set', this.filterText)
        },
        resetFilter() {
            this.filterText = {}
            this.$events.fire('filter-reset')
        },
        onPaginationData (paginationData) {
            this.$refs.pagination.setPaginationData(paginationData) 
            this.$refs.paginationInfo.setPaginationData(paginationData)
        },
        onChangePage (page) {
            this.$refs.vuetable.changePage(page)
        },
        onCellClicked (data, field, event) {
            this.$refs.vuetable.toggleDetailRow(data.id)
        },
        onLoading() {
          
          console.log('loading... show your spinner here')
          this.loading = true
        },
        onLoaded() {
          console.log('loaded! .. hide your spinner here')
          this.loading = false
        },
        playSound(value){
            if(value==1){
                var audio = new Audio('sound/list.mp3');
            }else if(value==2){
                var audio = new Audio('sound/open.mp3');
            }else{
                var audio = new Audio('sound/release.mp3');
            }

            audio.play();
        }
    },
    events: {
        'filter-set' (filterText) {
            this.moreParams= {
                pokemon: filterText.pokemon
            }
            Vue.nextTick( ()=> this.$refs.vuetable.refresh())
        },
        'filter-reset' () {
            this.moreParams= {}
            Vue.nextTick( ()=> this.$refs.vuetable.refresh())
        }
    },
    created: function() {   
        this.playSound(1)
    },
    mounted() {
        
    },
    name: 'MyPokemon'
}
</script>

<style>
    .size-modal-content {
        padding: 10px;
        font-style: 13px;
      }

    .demo-modal-class {
        border-radius: 5px;
        background: #F7F7F7;
        box-shadow: 5px 5px 30px 0px rgba(46,61,73, 0.6);
    }

/* Loading Animation: */

.pagination {
  float: right;
  padding: 0 20px 20px 0;
}
.pagination a.page {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.page.active {
  color: white;
  background-color: #337ab7;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.btn-nav {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
}
.pagination a.btn-nav.disabled {
  color: lightgray;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
  cursor: not-allowed;
}
.pagination-info {
  float: left;
}

.overlay { 
  position: absolute; 
  top: 0; left: 0; 
  /* bottom: 0%; right: 0%; */
  width: 100%; height:100%; 
  z-index: 10; 
  background-color: rgba(0,0,0,0.2);
  /*dim the background*/ 
}
.flex {
  display: flex;
}
.flex-column {
  flex-direction: column;
}
.items-center {
  align-items: center;
}
.justify-center {
  justify-content: center;
}
</style>