<template>
  <div>  
    
    
    <v-container class="mb-6"    >
      <v-row v-if="errors.length > 0" >
        <v-alert closable  variant="outlined"  :text="errors[0]" type="warning"></v-alert>
      </v-row>
      <v-row>
        <!-- singlecustomer    {{ singlecustomer }} -->
          
          <v-card  class="mx-auto"  >
            <v-card-item title="Edit Customer">
            </v-card-item>          
            <v-form v-model="form.isFormValid" fast-fail @submit.prevent="saveCustomer">
              <v-card-text class="py-0">
                <v-sheet width="500" class="mx-auto">
                    <v-text-field v-model="singlecustomer.firstname" label="First name*" :rules="firstNameRules"  ></v-text-field>
                    <v-text-field v-model="singlecustomer.surname" label="Sur name" ></v-text-field>
                    <v-text-field v-model="singlecustomer.email" label="Email*"  :disabled="disabled" ></v-text-field>
                    <v-text-field v-model="singlecustomer.location" label="Location"   ></v-text-field>                              
                </v-sheet>
                <small>*indicates required field</small>
              </v-card-text>
              <v-card-actions>
                 <v-btn type="submit" rounded="0" variant="flat" block class="text-none text-white" color="blue-darken-4">Update customer</v-btn> 
              </v-card-actions>
            </v-form>
          </v-card>
          
  
        </v-row>
    </v-container>
  </div>
  </template>
  <script>
  import { reactive, onMounted } from "vue";
  import useCustomer from "../composables/customer";
  
  export default {
    name: "EditCustomer",
    props: {
      id: {
        required: true,
        type: String,
      },
    },
    data: () => ({
        disabled:true,
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
    setup(props) {
  
      const { errors ,updateCustomer, getCustomerbyId, singlecustomer  } = useCustomer();
      onMounted(() => getCustomerbyId(props.id));

      //Update customer
      const form = reactive({
        isFormValid: false
      })
      const saveCustomer = async () => {
        if(form.isFormValid){
          await updateCustomer();
        }        
      };
  
      
  
      return { errors, saveCustomer, singlecustomer, form }
    },
  }
  </script>