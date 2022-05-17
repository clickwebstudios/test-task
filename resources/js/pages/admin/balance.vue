<template>
  <div class="admin_balance_page">
    <br>
    <br>
    <br>
    <br>
    <el-main>
      <el-row :gutter="20">
        <el-col :span="8" :offset="8">
          <BillingFormOnly />
        </el-col>
      </el-row>
      <br>
      <el-row :gutter="20">
        <el-col :span="8" :offset="8">
          <BuyCoins />
        </el-col>
      </el-row>
    </el-main>
  </div>
</template>

<script>
import BillingFormOnly from "~/components/BillingFormOnly";
import BuyCoins from "~/components/BuyCoins";

export default {
  components: {
    BillingFormOnly,
    BuyCoins
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
