<style lang="scss">
.user_parser_log_outer{
    h6{
        text-align: center;
        margin: 0;
    }
}
.log_ul{
  padding:0px;
  margin:0px;
  display: flex;
  flex-direction: column;
  width:100%;
  li{
    list-style: none;
    display: flex;
    width: 100%;
    padding: 5px 0px 10px;
    border-bottom: 1px solid #f6f6f6;
    margin-bottom: 5px;
    & > div{
        margin-right:20px;
    }
  }
}
</style>
<template>
  <div class="user_parser_log_outer">
    <el-card class="box-card" v-loading='loading'>
      <div slot="header" class="clearfix">
        <span>API logs</span>
      </div>
          <ul v-if='logs.length' class='log_ul'>
            <li v-for='log in logs' class='log_li'>
              <div class='date'>
                <b>Date:</b> {{ log.created_at | filterDate }}
              </div>
              <div class='message'>
                <b>Message:</b> {{ log.message }}
              </div>
              <div class='price'>
                <b>Price:</b> {{ log.price }} coin
              </div>
            </li>
          </ul>
        <div v-else>
            <h6>No logs found</h6>
        </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      logs: []
    };
  },
  watch: {

  },
  computed: {
    user(){
      return this.$store.getters.user;
    }
  },
  filters: {
    filterDate(value){
      if(!value){
        return;
      }
      return Vue.prototype.$moment(value).format('lll');
    }
  },
  mounted() {
    window.tempUserParserLog = this;
    this.getUserLogs();
  },
  methods: {
    getUserLogs(){
      this.loading = true;
      this.$http.post(this.$routeLaravel('api.user.logs'))
        .then(response => {
          this.loading = false;
          this.logs = response.data.logs;
        })
        .catch(error => {
          this.loading = false;
        });
    },
  }
};
</script>


