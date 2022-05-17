<style lang="scss">
.billing_form_outer{
  .el-form-item__label {
    line-height: 16px !important;
    padding: 0 0 5px 0;
  }
  .el-form-item {
    margin-bottom: 20px;
  }
}
.error_card_stripe{
  display: block;
  line-height: 22px;
  font-size: 12px;
  margin-bottom: 5px;
  margin-top: -15px;
  color: #ff0000;
}
</style>
<template>
  <div class="billing_form_outer">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>Payment form</span>
        </div>
        <el-form :model="form" :rules="rules" ref="loginForm" label-position="top" label-width="160px">
          <el-form-item label="Credit Card or Debit Card Number" prop="cardNumber">
            <el-input v-model="form.cardNumber" placeholder='____-____-____-____' v-mask="'####-####-####-####'" autocomplete="off"></el-input>
          </el-form-item>
          <div v-if="errorsCard" class="error_card_stripe">
            {{errorsCard}}
          </div>

          <el-form-item label="Expiry Date" prop="cardExpiry">
            <el-input 
              v-model="form.cardExpiry"
              autocomplete="off"
              v-mask="'##/##'"
              placeholder='__/__'
            />
          </el-form-item>
          
          <el-form-item label="CVV" prop="cardCvc">
            <el-input v-model="form.cardCvc" placeholder='____' v-mask="'####'" autocomplete="off"></el-input>
          </el-form-item>
          
          <el-form-item label="Count coins" v-mask="'####'" prop="coins">
            <el-input v-model="form.coins" autocomplete="off"></el-input>
          </el-form-item>
          
          <el-form-item>
            <el-button v-loading="loading" type="primary" @click="sendForm">Checkout</el-button>
          </el-form-item>
        </el-form>
      </el-card>
  </div>
</template>

<script>
export default {
  props: {

  },
  components: {  },
  data() {
    return {
      loading: false,
      errorLogin: false,
      errorsCard: '',
      form: {
        cardNumber: '',
        cardExpiry: '',
        cardCvc: '',
        coins: 10
      },
      rules: {
        cardNumber: [
          { required: true, message: 'Please input Card Number', trigger: 'blur' }
        ],
        cardExpiry: [
          { required: true, message: 'Please input Expiry Date', trigger: 'blur' }
        ],
        cardCvc: [
          { required: true, message: 'Please input CSV', trigger: 'blur' }
        ],
        coins: [
          { required: true, message: 'Please input Count coins', trigger: 'blur' }
        ]
      }
    };
  },
  watch: {
    
  },
  computed: {
   
  },
  mounted() {
    window.tempBillingForm = this;
    this.loadStripeScripts();
  },
  methods: {
    createErrror(message) {
      let er = message.replace(/card\[number\]/gi, '"Credit Card Number"');
      er = er.replace(/card\[exp_year\]/gi, '"Card Expire Year"');
      return er;
    },
    async validateStripe (){
      this.loading = true;
      this.errorsCard = null;
      let exp = this.form.cardExpiry.split('/');
      let cardData = {
        number: this.form.cardNumber,
        cvc: this.form.cardCvc,
        exp_month: exp[0],
        exp_year: exp[1]
      };

      return new Promise((resolve, reject) => {
        window.Stripe.card.createToken(cardData, (status, response) => {
          if (status !== 200) {
            this.loading = false;
            this.errorsCard = this.createErrror(response.error.message);
            resolve(false);
          } else {
            this.loading = false;
            resolve(true);
          }
        });
      });
    },
    async validate() {
      return new Promise(resolve => {
        this.$refs.loginForm.validate((valid) => {
         if (valid) {
           resolve(true);
         } else {
           resolve(false);
         }
       });
     });
    },
    async sendForm(){
      this.errorLogin = false;
      if(!await this.validate()){
        return;
      }
      
      if(!await this.validateStripe()){
        return;
      }
      
      this.loading = true;
      this.$http.post(this.$routeLaravel('api.payment.checkout'), this.form)
        .then(response => {
          this.loading = false;
        })
        .catch(error => {
          this.loading = false;
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
};
</script>


