<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted } from "vue";

const { props } = usePage();

const tshirts = computed(() => props.tshirts || []);

const events = computed(() => props.events || []);

// Frases para el carrousel
const phrases = [
  "Upgrade your wardrobe with our exclusive designs.",
  "Where urban meets the sky. Elevate your fashion.",
  "New styles arriving every month!",
  "Urban streetwear that feels like the Sky is the limit.",
];

// Estado para el texto actual
const currentPhrase = ref(0);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("es-ES", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};

// Cambiar frase cada 3 segundos
onMounted(() => {
  setInterval(() => {
    currentPhrase.value = (currentPhrase.value + 1) % phrases.length;
  }, 3000);
});
</script>

<template>
  <AppLayout>
    <!-- Carrousel de Texto -->
    <div class="home__carousel">
      <p>{{ phrases[currentPhrase] }}</p>
    </div>

    <!-- Novedades -->
    <div class="home__featured">
      <h2>FEATURED PRODUCT</h2>
      <div v-for="tshirt in tshirts" :key="tshirt.id">
        <Link :href="route('tshirts.show', tshirt.id)">
          <div class="home__featuredImages">
            <img
              :src="'storage/img/tshirts/' + tshirt.tshirt_img1"
              alt="Tshirt Image 1"
              class="home__featured--img"
            />
            <img
              :src="'storage/img/tshirts/' + tshirt.tshirt_img2"
              alt="Tshirt Image 2"
              class="home__featured--img"
            />
          </div>
        </Link>
      </div>
      <Link class="home__featured--more" :href="route('tshirts.index')"> VIEW MORE </Link>
    </div>

    <!-- Promoción -->
    <div class="home__promoBanner">
      <h2>LIMITED TIME OFFER!</h2>
      <p>
        GET 20% OFF ON YOUR FIRST ORDER. <br />
        USE CODE: SKY20
      </p>
    </div>

    <!-- Eventos -->
    <div class="home__events">
      <h2>UPCOMING EVENTS</h2>

      <div class="home__events--list">
        <Link
          v-for="event in events"
          :key="event.id"
          :href="route('events.show', event.id)"
          class="home__eventCard"
        >
          <div class="home__eventColor" :style="{ backgroundColor: event.color }"></div>

          <div class="home__eventInfo">
            <h3>{{ event.title }}</h3>
            <p>{{ formatDate(event.start_date) }}</p>
            <p v-if="event.location">{{ event.location }}</p>
          </div>
        </Link>
      </div>

      <Link class="home__events--more" :href="route('events.index')"> VIEW EVENTS </Link>
    </div>

    <!-- Testimonios -->
    <div class="home__testimonials">
      <h2>CUSTOMERS TESTIMONIALS</h2>
      <div class="home__testimonials--list">
        <div class="home__testimonials--opinion">
          <p>"SkyUrban's collection is amazing! I love the quality and the style."</p>
          <p>- John D.</p>
        </div>
        <div class="home__testimonials--opinion">
          <p>"I get compliments every time I wear one of their shirts!"</p>
          <p>- Sarah K.</p>
        </div>
        <div class="home__testimonials--opinion">
					<p>"I have never received so many compliments on my outfit. SkyUrban knows how to make great clothes!"</p>
          <p>- Michael T.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@import "../../css/pages/home.css";
</style>
