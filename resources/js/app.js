require('./bootstrap');

import axios from 'axios';
window._ = require('lodash');
import Vue from 'vue';
import App from './App.vue'
import router from '~/router/index'
Vue.prototype.$http = axios;

window.Vue = Vue;

let spkselector = document.head.querySelector('meta[name="stripe-public-key"]');
let appUrl = document.head.querySelector('meta[name="app-url"]');

if(spkselector){
    Vue.prototype.$stripePublicKey = spkselector.content;
}

if(appUrl){
    Vue.prototype.$appUrl = appUrl.content;
}

import VueMask from 'v-mask'
Vue.use(VueMask);

import helpers from '~/libs/helpers';
Vue.prototype.$helpers = helpers();

import {
    Rate,
    Slider,
    Select,
    Option,
    Tabs,
    TabPane,
    InputNumber,
    Dropdown,
    DropdownMenu,
    Dialog,
    Skeleton,
    SkeletonItem,
    Popover,
    CheckboxGroup,
    Checkbox,
    Loading,
    MessageBox,
    Message,
    Notification,
    Form,
    FormItem,
    Input,
    Col,
    Button,
    RadioButton,
    RadioGroup,
    Alert,
    Divider,
    Radio,
    Table,
    TableColumn,
    Pagination,
    Row,
    Card,
    Main,
    DropdownItem,
    Avatar,
} from 'element-ui';

import langUi from 'element-ui/lib/locale/lang/en'
import localeUi from 'element-ui/lib/locale'

localeUi.use(langUi);

Vue.component(Rate.name, Rate);
Vue.component(Slider.name, Slider);
Vue.component(Select.name, Select);
Vue.component(Option.name, Option);
Vue.component(Tabs.name, Tabs);
Vue.component(TabPane.name, TabPane);
Vue.component(InputNumber.name, InputNumber);
Vue.component(Dropdown.name, Dropdown);
Vue.component(DropdownMenu.name, DropdownMenu);
Vue.component(Dialog.name, Dialog);
Vue.component(Skeleton.name, Skeleton);
Vue.component(SkeletonItem.name, SkeletonItem);
Vue.component(Popover.name, Popover);
Vue.component(Checkbox.name, Checkbox);
Vue.component(CheckboxGroup.name, CheckboxGroup);
Vue.component(Form.name, Form);
Vue.component(Input.name, Input);
Vue.component(FormItem.name, FormItem);
Vue.component(Col.name, Col);
Vue.component(Button.name, Button);
Vue.component(RadioButton.name, RadioButton);
Vue.component(RadioGroup.name, RadioGroup);
Vue.component(Alert.name, Alert);
Vue.component(Divider.name, Divider);
Vue.component(Radio.name, Radio);
Vue.component(Table.name, Table);
Vue.component(TableColumn.name, TableColumn);
Vue.component(Pagination.name, Pagination);
Vue.component(Row.name, Row);
Vue.component(Card.name, Card);
Vue.component(Main.name, Main);
Vue.component(DropdownItem.name, DropdownItem);
Vue.component(Avatar.name, Avatar);

Vue.use(Loading.directive);
Vue.prototype.$loading = Loading.service;
Vue.prototype.$msgbox = MessageBox;
Vue.prototype.$alert = MessageBox.alert;
Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$prompt = MessageBox.prompt;
Vue.prototype.$message = Message;
Vue.prototype.$notify = Notification;

import moment from 'moment';
var momentTZ = require('moment-timezone');
Vue.prototype.$moment = momentTZ;

Vue.prototype.$routeLaravel = require('~/libs/routes.js');

import VueCardFormat from 'vue-credit-card-validation/dist/vue-credit-card-validation.js';
Vue.use(VueCardFormat);

import ValidationPlugin from './plugins/validation.js';
Vue.use(ValidationPlugin);

import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';
Vue.use(Antd);

import { notification } from "ant-design-vue";
Vue.prototype.$notification = notification;

import store from '~/store/index';

Vue.prototype.$_router = router;
Vue.prototype.$_store = store;

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');
window.pusher_key = 'efad69a218d76f9990f1'; //process.env.MIX_PUSHER_APP_KEY;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'efad69a218d76f9990f1', //process.env.MIX_PUSHER_APP_KEY,
    cluster: 'us2', //process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

(async () => {
  await store.dispatch('FRESH_GLOBAL_DATA');
  
  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app');
  
})();