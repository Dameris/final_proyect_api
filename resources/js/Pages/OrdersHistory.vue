<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  orders: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <AppLayout>
    <div class="orderHistory__container">
      <h1>Order History</h1>

      <div v-if="orders.length === 0" class="orderHistory__noOrders">
        <p>No orders found.</p>
      </div>

      <div v-else>
        <div v-for="order in orders" :key="order.id" class="orderHistory__order--item">
          <p><strong>Order #{{ order.id }}</strong></p>
          <p>Status: <span :class="order.status === 'Completed' ? 'status-completed' : 'status-pending'">{{ order.status }}</span></p>
          <p>Total Price: <strong>{{ order.total_price }}€</strong></p>
          <p>Items:</p>
          <ul>
            <li v-for="item in order.order_items" :key="item.id">
              {{ item.quantity }} x <strong>{{ item.tshirt.tshirt_name }}</strong> ({{ item.price }}€ each)
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@import "../../css/pages/orderHistory.css";
</style>
