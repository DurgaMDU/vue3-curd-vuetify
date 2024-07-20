<template>
<div>  
  
  <v-container class="mb-6"    >
  <v-row v-if="errors.length > 0" >
    <v-alert closable  variant="outlined"  :text="errors[0]" type="warning"></v-alert>
  </v-row>
    <v-row>
        <!--{{ customers }}-->
        
        <v-card  class="mx-auto"  >
          <v-card-item title="Add Customer">
          </v-card-item>          
          <v-form v-model="form.isFormValid" fast-fail @submit.prevent="saveCustomer">
            <v-card-text class="py-0">
              <v-sheet width="500" class="mx-auto">
                  <v-text-field v-model="form.firstname" label="First name*" :rules="firstNameRules"  ></v-text-field>
                  <v-text-field v-model="form.surname" label="Sur name" ></v-text-field>
                  <v-text-field v-model="form.email" label="Email*" :rules="emailRules"  ></v-text-field>
                  <v-text-field v-model="form.location" label="Location"   ></v-text-field>                              
              </v-sheet>
              <small>*indicates required field</small>
            </v-card-text>
            <v-card-actions>
               <v-btn type="submit" rounded="0" variant="flat" block class="text-none text-white" color="blue-darken-4">Add new customer</v-btn> 
            </v-card-actions>
          </v-form>
        </v-card>
        

      </v-row>
  </v-container>
</div>
</template>
<script>
import { reactive } from "vue";
import useCustomer from "../composables/customer";

export default {
  name: "AddCustomer",
  data: () => ({
      emailRules: [
      value => {
          if(value.length > 0) {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || 'Must be a valid e-mail.';
          }
        }
       
      ],
      firstNameRules: [
        value => {
          if (value?.length > 2) return true
          return 'First name must be at least 3 characters.'
        },
      ],
    }),
  setup() {

    const { errors ,storeCustomer  } = useCustomer();

    const form = reactive({
      isFormValid: false,
      firstname:"",
      surname:"",
      email:"",
      location:""
    })

    const saveCustomer = async () => {
      //console.log('form.isFormValid',form.isFormValid)
      if(form.isFormValid && form.firstname && form.email){
        await storeCustomer({ ...form });
      }      
    };

    

    return {  form, errors, saveCustomer }
  },
}
</script>