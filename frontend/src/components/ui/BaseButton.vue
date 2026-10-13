<script setup lang="ts">
withDefaults(
  defineProps<{
    variant?: 'primary' | 'secondary' | 'ghost'
    type?: 'button' | 'submit' | 'reset'
    block?: boolean
    disabled?: boolean
  }>(),
  { variant: 'primary', type: 'button', block: false, disabled: false }
)
</script>

<template>
  <button
    :type="type"
    :disabled="disabled"
    class="inline-flex items-center justify-center gap-2 rounded-[10px] px-5 py-3.5 text-[15px] font-medium transition-colors whitespace-nowrap disabled:opacity-50"
    :class="[
      block && 'w-full',
      variant === 'primary' && 'bg-purple text-white hover:bg-purple-hover',
      variant === 'secondary' &&
        'bg-bg-elevated text-white border border-border-light hover:bg-bg-card-hover',
      variant === 'ghost' &&
        'bg-transparent text-white border border-border-light hover:bg-bg-elevated',
    ]"
  >
    <slot />
  </button>
</template>
