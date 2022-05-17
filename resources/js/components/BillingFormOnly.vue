<style lang="scss" scoped>
.text-right{
  text-align:right;
}
.retry_outer_block{
  display:flex;
  justify-content: flex-end;
  align-items: center;
}

.to_pay_btn{
  margin-left: 10px;
  display: inline-flex;
  align-items: center;
  height: 24px;
  .icon_tp{
    font-size:12px;
  }
}
.flex{
  display: flex;
}
.error_card_stripe{
  color:#ff0000;
  text-align:right;
}

.save_billing{
  text-align:right;
  margin:0px 0px;
  & > button{
    width:100%;
  }
}
.addNewCardModal{
  width:100%;
  margin-top:10px;
}
.paymnet_card_row{
  align-items: center;
  display: flex;
  margin:10px 0px;
}
.pay_border{
  border: 2px solid #1890ff;
}
.flex-right{
  justify-content: flex-end;
  text-align: right;
}
.icon_delete{
  color:#f5222d;
  cursor:pointer;
}
</style>
<style lang="scss">
.FIXED_LABEL{
  &.is_member{
    .not_payment_radio{
      .ant-radio-wrapper{
        display:none !important;
      }
    }
  }
  label{
    white-space: normal;
    line-height: 19px;
    display: inline-block;
    padding-right: 12px;
    align-items: center;
    position:relative;
    padding-top: 2px;
    &::after {
      top: 1px !important;
      content: ':' !important;
      position: absolute !important;
    }
    &::before {
      display: inline-flex;
      top: 1px;
    }
  }
  .ant-radio-inner{
    width: 22px;
    height: 22px;
  }
  .ant-radio-inner::after{
    width: 14px;
    height: 14px;
  }
  .ant-radio-wrapper{
    padding-right: 0;
    margin-right: 0;
    &:after{
      display: none;
    }
  }
  .card_number{
    display: flex;
    align-items: center;
    label{
      margin-left: 10px;
    }
    label:before{
      display: none;
    }
  }
  .broker_paid_type{
    margin:0px 0px 15px;
  
    .ant-radio-inner {
      width: 16px;
      height: 16px;
    }
    .ant-radio-inner::after {
      width: 8px;
      height: 8px;
    }
  }
  
}
</style>
<template>
<div>
  <div class="FIXED_LABEL BILLING_FORM_OUTER_ONLY">
    <template v-if="payments !== null">
      <template>
        <a-card
          :headStyle="{ background: '#F5F5F5' }"
          >
          <div slot="title">
            <div class="payments-cards">
              <p>Payment by Credit Card</p>
              <ul class="credit-cards">
                <li>
                  <img src="/img/credit_cards/visa.svg" />
                </li>
                <li>
                  <img src="/img/credit_cards/mastercard.svg" />
                </li>
                <li>
                  <img src="/img/credit_cards/maestro.svg" />
                </li>
                <li>
                  <img src="/img/credit_cards/amex.svg" />
                </li>
                <li>
                  <img src="/img/credit_cards/discover.svg" />
                </li>
                <li>
                  <img src="/img/credit_cards/diners.svg" />
                </li>
                <li>
                  <img src="/img/credit_cards/jcb.svg" />
                </li>
              </ul>
            </div>
          </div>
          <div>
            <a-spin :spinning="loadPayments">

              <a-row
                class="paymnet_card_row"
                justify="space-between"
                :key="index"
                v-for="(payment, index) in payments"
                >
                <a-col
                  :span="15"
                  >
                  <span v-if="payment.cardBrand">
                    {{ payment.cardBrand.toUpperCase() }}
                  </span>
                </a-col>
                
                <a-col :span="9">

                  <template v-if="payment.cardCode">
                    <a-col @click.stop.prevent="changePaymentDefault(payment)" :span="19" v-if="payment.is_default" class='flex-right card_number'>
                      ••••{{ payment.cardCode.slice(payment.cardCode.length - 4) }}
                      <a-radio :checked="true"></a-radio>
                    </a-col>
                    <a-col @click.stop.prevent="changePaymentDefault(payment)" :span="19" v-else class='flex-right card_number'>
                      ••••{{ payment.cardCode.slice(payment.cardCode.length - 4) }}
                      <a-radio :checked="false"></a-radio>
                    </a-col>
                  </template>

                  <a-col :span="5" class="flex-right">
                    <template v-if="!payment.is_default">
                      <a-popconfirm
                        title="Are you sure you want to remove this card from the profile?"
                        ok-text="Yes, remove it"
                        cancel-text="Cancel"
                        @confirm="deletePayment(payment.id)"
                        >
                        <a-icon type="delete" class="icon_delete"/>
                      </a-popconfirm>
                    </template>
                  </a-col>
                </a-col>
              </a-row>
            </a-spin>
          </div>
          <a-button @click="showPaymentModal" class="addNewCardModal" type="primary" ghost>Add additional payment card</a-button>
        </a-card>
      </template>

      
      <NewPaymentMethod
        ref="paymentModal"
        :use-default-callback="false"
        @payment-added="afterPaymentAdded"
      />
      

    </template>
    <template v-else>
      <a-skeleton :loading="true" active />
    </template>

  </div>
</div>
</template>

<script>
import {mapGetters} from "vuex";
import { notification } from "ant-design-vue";

import NewPaymentMethod from "~/components/modals/NewPaymentMethod";

