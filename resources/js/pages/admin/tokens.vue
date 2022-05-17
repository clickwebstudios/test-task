<template>
  <div class="admin_tokens_page">
    <br>
    <el-main>
      <el-row :gutter="20">
        <el-col :span="24" :offset="0">
          <UserTokens />
        </el-col>
      </el-row>
      
      <br>
      <el-row :gutter="20">
        <el-col :span="24" :offset="0">
          <UserApi />
        </el-col>
      </el-row>
      
    </el-main>
  </div>
</template>

<script>
import UserTokens from "~/components/UserTokens";
import UserApi from "~/components/UserApi";

export default {
  components: {
    UserTokens,
    UserApi
  },
  data () {
    return {
     
    };
  },
  computed: {
    
  },
  mounted(){
    
  },
  methods:{
    
  },
};
</script>
