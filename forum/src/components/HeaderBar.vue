<script setup>
import Button from '../components/Button.vue'
</script>

<template>
  <header>
    <nav>
      <RouterLink :to="{ path: '/auth', query: { mode: 'login' } }">
        <Button text="Войти" />
      </RouterLink>
      <RouterLink :to="{ path: '/auth', query: { mode: 'register' } }">
        <Button variant="free" text="Создать аккаунт" />
      </RouterLink>
      <Button variant="rounded" text="Сохранить" />
      <Button variant="admin" text="Создать категорию" />
    </nav>
  </header>
</template>
