import axios from "axios";

// For common config
axios.defaults.baseURL = process.env.VUE_APP_API_BASE;
//axios.defaults.headers.post["Content-Type"] = "application/json";

const http = axios.create({
    headers: {
      "Content-type": "application/json"
    }
});

const uploadhttp = axios.create({
  headers: {
    "Content-type": "multipart/form-data"
  }
});

export {
  http,
  uploadhttp
};