export default {
  props: {
    showBrokerForm: {
      type: Boolean,
      default: true
    },
    allowChangePaymentType: {
      type: Boolean,
      default: true
    },
    allowSaveBilling: {
      type: Boolean,
      default: false
    },
    labelColPay: {
      type: Object,
      default(){
        return { span: 9 };
      }
    },
    wrapperColPay: {
      type: Object,
      default(){
        return { span: 15 };
      }
    },
  },
  components: { NewPaymentMethod },
  data() {
    return {
      brokerType: null,
      cardBrand: null,
      notification: notification,
      payments: null,
      stripeInvoice: null,
      loadPayments: false,
      type_pay_card: 'card',
      paymentType: "Card",
      cardEntity: {
        cardNumber: null,
        cardExpiry: '',
        cardCvc: null
      },
      errorsCard: '',
      validationCard: {},
      broker_paid_note: null,

      modal: {
        payment_method: false
      },
      paymentDataCash: {
        note: ""
      },
      errors: null,
      error_type: "",
      errorsLaravel: null,
      success_message: null,
      invoices: [],
    };
  },
  watch: {
    payments(){
      
    },
    clientEntity: {
      handler() {
        if(_.has(this.clientEntity, ['paymentData', 'broker_paid_note'])){
          this.broker_paid_note = this.clientEntity.paymentData.broker_paid_note;
        }
        if(_.has(this.clientEntity, ['paymentData', 'billing_infomation_broker_type_id'])){
          this.brokerType = this.clientEntity.paymentData.billing_infomation_broker_type_id;
        }
        this.payments = null;
        this.getUserPayments();
      },
      deep: true
    }
  },
  computed: {
    billingBrokerTypes(){
      return this.$store.getters.billingBrokerTypes;
    },
    defaultBrokerPaymentType(){
      let type = this.$store.getters.defaultBrokerPaymentType;
      return type? type.id : null;
    },
    radioBroker(){
      return (this.type_pay_card === 'broker_paid');
    },
    issetPaymentCard(){
      return true;
    },
    client: function () {
      return this.$store.getters.user;
    },
    clientEntity() {
      return this.$store.getters.user;
    },
    messagePaymentError: function(){
      if(!_.has(this.invoice, ['intent', 'last_payment_error', 'message'])){
        return null;
      }
      return this.invoice.intent.last_payment_error.message;
    },
    isMember(){
      return (this.auth_type === 'user');
    },
    ...mapGetters(['auth_type'])
  },
  mounted() {
    window.temp7756 = this;
    
    // достает текущие способы оплаты..метод не очень быстрый, поэтому достаем его здесь? по требованию, а не через хранилище
    this.payments = null;
    this.getUserPayments();
  },
  methods: {
    showPaymentModal() {
      this.$refs.paymentModal.showModal();
    },
    hidePaymentModal() {
      this.$refs.paymentModal.hideModal();
    },
    afterPaymentAdded(){
      this.hidePaymentModal();
      this.getUserPayments();
    },
    changePaymentDefault(payment) {
      this.loadPayments = true;

      this.$http.post(this.$routeLaravel('api.payment.setDefault', payment.id))
        .then(() => {
          this.getUserPayments();
        })
        .catch(() => {
          this.loadPayments = false;
        });
    },
    deletePayment(id){
      this.loadPayments = true;
      this.$http.post(this.$routeLaravel('api.payment.delete', id))
        .then(() => {
          this.loadPayments = false;
          this.getUserPayments();
        });
    },
    clearcardValidate(){
      if(this.$refs.formModelCard){
          this.$refs.formModelCard.clearValidate();
      }
      this.errorsCard = null;
    },
    validate: async function(){
      this.clearcardValidate();

      let valForm = await this.validateFrom();
      let valStripe = true;
      try{
        await this.validateStripe();
      }catch (e) {
        valStripe = false;
      }
      return (valForm && valStripe);
    },
    async validateFrom () {
      let val = false;
      await this.$refs.formModelCard.validate().then(() => {
        val = true;
      }, () => {});
      return val;
    },
    async validateStripe (){
      this.errorsCard = null;
      let exp = this.cardEntity.cardExpiry.split('/')
      let cardData = {
        number: this.cardEntity.cardNumber,
        cvc: this.cardEntity.cardCvc,
        exp_month: exp[0],
        exp_year: exp[1],
      };

      return new Promise((resolve, reject) => {
        window.Stripe.card.createToken(cardData, (status, response) => {
          if (status !== 200) {
            this.errorsCard = this.createErrror(response.error.message);
            reject({errors: this.errorsCard});
          } else {
            resolve();
          }
        });

      });
    },
    createErrror(message) {
      let er = message.replace(/card\[number\]/gi, '"Credit Card Number"');
      er = er.replace(/card\[exp_year\]/gi, '"Card Expire Year"');
      return er;
    },
    async getUserPayments(){
      this.payments = null;
      this.loadPayments = false;
      if(!this.clientEntity){
          return;
      }
      return new Promise(resolve => {
        this.loadPayments = true;

        this.$http.get(this.$routeLaravel('api.payment.getPayments'))
          .then(response => {
            this.payments = response.data.payments;
          })
          .catch(error => {

          })
          .then(() => {
            this.loadPayments = false;
            resolve();
          });
      });
    }
  }
};
</script>


