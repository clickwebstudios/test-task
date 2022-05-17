<style lang="scss" scoped>
.main_menu{
  display: flex;
  justify-content: space-between;
  box-shadow: 1px 1px 12px 0px rgb(0 0 0 / 5%);
  align-items: center;
  min-height: 55px;
  padding: 10px 15px;
}
.name_user{
  display:flex;
  align-items: center;
  .icon_user{
    margin-right:10px;
  }
}
.main_menu_ul{
  padding:0px;
  margin:0px;
  display:flex;
  li{
    list-style:none;
    margin-right:10px;
  }
}
</style>
<template>
  <div class="main_menu" v-if="user">
    <ul class="main_menu_ul">
      <li>
        <router-link :to="{ name: 'admin.index' }" class="nav-text">
          <span>Home page</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'admin.balance'}" class="nav-text">
          <span>Buy coins and Attach card</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'admin.tokens'}" class="nav-text">
          <span>API and Generate Token</span>
        </router-link>
      </li>
    </ul>
    <div class="menu_user">
      <el-dropdown trigger="click">
        <span class="el-dropdown-link">
          <div class="name_user">
            <el-avatar :size="30" :src="circleUrl" class="icon_user"></el-avatar>
            {{ user.name }} (coins: {{ user.user_balance.coins }})
            <i class="el-icon-arrow-down el-icon--right"></i>
          </div>
        </span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item icon="el-icon-bank-card">
            <span @click="goToBalance">
              Top up your balance
            </span>
          </el-dropdown-item>
          <el-dropdown-item icon="el-icon-close" @click="logout"><span @click="logout">Logout</span></el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
export default {
  components: {

  },
  data () {
    return {
      circleUrl: "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png",
    };
  },
  computed: {
    user(){
      return this.$store.getters.user;
    }
  },
  methods:{
    goToBalance(){
      this.$router.push({name: 'admin.balance'});
    },
    logout(){
      this.$http.post(this.$routeLaravel('api.auth.logout'))
        .then(() => {
          location.href = '/';
        });
    }
  },
  destroyed(){
    Echo.leave('user.coins.'+this.user.id);
  },
  mounted(){
    Echo.channel('user.coins.'+this.user.id)
      .listen('.changed', () => {
        if(!this._isDestroyed){
          this.$store.dispatch('FRESH_GLOBAL_DATA');
        }
      });
    window.tempAdminHeader = this;
  }
};
</script>
