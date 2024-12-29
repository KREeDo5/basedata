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
      const baseStyle = 'flex justify-center items-center rounded-xl'
      switch (this.variant) {
        case 'rounded':
          return `${baseStyle} bg-base-grey hover:bg-base-asphalt text-base-blue font-w400 text-base outline outline-1 outline-base-blue`
        case 'free':
          return `${baseStyle} text-base-light-grey font-w400 text-basehover:outline hover:outline-1 hover:outline-base-light-grey`
        case 'admin':
          return `${baseStyle} bg-base-red text-white font-w500 text-base hover:bg-base-light-red`
        case 'edit':
          return `${baseStyle} bg-base-grey text-white font-w500 text-base hover:bg-base-asphalt`
        default:
          return `${baseStyle} bg-base-blue text-white font-w500 text-base hover:bg-base-light-blue`
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
