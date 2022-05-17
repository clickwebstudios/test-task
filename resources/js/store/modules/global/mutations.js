import * as types from './mutation-types';
export const mutations = {
  
  [types.FETCH_USER] (state, payload) {
    state.user = payload;
  },
  
  [types.FETCH_GLOBAL_STORE_LOAD] (state, payload) {
    state.globalStoreIsLoad = payload;
  },
}
