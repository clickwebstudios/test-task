
var _arr_l_scripts = {};
var _arr_l_callbacks = {};

var helpers = function () {
    
    this.loadStyle = function(styleName, callback) {
        let styleIsset = $("link[href='"+styleName+"']");

        if(styleIsset.length < 1){

            var body 		= document.getElementsByTagName('body')[0];
            var style 		= document.createElement('link');
            style.rel 	    = 'stylesheet';
            style.type 	    = 'text/css';
            style.href 		= styleName;

            // then bind the event to the callback function
            // there are several events for cross browser compatibility
            // script.onreadystatechange = callback;
            style.onload = callback;

            // fire the loading
            body.appendChild(style);

        }else{
            callback();
        }
    };
    
    this.loadScript = function (scriptName, callback, callbackAfterAdd){
        
        if (!_arr_l_scripts[scriptName]) {
            _arr_l_scripts[scriptName] = true;

            var body = document.getElementsByTagName('body')[0];
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = scriptName;

            $(script).attr('is_load', '0');

            // then bind the event to the callback function
            // there are several events for cross browser compatibility
            // script.onreadystatechange = callback;
            //script.onload = callback;

            _arr_l_callbacks[scriptName] = [];
            
            if(callbackAfterAdd){
                _arr_l_callbacks[scriptName].push(callbackAfterAdd);
            }
            
            if(callback){
                _arr_l_callbacks[scriptName].push(callback);
            }

            script.onload = function () {

                _arr_l_callbacks[scriptName].forEach(function (item) {
                    item();
                });

                // установим флаг, чтобы знать что скрипт был загружен
                $(script).attr('is_load', '1');
            };

            // fire the loading
            body.appendChild(script);

        } else if (callback) {

            // да скрипт возможно уже поставлен в загрузку, но он может еще загружаться
            let script = $('script[src="' + scriptName + '"]');
            if ($(script).attr('is_load') == '1') {
                callback();
            } else {
                // мы не знаем загружен скрипт или нет
                // поэтому мы поставим в очередь нужную нам функцию
                _arr_l_callbacks[scriptName].push(callback);
            }
        }
    };
};


export default function(){
    return new helpers();
}