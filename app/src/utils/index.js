import router from "../router";


function validate_input(field, min, max) {
    let length = field.length;
    if(length < min || length > max || length == 0) {
        return false;
    }
    return true;
}

function valide(fields={min: 1, max: 9999}){
    if(Object.keys(fields).length==0){
        return {is_valid: false};
    }

    const errors = [];
    const field_keys = Object.keys(fields);

    for(let i =0; i< field_keys.length;i++){
        
        if(!validate_input(fields[field_keys[i]].value, fields[field_keys[i]].min, fields[field_keys[i]].max)){
            errors.push({
                field: field_keys[i],
                message: `${field_keys[i]} está vazio ou é invalido.`,
            });
        }
    }

    if(errors.length>0){
        return {
            is_valid: false,
            errors
        }
    }


    return {
        is_valid: true
    };
}


function viewError(err, message){
    router.push({
        name: 'Error',
        params: {
            code: err.response.status,
            title: err.response.statusText,
            message
        }
    });
}

export { valide, viewError };