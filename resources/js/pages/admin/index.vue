<template>
  <div class="admin_index_page">
    <br>
    <el-main>
      <el-row :gutter="20">
        <el-col :span="24" :offset="0">
          <UserParseLog />
        </el-col>
      </el-row>
    </el-main>
  </div>
</template>

<script>
import UserParseLog from "~/components/UserParseLog";
  
export default {
  components: {
    UserParseLog
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
