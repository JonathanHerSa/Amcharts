<script setup>
import { onMounted, ref } from "vue";
import Grafico from "./Amcharts/Grafico.vue";
import GraficaBarras from "./Amcharts/GraficaBarras.vue";
import GraficaDona from "./Amcharts/GraficaDona.vue";
import Anotaciones from "./Amcharts/Anotaciones.vue";

const props = defineProps(["datos"]);
const key = ref(0);
/* onMounted(() => {
    Echo.channel("items").listen("UpdateChart", (e) => {
        setTimeout(() => {
            key.value += 1;
        }, 1250);
    });
}); */
</script>
<template>
    <div
        class="bg-gradient-to-br from-red-100 to-cyan-100 w-screen h-screen flex flex-col gap-4 p-4"
    >
        <!-- <GraficaBarras :datos="datos" /> -->
        <!-- <Grafico :datos="datos" :key="key" /> -->
        <!-- <GraficaDona /> -->
        <Anotaciones :datos="datos" />
    </div>
</template>
