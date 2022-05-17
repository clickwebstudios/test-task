
export default {
  install(Vue, options){
  
    Vue.prototype.$implodeErrorsLaravel = function (errors) {
        if (_.isString(errors)) {
          return errors;
        }
      
        let result = '';
        if (!_.isObject(errors) && !_.isArray(errors)) {
          return result;
        }
        
        for (let filed in errors) {
          let fieldErrors = errors[filed];
          
          if(_.isArray(fieldErrors)){
            fieldErrors.forEach((error) => {
              result += error;
            });
          }else{
            result += fieldErrors;
          }
        }
        return result;
    };
  
    Vue.prototype.$implodeErrors = function (errorsValidation, field) {
        if (!_.has(errorsValidation, [field])) {
            return null;
        }
        let string = '';
        errorsValidation[field].forEach((err) => {
            string += err + ', ';
        });
        return _.trim(string, ', ');
    };
    
    const validateEmail = (email) => {
      return String(email)
        .toLowerCase()
        .match(
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
    };
    
  }
}