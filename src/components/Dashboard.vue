<template>
<div>
  <v-container class="mb-6"    >
      <v-row  align="start" no-gutters      >
        <v-col >
          <v-sheet class="pa-2 ma-2">
            <v-card  class="mx-auto"  variant="outlined" >
              <v-card-item title="Customer">
                <template v-slot:subtitle>
                  <v-icon  icon="mdi-home"   size="18"  color="error"  class="me-1 pb-1"  ></v-icon>
                  Total Customer
                </template>
              </v-card-item>

              <v-card-text class="py-0">
                <v-row align="center" no-gutters>
                  <v-col  class="text-h2" cols="12"  > {{ totalcustomers }} </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-sheet>
        </v-col>
        <v-col  >
          <v-sheet class="pa-2 ma-2">
            <v-card  class="mx-auto" variant="outlined"  >
              <v-card-item title="Customer Actions">
                <template v-slot:subtitle>
                  <v-icon  icon="mdi-download"   size="18"  color="error"  class="me-1 pb-1"  ></v-icon>
                  template csv file <a href="/customer-csv.csv" download>click here</a>
 
                </template>
              </v-card-item>

              <v-card-text class="py-0">
                <v-row align="center" no-gutters>
                  <v-col  class="text-h2" cols="12" > 
                    <v-btn class="ma-2"  color="green-lighten-2" to="/add" >Add</v-btn>
                    <v-btn class="ma-2"  color="blue-lighten-2"   @click="csvfile.dialog = true">Upload</v-btn>
                  </v-col>
                </v-row>
              </v-card-text>

            </v-card>
          </v-sheet>
        </v-col>
      </v-row>
     
      
    <v-row v-if="success.length > 0">
      <v-alert closable variant="outlined" type="success">
        <span v-html="success[0]"></span>
      </v-alert>
    </v-row>   

    <v-row v-if="errors.length > 0">
      <v-alert closable variant="outlined" type="warning">
        <span v-html="errors[0]"></span>
      </v-alert>
    </v-row>   

    <v-row v-if="info.length > 0">
      <v-alert closable variant="outlined" type="warning">
        <span v-html="info[0]"></span>
      </v-alert>
    </v-row>   

    <v-row>
        <!--{{ customers }}-->
        <v-card  class="mx-auto"  variant="outlined" >
          <v-toolbar color="primary"  title="Customer listing"  >

            <v-toolbar-items>
              <v-btn  variant="text" @click="getCustomerDefault(1,5,[])" icon="mdi-refresh"></v-btn>
            </v-toolbar-items>
          </v-toolbar>  
          <v-card-text class="py-0">

            
            <v-data-table-server
                v-model:items-per-page="itemsPerPage"
                :headers="headers"
                :items="paginated_customers"
                :items-length="totalcustomers"
                :loading="loading"
                :search="search"
                item-value="firstname"
                @update:options="getCustomer"
              >

              <!-- <template v-slot:item.edit="{ item }">
              <v-btn :loading="item.loading" elevation="0" icon color="red" v-on:click="tblchallenges_delete(item)">
              <v-icon dark>mdi-delete</v-icon>
              </v-btn></template> -->
              <template v-slot:body="{ items }">
                <tr v-for="item in items" :key="item.id">
                  <td>{{ item.firstname }}</td>
                  <td>{{ item.surname }}</td>
                  <td align="start">{{ item.email }}</td>
                  <td>{{ item.location }}</td>
                  <td>{{ item.created }}</td>
                  <td>
                    <v-btn class="ma-2" variant="text" icon="mdi-pencil"  color="blue-lighten-2" :to="`/edit/${item.id}`" ></v-btn>
                    <v-btn class="ma-2" variant="text" icon="mdi-delete"  color="red-lighten-2" @click="deleteCustomer(item.id,item.firstname)" ></v-btn>
                  </td>
                </tr> 
              </template>
              
            </v-data-table-server>

            <!--
            <v-table density="compact" fixed-header align-center>
              <thead>
                  <tr>
                      <th class="text-center font-weight-bold">First Name</th>
                      <th class="text-center font-weight-bold">Sur Name</th>
                      <th class="text-center font-weight-bold">Email</th>
                      <th class="text-center font-weight-bold">Location</th>
                      <th class="text-center font-weight-bold">Created on</th>
                      <th class="text-center font-weight-bold">Action</th>
                  </tr>
              </thead>
              <tbody>
                <template  v-if="customers.length > 0" >
                  <tr v-for="item in customers" :key="item.firstname">
                      <td>{{ item.firstname }}</td>
                      <td>{{ item.surname }}</td>
                      <td>{{ item.email }}</td>
                      <td>{{ item.location }}</td>
                      <td>{{ item.created }}</td>
                      <td>
                        <v-btn class="ma-2" variant="text" icon="mdi-pencil"  color="blue-lighten-2" :to="`/edit/${item.id}`" ></v-btn>
                        <v-btn class="ma-2" variant="text" icon="mdi-delete"  color="red-lighten-2" @click="deleteCustomer(item.id,item.firstname)" ></v-btn>
                      </td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td colspan="6">No customer data found</td>
                  </tr>
                </template>
              </tbody>
           </v-table>

          -->
          </v-card-text>
        </v-card>
        
        
        <v-dialog v-model="csvfile.dialog" persistent transition="dialog-top-transition" width="auto" >
          <v-card>
            <v-toolbar color="primary"  title="Customer Upload"  ></v-toolbar>   
            <v-form v-model="csvfile.isFormValid" fast-fail @submit.prevent="uploadCustomer">   
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-sheet width="500" class="mx-auto">
                      <v-file-input label="Browse Customer file (*.csv)" v-model="csvfile.file"  :rules="[rules.required,rules.size]" accept=".csv" variant="underlined"></v-file-input>
                    </v-sheet>           
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn  color="blue-darken-1" variant="text" @click="csvfile.dialog = false"  >  Cancel </v-btn>
                <v-btn type="submit" rounded="0" color="blue-darken-1" variant="text"  >  Upload
                </v-btn>
              </v-card-actions>
           </v-form>
          </v-card>
        </v-dialog>

      </v-row>

      <v-overlay  :opacity="1" v-model="overlay.show" class="align-center justify-center" >
          <v-progress-circular indeterminate size="64">
            Loading...
          </v-progress-circular>
    </v-overlay>
  </v-container>
