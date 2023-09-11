<template lang="">
<div>
  <Head title="Create User" v-if="!props.user"/>
  <Head title="Edit User" v-if="props.user"/>
    
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">
        <form @submit.prevent="submit" >
            <div class="form-group validated row">

             <text-input v-model="form.name" :error="form.errors.name" label="Name" placeholder="Name"/>

               <div class="form-group col-lg-6">
                    <label for="status" class="underline">Status</label>
                    <select id="status" class="form-control border-gray-200" v-model="form.status">
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="text-danger" v-if="form.errors.status">{{ form.errors.status }}</span>
               </div>


                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">

                              <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                            </div>
                            <div class="col-lg-6 kt-align-right">
                            <button type="reset" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx">Reset</button>
                            </div>


                        </div>
                    </div>
                </div>
             </div>
        </form>
    </div>
</div>
</div>
</template>
<script setup>
import { onMounted, reactive,ref } from 'vue'
import { useForm,router } from '@inertiajs/vue3'
import FileUpload from '../../../components/FileUpload.vue'
import Datepicker from '../../../components/Datepicker.vue'
import SubmitButton from '../../../components/SubmitButton.vue'
import FileInput from '../../../components/FileInput.vue'
import TextInput from '../../../components/admin/TextInput.vue'
import FilePond from '../../../components/FilePond.vue'



const form = useForm({
  name: props.category?.name || null,
  status:props.category?.status || '',
  category: urlParams.get('category') || '',
})

const props = defineProps({
   errors:Object,
   category:Object
})
 const imageUrl = ref('');
onMounted(()=>{
  if(props.category){
     emit.emit('pageName', 'Category Management',[{title: "Category ", routeName:"admin.category"},{title: "Edit", routeName:""}]);

  }else{
   emit.emit('pageName', 'Category Management', [{title: "Category", routeName:"admin.category"},{title: "Add", routeName:""}])
  }
})

function submit() {
  if(props.category){
    form.post(route('admin.updateCategory',props.category.id));
  }else{
    form.post(route('admin.createCategory'));
  }
}




</script>
<style lang="">
    
</style>