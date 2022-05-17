<style lang="scss">
.info_coins{

}
.message_error{
    margin-bottom:20px;
}
</style>
<template>
  <div class="billing_form_outer">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>Buy coins</span>
        </div>
        <el-form 
          :model="form" 
          :rules="rules" 
          ref="mainForm" 
          label-position="top" 
          label-width="160px"
          >
          
          <el-form-item label="Coins" prop="coins">
            <el-input 
              v-model="form.coins"
              autocomplete="off"
              v-mask="'####'"
              placeholder='10'
            />
          </el-form-item>
          
          <el-alert
            title="1 coin = $1 USD"
            type="info">
          </el-alert>
          
          <br>
          <el-alert
            class="message_error"
            v-if="messageError"
            :title="messageError"
            type="error"
          />
          
          <el-form-item>
            <el-button v-loading="loading" type="primary" @click="sendForm">Checkout</el-button>
          </el-form-item>
        </el-form>
      </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      messageError: null,
      form: {
        coins: 10
      },
      rules: {
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
    window.tempBuyCoinsForm = this;
  },
  methods: {
    async validate() {
      return new Promise(resolve => {
        this.$refs.mainForm.validate((valid) => {
         if (valid) {
           resolve(true);
         } else {
           resolve(false);
         }
       });
     });
    },
    async sendForm(){
      this.messageError = null;
      if(!await this.validate()){
        return;
      }
      
      this.loading = true;
      this.$http.post(this.$routeLaravel('api.user.checkoutCoins'), this.form)
        .then(response => {
          if(response.data.paymentResult.status === 'succeeded'){
            this.$notification["success"]({
              message: "Success",
              description: "The payment was successful"
            });
          }else{
            this.$notification["error"]({
              message: "Errror",
              description: "Payment failed",
            });
          }
          this.loading = false;
        })
        .catch(error => {
          this.messageError = error.response.data.message;
  
          this.loading = false;
        });
    },
  }
};
</script>


