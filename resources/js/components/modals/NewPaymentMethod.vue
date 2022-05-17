<template>
  <a-modal
      width="900px"
      v-model="paymentModal"
      title="Add Credit Card"
      centered
      @ok="formSubmit"
      ok-text="Add payment method"
    >
    <div class="container-60" v-loading="loadForm">
      <a-row>
        <a-col :span="24">
          <a-card title="Add new card" id="creditCardValidation">
            <a-form-model
              ref="paymentForm"
              :model="card"
              >
              <a-form-model-item
                class="fix_label_global"
                label="Credit Card or Debit Card Number" prop="cardNumber" required>
                <a-input
                  v-cardformat:formatCardNumber
                  v-model="card.cardNumber"
                />
              </a-form-model-item>
              <a-form-model-item label="Expiry Date" prop="cardExpiry" required>
                <a-input
                  placeholder="MM/YY"
                  v-mask="'##/##'"
                  v-model="card.cardExpiry"
                />
              </a-form-model-item>
              <a-form-model-item label="CVC" prop="cardCvc" required>
                <a-input
                  v-cardformat:formatCardCVC
                  placeholder="Enter 3 or 4 digits from the back of the card"
                  v-model="card.cardCvc"
                />
              </a-form-model-item>
              <a-alert v-if="errors && errors.length" :message="errors" type="error"/>
            </a-form-model>
          </a-card>
        </a-col>
      </a-row>
    </div>
  </a-modal>
</template>
<script>
export default {
  props: {
    useDefaultCallback: {
      type: Boolean,
      default: true
    }
  },
  data(){
    return{
      loadForm: false,
      paymentModal: false,
      errors: [],
      errorsLaravel: '',
      success_message: '',
      card:{
        cardNumber: '',
        cardExpiry: '',
        cardCvc: '',
        type_pay_card: ''
      }
    };
  },
  computed: {

  },
  mounted() {
    this.loadStripeScripts();
  },
  methods:{
    hideModal(){
      this.paymentModal = false;
    },
    showModal(){
      this.paymentModal = true;
    },
    async formSubmit() {
      this.$refs.paymentForm.validate(valid => {
        if (valid) {
          this.saveNewCard();
        } else {
          return false;
        }
      });
    },
    saveNewCard() {
      this.checkCard().then(
        result => {
          this.storeNewCard();
        });
    },
    clearErrors() {
      this.errors = null;
      this.errorsLaravel = null;
      this.success_message = null;
    },
    getStoreData() {
      return {
        type_pay_card: this.card.type_pay_card,
        cardNumber: this.card.cardNumber,
        cardExpiry: this.card.cardExpiry,
        cardCvc: this.card.cardCvc
      };
    },
    storeNewCard() {
      let storeData = this.getStoreData();
      this.loadForm = true;
      this.$http.post(this.$routeLaravel('api.payment.store'), {
          paymentData: storeData
        })
        .then(response => {
          this.loadForm = false;
          this.$emit('payment-added', response);
        })
        .catch(error => {
          this.loadForm = false;
          this.$notification['error']({
            message: 'Error',
            description: this.$implodeErrorsLaravel(error.response.data.errors)
          });
        });
    },
    checkStripeFailPayment (stripe_invoice_secret_id, stripe_payment_id){
      var stripe = Stripe(window.stripePublicKey);
      stripe.confirmCardPayment(stripe_invoice_secret_id, {
        payment_method: stripe_payment_id
      }).then((result) => {
        if (!result.error) {
          this.afterSuccesCard();
        }
      });
    },
    afterSuccesCard() {
      this.hideModal();
    },
    async checkCard() {
      return true;
      this.clearErrors();
      let exp = this.card.cardExpiry.split('/');
      let cardData = {
        number: this.card.cardNumber,
        cvc: this.card.cardCvc,
        exp_month: exp[0],
        exp_year: exp[1]
      };
      return new Promise((resolve, reject) => {
        window.Stripe.card.createToken(cardData, (status, response) => {
          if (status !== 200) {
            this.errors = this.createErrror(response.error.message);
            reject({errors: this.errors});
          } else {
            resolve();
          }
        });
      });
    },
    loadStripeScripts(){
      if(_.has(window.Stripe, ['setPublishableKey'])){
        window.Stripe.setPublishableKey(window.stripePublicKey);
      }else{
        this.$helpers.loadScript('https://js.stripe.com/v2/', () => {
          this.$helpers.loadScript('https://js.stripe.com/v3/',() => {
            window.Stripe.setPublishableKey(this.$stripePublicKey);
          });
        });
      }
    },
  }
}
</script>
