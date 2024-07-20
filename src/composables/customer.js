import { ref, computed } from 'vue'
import { http, uploadhttp } from "../http-common";
import { useRouter } from 'vue-router'

export default function useCustomer() {
    
    const errors = ref([]) //array of strings error message
    const info = ref([]) //array of strings info message
    const success = ref([]) //array of strings success message

    const singlecustomer = ref([]) // just one Customer
    const customers = ref([]) // List of Customer
    const totalcustomers = ref(0) //Customer totalcount

    const paginated_customers = ref([]) // pagination of Customer list

    const loading= ref(true);

    const router = useRouter()

    const getCustomers = async (page=1, itemsPerPage=5, sortBy) => {        
        loading.value = true
        errors.value = []
        try {
            let response = await http.get('/api/read.php')
            if(response.data.row !== undefined){
                customers.value = response.data.row
                totalcustomers.value = response.data.count
                await pagination(page, itemsPerPage, sortBy)
                loading.value = false
            }else{
                errors.value.push(response.data.status_message)
                loading.value = false
            }
        } catch (error) {
            errors.value.push(error.message)
            loading.value = false
        }
    }

    const pagination = async (page=1, itemsPerPage=5, sortBy = []) => {
        errors.value = []
        try {
                const start = (page - 1) * itemsPerPage
                const end = start + itemsPerPage
                const items = customers.value.slice()
                if (sortBy.length) {
                const sortKey = sortBy[0].key
                const sortOrder = sortBy[0].order
                console.log('items',items)
                console.log('sortOrder',sortOrder)
                items.sort((a, b) => {
                    const aValue = a[sortKey]
                    const bValue = b[sortKey]
                    return (sortOrder === 'desc' ? (a[sortKey] < b[sortKey]) : (a[sortKey] > b[sortKey])) ? 1 : -1;
                    //return sortOrder === 'desc' ? bValue - aValue : aValue - bValue  
                })
            }           
            paginated_customers.value = items.slice(start, end) 
        } catch (error) {
            errors.value.push(error.message)
            loading.value = false
        }

    }

    const getCustomerbyId = async (id) => {
        let response = await http.post(`/api/single_read.php?id=${id}`)
        if(response.data.status == 1){
            singlecustomer.value = response.data.row            
        }else{
            singlecustomer.value = {}
            window.alert('Customer data not available')
            await router.push({ path: '/' })
        }
        
    }

    //Store new customer
    const storeCustomer = async (data) => {
        errors.value = []
        try {
            let resp = await http.post('/api/create.php', data)
            if(resp.data.status == 0){
                errors.value.push(resp.data.status_message)
            }else{
                window.alert('Customer data added successfully')
                await router.push({ path: '/' })
            }
        } catch (e) {
            errors.value.push('Error while save customer')
        }

    }

    //Delete Customer by id
    const destroyCustomer = async (id) => {
        errors.value = []
        success.value = []
        let resp = await http.post(`/api/delete.php`,{'id':id})
        if(resp.data.status == 0){
            errors.value.push(resp.data.status_message)
        }else{
            success.value.push(resp.data.status_message)
        }
    }

    //Update Available customer
    const updateCustomer = async () => {
        errors.value = []
        try {
            let resp = await http.post('/api/update.php', singlecustomer.value)
            if(resp.data.status == 0){
                errors.value.push(resp.data.status_message)
            }else{
                window.alert('Customer data updated successfully')
                await router.push({ path: '/' })
            }        
        } catch (e) {
            errors.value.push('Error while save customer')
        }

    }

   //Upload .csv customerlist
   const uploadcsvCustomer = async (data) => {
    info.value = [];
    success.value = [];
    let formData = new FormData();
    formData.append("file", data.file[0]);
    
    try {
        let resp = await uploadhttp.post('/api/upload.php', formData)
        if(resp.data.status == 0){
            info.value.push(resp.data.status_message)
        }else{
            success.value.push(resp.data.result.message)
            //await router.push({ path: '/' })
        }        
    } catch (e) {
        info.value.push('Error while upload customer file')
    }
    

}

    return {
        getCustomers,
        pagination,
        getCustomerbyId,
        storeCustomer,
        destroyCustomer,
        updateCustomer,
        uploadcsvCustomer,
        success,
        info,
        errors,
        customers,
        paginated_customers,
        singlecustomer,
        totalcustomers,
        loading,
    }

}