</div>
</template>
<script>
import { onMounted , reactive} from "vue";
import useCustomer from "../composables/customer";

    


export default {
  name: "Dashboard",
  data: () => ({
      itemsPerPage: 5,
      headers: [
        {
          title: 'Firstname',
          align: 'start',
          sortable: false,
          key: 'firstname',
        },
        { title: 'Surname', key: 'surname', sortable: false, align: 'start' },
        { title: 'Email', key: 'email', align: 'start' },
        { title: 'Location', key: 'location', align: 'start' },
        { title: 'Created on', key: 'created', align: 'start' },
        { title: 'Actions', key: 'action', sortable: false, align: 'start' },
      ],
      search: '',

      rules: {
        required: value => (!!value.length) || 'CSV File is required.',
        size: value => !value || !value.length || value[0].size < 2000000 || 'Customer CSV file size should be less than 2 MB!',
      },      
  }),
  methods: {
   
    
  },
  setup() {

    const { totalcustomers, customers, paginated_customers, loading, getCustomers, destroyCustomer,  uploadcsvCustomer, success,errors,info } = useCustomer();
    //console.log(process.env.VUE_APP_API_BASE)

    const overlay = reactive({
        show:true
    });
    onMounted(async () => {
      await getCustomers();
      overlay.show = false;
    });

    //Delete customer
    const deleteCustomer = async (id,name) => {
      if (!window.confirm("Are you sure want to delete this Customer "+name+" ?")) {
        return;
      }
      overlay.show = true;
      await destroyCustomer(id);
      await getCustomers();
      overlay.show = false;
    };
    //Refresh listing
    const getCustomerDefault = async (page, itemsPerPage, sortBy) => {
      
      overlay.show = true;
      await getCustomers( page, itemsPerPage, sortBy );
      overlay.show = false;
    }
    //Pagination get customer
    const getCustomer = async ( {page, itemsPerPage, sortBy} ) => {
      
      overlay.show = true;
      await getCustomers( page, itemsPerPage, sortBy );
      overlay.show = false;
    }
    const csvfile = reactive({
      isFormValid:false,
      file:[],
      dialog:false
    })
    
    const uploadCustomer = async () => {      
      //console.log('csvfile.file.length',csvfile.file.length)
      if(csvfile.isFormValid && csvfile.file.length){
        overlay.show = true;
        await uploadcsvCustomer({ ...csvfile });
        await getCustomers();
        csvfile.dialog = false;
        overlay.show = false;
      }        
    };

    return { getCustomer, getCustomerDefault, customers, paginated_customers, totalcustomers,loading, deleteCustomer, csvfile, uploadCustomer,success, info,errors,overlay }
  },
}
</script>