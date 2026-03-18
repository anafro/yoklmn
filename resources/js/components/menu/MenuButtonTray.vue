<script setup lang="ts">
import MenuButton from './MenuButton.vue';
</script>

<template>
    <div class="group flex gap-x-4">
        <MenuButton name="Арена" description="Сражайся в общей комнате со всеми, чтобы стань лучшим ёшкой!"
            class="bg-red-500 text-red-950"></MenuButton>

        <MenuButton name="Битва" description="Вступи в схватку один на один с таким же по силе ёшкой как ты!"
            class="bg-orange-500 text-orange-950"></MenuButton>

        <MenuButton name="Друзья" description="Собирай компанию ёшек и играйте все вместе!"
            class="bg-yellow-500 text-yellow-950"></MenuButton>

        <MenuButton name="Тренировка" description="Оттачивай навыки премудорого ёшки в одиночку!"
            class="bg-lime-500 text-lime-950"></MenuButton>

        <MenuButton name="Казуальная" description="Играй в сезонные мини-режимы ЁКЛМН!"
            class="bg-teal-500 text-teal-950"></MenuButton>
    </div>
</template>
