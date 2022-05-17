import * as types from './mutation-types';
import axios from "axios";

export const actions = {

    FRESH_GLOBAL_DATA: async (context, payload) => {
      return new Promise(resolve => {
                
        axios.get('/store/global/get').then((response) => {
          
          context.commit('FETCH_USER', response.data.user);
          context.commit('FETCH_GLOBAL_STORE_LOAD', true);
          
          resolve();
        })
        .catch(error => {
          resolve();
        });
      });
    },
};
