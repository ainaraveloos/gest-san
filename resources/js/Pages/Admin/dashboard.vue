<template>
    <div
        class="grid gap-4 mb-6 grid-cols-[repeat(auto-fit,minmax(250px,1fr))] text-white"
    >
        <div
            class="bg-blue-600 h-40 content-center text-center shadow-sm rounded-lg"
        >
            <h1>1</h1>
        </div>
        <div
            class="bg-green-400 h-40 content-center text-center  shadow-sm rounded-lg"
        >
            <h1>2</h1>
        </div>
        <div
            class="bg-purple-400 h-40 content-center text-center  shadow-sm rounded-lg"
        >
            <h1>3</h1>
        </div>
        <div
            class="bg-orange-400 h-40 content-center text-center  shadow-sm rounded-lg"
        >
            <h1>4</h1>
        </div>
    </div>
    <div class="grid md:grid-cols-12 grid-cols-1 gap-4">
        <div class="h-96 md:col-span-7 col-span-full flex-grow rounded-lg bg-white shadow-sm content-center text-center ">
            <h1 class="">5</h1>
        </div>
        <div class="h-96 bg-white md:col-span-5 col-span-full rounded-lg shadow-sm content-center text-center ">
            <h1 class="">6</h1>
        </div>
    </div>
</template>

<script setup>
import { defineOptions } from "vue";
import TestLayout from "@/Layouts/TestLayout.vue";

defineOptions({
    layout: TestLayout,
});
</script>

<style></style>
