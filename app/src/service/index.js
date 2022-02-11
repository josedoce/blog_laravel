import axios from 'axios';

const instance = axios.create({
  withCredentials: true,
  baseURL: process.env.VUE_APP_API,
});

const csrf = async function(){
  return await instance.get(`${ process.env.VUE_APP_API.replace('/api', '') }/sanctum/csrf-cookie`)
  .then((res)=>{
    console.log('csrf is ok');
  });
};

const authRequest = function(token){
  instance.defaults.headers = {
    Authorization: `Bearer ${ token }`
  };
  return {...instance};
};

export default instance;
export { instance as axios, csrf, authRequest }
