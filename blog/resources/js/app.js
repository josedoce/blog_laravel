const { default: axios } = require('axios');

require('./bootstrap');
const URL = 'http://127.0.0.1:8000';
(async()=>{
    /*
    
    await axios.get(URL+'/sanctum/csrf-cookie')
    .then((res)=>{
        console.log('csrf', res);
    });
    */
    await axios.post(URL +'/login')
    .then((res)=>{
        console.log('login feito');
    });
        
    await axios.get(URL+'/nomes')
    .then((res)=>{
        console.log('response', res.data)
    });

    await axios.get(`${URL}/api/user`)
    .then((res)=>{
        console.log('response', res.data);
    });
})();