<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3"; // Pasamos a la importación moderna v1+
import axios from "axios";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    cart: {
        type: Array,
        default: () => []
    }
});

const localCart = ref([]);

// Formulario reactivo para la dirección (Exigencia DIW)
const shippingForm = useForm({
    fullName: "",
    address: "",
    city: "",
    zipCode: "",
    phone: "",
});

watch(() => props.cart, (newCart) => {
    localCart.value = newCart;
}, { immediate: true });

const fetchCart = async () => {
    try {
        const response = await axios.get("/api/cart");
        localCart.value = response.data || [];
    } catch (error) {
        console.error("Error fetching cart:", error);
    }
};

const getMaxStock = (item) => {
    if (!item.product || !item.product.stocks) return item.quantity;
    const sizeRecord = item.product.stocks.find(s => s.size === item.size);
    const backendStock = sizeRecord ? sizeRecord.stock : 0;
    return item.quantity + backendStock;
};

const updateQuantity = async (itemId, quantity) => {
    if (quantity < 1) return;

    router.put(`/cart/${itemId}`, { quantity }, {
        preserveScroll: true,
        onSuccess: () => {
            if (!props.cart || props.cart.length === 0) fetchCart();
        },
        onError: (errors) => {
            console.error(errors);
            window.emitter.emit('trigger-alert', { 
                message: "Could not update quantity. Check stock availability.", 
                type: "error" 
            });
            fetchCart();
        }
    });
};

const fixQuantity = (item) => {
    const maxAllowed = getMaxStock(item);
    
    if (item.quantity < 1 || isNaN(item.quantity)) {
        item.quantity = 1;
    } else if (item.quantity > maxAllowed) {
        // Bug corregido: Cambiado comillas simples por Backticks para que interpole las variables
        window.emitter.emit('trigger-alert', { 
            message: `Sorry, there are only ${maxAllowed} units available in total for size ${item.size}.`, 
            type: "error" 
        });
        item.quantity = maxAllowed;
    }
};

const removeFromCart = async (itemId) => {
    router.delete(`/cart/${itemId}`, {
        preserveScroll: true,
        onSuccess: () => {
            if (!props.cart || props.cart.length === 0) fetchCart();
        }
    });
};

const removeAllFromCart = () => {
    window.emitter.emit('trigger-confirm', {
        message: "Are you sure you want to empty your cart?",
        onConfirm: () => {
            router.delete("/cart", {
                preserveScroll: true,
                onSuccess: () => {
                    localCart.value = [];
                    window.emitter.emit('trigger-alert', { 
                        message: "Your cart has been successfully emptied.", 
                        type: "success" 
                    });
                }
            });
        }
    });
};

// Checkout procesando los nuevos datos de dirección
const checkout = () => {
    // Validación del formulario de envío previa a la compra
    if (!shippingForm.fullName || !shippingForm.address || !shippingForm.city || !shippingForm.zipCode || !shippingForm.phone) {
        window.emitter.emit('trigger-alert', { 
            message: "Please complete all shipping details before checked out.", 
            type: "error" 
        });
        return;
    }

    // Enviamos el carro junto con los datos de envío al backend
    router.post("/checkout", {
        cart: localCart.value,
        shipping_details: shippingForm.data()
    }, {
        onSuccess: () => {
            fetchCart();
            shippingForm.reset();
            window.emitter.emit('trigger-alert', { 
                message: "ORDER PLACED SUCCESSFULLY!", 
                type: "success" 
            });
        },
    });
};

onMounted(() => {
    if (!props.cart || props.cart.length === 0) {
        fetchCart();
    }
});
</script>

<template>
    <AppLayout>
        <div v-if="localCart.length > 0" class="shoppingCart__wrapper">
            <h2>Your Shopping Cart</h2>
            
            <div class="shoppingCart__layout">
                <div class="shoppingCart__productsSection">
                    <button @click="removeAllFromCart" class="shoppingCart__removeAll">
                        EMPTY CART
                    </button>
                    
                    <ul class="shoppingCart__list">
                        <li v-for="item in localCart" :key="item.id" class="shoppingCart__list--item">
                            <div class="shoppingCart__list--itemDetails">
                                <span class="shoppingCart__list--itemName">
                                    {{ item.product?.tshirt_name ?? item.product?.jogger_name ?? 'Product removed' }}
                                </span>
                                <span class="shoppingCart__list--itemMeta">Size: {{ item.size }}</span>
                                <span class="shoppingCart__list--itemPrice">
                                    €{{ item.product?.tshirt_price ?? item.product?.jogger_price ?? 0 }}
                                </span>
                            </div>
                            <div class="shoppingCart__list--itemAction">
                                <input
                                    type="number"
                                    min="1"
                                    :max="getMaxStock(item)" 
                                    v-model.number="item.quantity" 
                                    @input="fixQuantity(item)"
                                    @change="updateQuantity(item.id, item.quantity)"
                                    class="shoppingCart__list--itemAction__quantity"
                                />
                                <button @click="removeFromCart(item.id)" class="shoppingCart__list--itemAction__remove">
                                    Remove
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="shoppingCart__summarySection">
                    <div class="shippingForm__card">
                        <h3>Shipping Details</h3>
                        <form @submit.prevent="checkout" class="shippingForm">
                            <div class="shippingForm__group">
                                <label>Full Name</label>
                                <input type="text" v-model="shippingForm.fullName" placeholder="John Doe" required />
                            </div>
                            <div class="shippingForm__group">
                                <label>Address</label>
                                <input type="text" v-model="shippingForm.address" placeholder="St. Urban Avenue, 42" required />
                            </div>
                            <div class="shippingForm__row">
                                <div class="shippingForm__group">
                                    <label>City</label>
                                    <input type="text" v-model="shippingForm.city" placeholder="Madrid" required />
                                </div>
                                <div class="shippingForm__group">
                                    <label>ZIP Code</label>
                                    <input type="text" v-model="shippingForm.zipCode" placeholder="28001" required />
                                </div>
                            </div>
                            <div class="shippingForm__group">
                                <label>Phone Number</label>
                                <input type="tel" v-model="shippingForm.phone" placeholder="+34 600 000 000" required />
                            </div>
                        </form>
                    </div>

                    <div class="summaryCart__card">
                        <div class="shoppingCart__totalPrice">
                            <span>Total Amount:</span>
                            <strong>
                                {{
                                    localCart.reduce(
                                        (sum, item) => sum + (item.product?.tshirt_price ?? item.product?.jogger_price ?? 0) * item.quantity,
                                        0,
                                    )
                                }}€
                            </strong>
                        </div>
                        <button @click="checkout" class="shoppingCart__checkout">
                            PROCEED TO CHECKOUT
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-else class="shoppingCart__emptyCart">
            <p>YOUR CART IS EMPTY.</p>
        </div>
    </AppLayout>
</template>

<style scoped>
@import "../../css/pages/shoppingCart.css";
</style>