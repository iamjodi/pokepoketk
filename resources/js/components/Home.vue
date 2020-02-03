<template>
    <div class="vuetable-wrapper" style="position: relative;"><!--Wrapper Element -->

          <vuetable ref="vuetable"
          api-url="api/getList"
          :fields="fields"
          pagination-path=""
          :per-page="perPage"
          :css="css.table"
          :sort-order="sortOrder"
          :multi-sort="true"
          :append-params="moreParams"
          @vuetable:cell-clicked="onCellClicked"
          @vuetable:pagination-data="onPaginationData"
          wrapper-class="vuetable-wrapper"
          @vuetable:loading="onLoading" 
          @vuetable:loaded="onLoaded"
          >

          <template slot="actions" slot-scope="props">

        </template>

      </vuetable>
      <div class="vuetable-pagination">
        <vuetable-pagination-info ref="paginationInfo"
        info-class="pagination-info"
        ></vuetable-pagination-info>
        <vuetable-pagination ref="pagination"
        :css="css.pagination"
        @vuetable-pagination:change-page="onChangePage"
        ></vuetable-pagination>
      </div>
      <br/><br><br>
    </div>
</template>

<script>

    export default {
    	components: {
	      Vuetable,
	      VuetablePagination,
	      VuetablePaginationInfo,
	    },
	    data () {
	    	rowData: {
	          type: Object,
	          required: true
	        },
	        rowIndex: {
	          type: Number
	        },
	    	perpagedata:[10,25,50,75,100],
	    	perPage: 10,
        	filterText: '',
	    	fields: [
	        {
	          name: '__sequence',
	          title: '#',
	          titleClass: 'text-right',
	          dataClass: 'text-right'
	        },

	        {
	          title: 'Name',
	          name: 'vendor_name',
	          sortField: 'vendor_name',
	        },
	        {
	          title: 'Address',
	          name: 'vendor_address',
	          sortField: 'vendor_address'
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
	            tableClass: 'table table-bordered table-striped table-hover',
	            loadingClass: 'loading',
	            ascendingIcon: 'glyphicon glyphicon-chevron-up',
	            descendingIcon: 'glyphicon glyphicon-chevron-down'
	          },
	          pagination: {
	            wrapperClass: 'pagination',
	            activeClass: 'active',
	            disabledClass: 'disabled',
	            pageClass: 'page',
	            linkClass: 'link',
	            icons: {
	              first: '',
	              prev: '',
	              next: '',
	              last: '',
	            },
	          },
	          icons: {
	            first: 'glyphicon glyphicon-step-backward',
	            prev: 'glyphicon glyphicon-chevron-left',
	            next: 'glyphicon glyphicon-chevron-right',
	            last: 'glyphicon glyphicon-step-forward',
	          },
	        },
	        sortOrder: [
	        { field: 'vendor_name', sortField: 'vendor_name', direction: 'desc'}
	        ],
	        moreParams: {}
	    },
	    watch: {
	      'perPage'(newValue, oldValue) {
	       this.$events.fire('filter-set', this.filterText)
	     },
	    }
        name : 'Home'
    }
</script>
