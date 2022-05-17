<style lang="scss" scoped>
.red{
  color:red;
}
</style>
<style lang="scss">
.auth_form{
  .errorLogin_form_input{
    .el-form-item__content{
      line-height: 6px;
    }
  }
}
</style>
<template>
  <div class="auth_form">
    <br>
    <br>
    <br>
    <br>
    <el-row :gutter="20">
      <el-col :span="8" :offset="8">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>Auth</span>
          </div>
          <el-form :model="form" :rules="rules" ref="loginForm" label-width="120px">
            <el-form-item label="Login" prop="login">
              <el-input v-model="form.login"></el-input>
            </el-form-item>

            <el-form-item label="password" prop="password">
              <el-input v-model="form.password"></el-input>
            </el-form-item>

            <el-form-item v-if="errorLogin" class="errorLogin_form_input">
              <span class="red">User not found</span>
            </el-form-item>

            <el-form-item>
              <el-button v-loading="loading" type="primary" @click="sendForm">Login</el-button>
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  components: {

  },
  data () {
    return {
      loading: false,
      errorLogin: false,
      form: {
        login: null,
        password: null
      },
      rules: {
        login: [
          { required: true, message: 'Please input login', trigger: 'blur' },
          { min: 3, max: 25, message: 'Length should be 3 to 25', trigger: 'blur' }
        ],
        password: [
          { required: true, message: 'Please input password', trigger: 'blur' },
          { min: 3, max: 25, message: 'Length should be 3 to 25', trigger: 'blur' }
        ]
      }
    };
  },
  computed: {

  },
  mounted(){
    window.tempLoginForm = this;
  },
  methods:{
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

      this.loading = true;

      this.$http.post(this.$routeLaravel('api.auth.login'), this.form)
        .then(response => {
          this.errorLogin = false;
          //this.$router.push({name: 'admin.index'});
          location.href= '/admin';
        })
        .catch(error => {
          this.errorLogin = true;
          this.loading = false;
        });
    }
  },
};
</script>
