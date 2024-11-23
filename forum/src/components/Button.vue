<script>
export default {
  name: 'Button',
  props: {
    variant: {
      type: String,
      default: 'default',
    },
    paddingX: {
      type: String,
      default: '24px',
    },
    paddingY: {
      type: String,
      default: '8px',
    },
    text: {
      type: String,
      required: true,
    },
  },
  computed: {
    buttonStyle() {
      switch (this.variant) {
        case 'rounded':
          return 'bg-base-grey hover:bg-base-asphalt text-base-blue font-w400 text-base rounded-xl outline outline-1 outline-base-blue'
        case 'free':
          return 'text-base-light-grey font-w400 text-base rounded-xl hover:outline hover:outline-1 hover:outline-base-light-grey'
        case 'admin':
          return 'bg-base-red text-white font-w500 text-base rounded-xl hover:bg-base-light-red'
        default:
          return 'bg-base-blue text-white font-w500 text-base rounded-xl hover:bg-base-light-blue'
      }
    },
  },
}
</script>

<template>
  <button :class="buttonStyle" :style="{ padding: `${paddingY} ${paddingX}` }">
    {{ text }}
  </button>
</template>
