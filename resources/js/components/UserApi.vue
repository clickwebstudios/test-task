<style lang="scss">
.lastResultMeta{
  background:#f3f3f3;
  color:#000;
  padding:20px;
  border-radius:4px;
  margin-top: 30px;
}
.errors_alert{
  margin: 20px 0px -15px;
}
</style>
<template>
  <div class="billing_form_outer">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>/metadata</span>
        </div>
        <el-alert
          title='This method parses the site and returns meta information'
          type="info"
          :closable="false"
        />
        <br>
        
        Exemple URL:
        <el-alert
          :title="exampleUrl"
          type="success"
          :closable="false"
        />
        <br>
        
        Exemple Site URL:
        <el-input
          v-model='siteurl'
        />
        
        <div v-if='lastResultMeta' class='lastResultMeta'>
          <b>Response:</b>
          <div>
            <b>Title:</b>
            {{ lastResultMeta.title }}
          </div>
          <div>
            <b>Description:</b>
            {{ lastResultMeta.description }}
          </div>
          <div>
            <b>Keywords:</b>
            {{ lastResultMeta.keywords }}
          </div>
        </div>
        
        <el-alert
          class='errors_alert'
          v-if='errors'
          :title="errors"
          type="error"
          effect="dark"
          :closable="false"
        />
        
        <br>
        <br>
        
        <el-button v-loading="loading" type="primary" @click="sendMetaRequest">Send request</el-button>
      </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      errors: null,
      lastResultMeta: null,
      loading: false,
      messageError: null,
      siteurl: 'https://www.amazon.com'
    };
  },
  watch: {
    
  },
  computed: {
    exampleUrl(){
      let text = this.$appUrl+'/metadata';
      text += '?token='+this.token;
      
      text += '&site='+this.siteurl;
      return text;
    },
    token(){
      return this.user.access_token;
    },
    user(){
      return this.$store.getters.user;
    }
  },
  mounted() {
    window.tempUserApi = this;
  },
  methods: {
    sendMetaRequest(){
      this.lastResultMeta = null;
      this.errors = null;
      this.loading = true;
      this.$http.get(this.exampleUrl)
        .then(response => {
          this.lastResultMeta = response.data.data;
          this.loading = false;
        })
        .catch(error => {
          this.loading = false;
          this.errors = this.$implodeErrorsLaravel(error.response.data.errors);
        });
    },
  }
};
</script>


