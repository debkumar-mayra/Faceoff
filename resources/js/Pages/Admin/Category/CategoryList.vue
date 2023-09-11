<template lang="">    
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">
        <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length" id="kt_table_1_length">
                        <label>Show
                            <select class="form-control border-gray-200 custom-select custom-select-sm form-control form-control-sm" v-model="perPage" @change="ListHelper.setPerPage($event.target.value)">
                        
                                <option value="5"> 5</option>  
                                <option value="10"> 10</option> 
                                <option value="20"> 20</option>  
                                <option value="50"> 50</option>
                                <option value="100"> 100</option>
                            </select> entries
                        </label>
                        
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="kt_table_1_filter" class="dataTables_filter">
                       <Link v-if="filters.category" :href="route('admin.createCategory',{category:filters.category})" class="btn btn-primary">+ Add New</Link>

                       <Link v-else :href="route('admin.createCategory')" class="btn btn-primary">+ Add New </Link>
                        <!-- {{ $search }} -->
                    </div>
                </div>
            </div>
            <div class="row table-responsive">
                <div class="col-sm-12">
                    <table
                        class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                        id="kt_table_1" role="grid" aria-describedby="kt_table_1_info" style="width: 1115px;">
                        <thead>


                            <tr role="row">
                            <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 50%;" aria-sort="ascending" aria-label="Agent: activate to sort column descending">Name <i
                            class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('name')"></i>
                            </th>

                            <th tabindex="0" aria-controls="kt_table_1" rowspan="1" colspan="1" style="width: 15%;"
                            aria-sort="ascending" aria-label="Agent: activate to sort column descending">Status <i
                            class="fa fa-fw fa-sort pull-right" style="cursor: pointer;" @click="ListHelper.sortBy('status')"></i>
                            </th>

                            <th class="align-center" rowspan="1" colspan="1" style="width: 20%;" aria-label="Actions">Actions</th>
                            </tr>

         
                            <tr class="filter">
                                <th>
                                    <input type="search" v-model="form.name" placeholder="" autocomplete="off"
                                        class="form-control-sm form-filter" />
                                </th> 

                                <th>
                                <select class="form-control form-control-sm form-filter kt-input" v-model="form.status" title="Select" data-col-index="2">
                                    <option value="">Select One</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                               </th>

                                <th>
                                    <div class="row justify-content-center align-items-center">
                                    </div>
                                </th>
                            </tr>

                        </thead>
                        <tbody v-auto-animate>      
                            <tr role="row" class="odd" v-for="category in categories.data" :key="category.id">
                                <td class="sorting_1" tabindex="0">
                                    {{category.name}}
                                </td>

                <td class="align-center">
                    <span @click="changeStatus(category.id)" style="cursor: pointer;" class="kt-badge kt-badge--inline kt-badge--pill cursor-pointer"
                    :class="(category.status == 1) ? 'kt-badge--success':'kt-badge--warning'"
                        >{{(category.status == 1) ? 'Active':'Inactive'}}</span>
                </td>

                                <td nowrap="" class="align-center">
                                    <span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        <Link class="dropdown-item" :href="route('admin.category',{category:category.id})"><i class="la la-eye"></i>Categories</Link>

                                        <Link class="dropdown-item" :href="route('admin.updateCategory',category.id)"><i class="la la-edit"></i> Edit</Link>
                                        <button href="#" class="dropdown-item" @click="deleteRecode(category.id)"><i class="fa fa-trash"></i> Delete</button>
                                    </div>
                                    </span>
                                </td>
                            </tr>

                             <tr role="row" v-if="Object.keys(categories.data).length == 0" class="odd text-center">
                                <td colspan="3" >No data Found</td>
                             </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
                    Showing {{categories.from}} to {{categories.to}} of {{categories.total}} entries
                </div>
            </div>
            <div class="col-sm-12 col-md-7">

                  <div class="float-right"> 
                     <Bootstrap4Pagination
                            :data="categories"
                            :limit=2
                            @pagination-change-page="ListHelper.setPageNum"
                        />
                        </div>
            </div>
        </div>
    </div>
</div>




</template>


<script setup>
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import Paginate from '../../../components/Paginate.vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, watch, reactive } from 'vue';
import {debounce,throttle,pickBy} from "lodash";
import ListHelper from '../../../helpers/ListHelper';



const {categories, filters} = defineProps({ categories: Object, filters: Object });

const form = reactive({
    name: filters.name || null,
    status: filters.status || null,
    category: filters.category || null,
})

watch(form, debounce(() => {
    router.visit(route('admin.category'), {
    method: 'get',
    data: pickBy(form),
    preserveState: true 
    });
    }, 100));



const perPage = ref(5);
onMounted(() => {
    perPage.value = urlParams.get('perPage') || usePage().props.perPage;
   emit.emit('pageName', 'Category Management',[{title: "Category List", routeName:"admin.category"}]);

    emit.on('deleteConfirm', function (arg1) {
        deleteConfirm(arg1);
    });

   emit.on('changeStatusConfirm', function (arg1) {
        changeStatusConfirm(arg1);
    });

});


const changeStatus = (id) => {
    sw.confirm('changeStatusConfirm',id,'Are you sure?',"You want to change the status!",'Yes, Change it!');
}

const changeStatusConfirm = (id) => {
    router.post(route('admin.changeStatusCategory',id))
}


const deleteRecode = (id) => {
 sw.confirm('deleteConfirm',id);
}

const deleteConfirm = (id) => {
    router.delete(route('admin.deleteCategory',id));
} 




</script>
