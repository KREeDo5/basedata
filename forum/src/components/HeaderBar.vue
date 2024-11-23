<script setup>
import Button from '../components/Button.vue'
</script>

<template>
  <header>
    <nav>
      <RouterLink to="/">
        <Button text="Войти" />
      </RouterLink>
      <RouterLink to="/about">
        <Button variant="free" text="Создать аккаунт" />
      </RouterLink>
      <Button variant="rounded" text="Сохранить" />
      <Button variant="admin" text="Создать категорию" />
    </nav>
  </header>
</template>
