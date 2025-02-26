<script>
export default {
  name: 'AppButton',
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
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    buttonStyle() {
      const baseStyle = 'flex justify-center items-center rounded-xl'
      const disabledStyle = this.disabled ? 'cursor-not-allowed opacity-50' : ''
      switch (this.variant) {
        case 'rounded':
          return `${baseStyle} ${disabledStyle} bg-base-grey hover:bg-base-asphalt text-base-blue font-w400 text-base outline outline-1 outline-base-blue`
        case 'free':
          return `${baseStyle} ${disabledStyle} text-base-light-grey font-w400 text-basehover:outline hover:outline-1 hover:outline-base-light-grey`
        case 'admin':
          return `${baseStyle} ${disabledStyle} bg-base-red text-white font-w500 text-base hover:bg-base-light-red`
        case 'edit':
          return `${baseStyle} ${disabledStyle} bg-base-grey text-white font-w500 text-base hover:bg-base-asphalt`
        default:
          return `${baseStyle} ${disabledStyle} bg-base-blue text-white font-w500 text-base hover:bg-base-light-blue`
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
