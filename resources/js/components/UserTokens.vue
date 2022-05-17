<style lang="scss">

</style>
<template>
  <div class="billing_form_outer">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>Tokens</span>
        </div>
        <el-alert
          :title="tokenText"
          type="info"
          :closable="false"
        />
        <br>
        <el-button v-loading="loading" type="primary" @click="generateToken">Generate new token</el-button>
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
    };
  },
  watch: {
    
  },
  computed: {
    tokenText(){
      return this.user.access_token? this.user.access_token : 'Not generated';
    },
    user(){
      return this.$store.getters.user;
    }
  },
  mounted() {
    window.tempUserTokens = this;
  },
  methods: {
    generateToken(){
      this.loading = true;
      this.$http.post(this.$routeLaravel('api.user.generateAccessToken'))
        .then(response => {
          this.$store.dispatch('FRESH_GLOBAL_DATA');
          this.loading = false;
        })
        .catch(error => {
          this.loading = false;
        });
    },
  }
};
</script>


