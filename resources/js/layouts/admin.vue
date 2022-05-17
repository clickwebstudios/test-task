<template>
  <div class="layout_main">
    <AdminHeader />
    
    <router-view></router-view>
    <!--
    <admin-footer />
    -->
    
  </div>
</template>
<script>
import AdminHeader from "~/pages/admin/chunks/AdminHeader";
  
export default {
  components: {
    AdminHeader
  }
};
</script>
