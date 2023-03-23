<script setup>
const props = defineProps({
  links: {
    type: Array,
    required: false,
    default: () => [],
  },
});
const emit = defineEmits(['change']);
const changePage = (link) => {
  // リンク先のURLが存在しない、または現在表示しているページを押下しても何もしない
  if (!link.url || link.active) return;
  const page = link.url.substring(link.url.indexOf('=') + 1);
  emit('change', page);
};
const linkLabel = (label) => {
  return label.replace('&laquo;', '<<').replace('&raquo;', '>>');
};
</script>

<template>
  <nav class="pagination-nav" aria-label="...">
    <ul class="pagination">
      <li
        v-for="link in links"
        :key="link.label"
        class="page-item"
        :class="{
          disabled: link.url === null,
          active: link.active,
        }"
        :tabindex="link.url === null ? '-1' : ''"
        @click.prevent="changePage(link)"
      >
        <a href="#" class="page-link">
          {{ linkLabel(link.label) }}
        </a>
      </li>
    </ul>
  </nav>
</template>

<style scoped>
.page-link {
  user-select: none;
}
.page-item:last-child .page-link {
  border-top-right-radius: 0.25rem;
  border-bottom-right-radius: 0.25rem;
}
</style>
