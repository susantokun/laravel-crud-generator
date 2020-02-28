<template>
    <div>
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img :src="`/storage/uploads/flags/flag_${locale}.svg`" alt="flag">
            <!-- <span class="ml-2">{{ locale.toUpperCase() }}</span> -->
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <a
                :href="linkEn"
                ref="account"
                class="dropdown-item"
                @keydown.up.exact.prevent=""
                @keydown.tab.exact="focusNext(false)"
                @keydown.down.exact.prevent="focusNext(true)"
                @keydown.esc.exact="hideDropdown"
            >
                <img src="/storage/uploads/flags/flag_en.svg" alt="english flag">
                <span class="ml-2">English</span>
            </a>
            <a
                :href="linkId"
                class="dropdown-item"
                @keydown.shift.tab="focusPrevious(false)"
                @keydown.up.exact.prevent="focusPrevious(true)"
                @keydown.down.exact.prevent=""
                @keydown.tab.exact="hideDropdown"
                @keydown.esc.exact="hideDropdown"
            >
                <img src="/storage/uploads/flags/flag_id.svg" alt="indonesia flag">
                <span class="ml-2">Indonesia</span>
            </a>
        </div>
    </div>
</template>

<script>

export default {
  props: ['locale', 'link-en', 'link-id'],
  data() {
    return {
      isVisible: false,
      focusedIndex: 0,
    }
  },
  methods: {
    toggleVisibility() {
      this.isVisible = !this.isVisible
    },
    hideDropdown() {
      this.isVisible = false
      this.focusedIndex = 0
    },
    startArrowKeys() {
      if (this.isVisible) {
        // this.$refs.account.focus()
        this.$refs.dropdown.children[0].children[0].focus()
      }
    },
    focusPrevious(isArrowKey) {
      this.focusedIndex = this.focusedIndex - 1
      if (isArrowKey) {
        this.focusItem()
      }
    },
    focusNext(isArrowKey) {
      this.focusedIndex = this.focusedIndex + 1
      if (isArrowKey) {
        this.focusItem()
      }
    },
    focusItem() {
      this.$refs.dropdown.children[this.focusedIndex].children[0].focus()
    },
    setLocale(locale) {
      this.$i18n.locale = locale
      this.$router.push({
        params: { lang: locale }
      })
      this.hideDropdown()
    }
  }
}
</script>

<style scoped>
    button {
      padding: 0;
      border: none;
      font: inherit;
      color: inherit;
      background-color: transparent;
      cursor: pointer;
    }

    .flex {
        display: flex;
        align-items: center;
    }

    img {
        width: 1.5rem;
        height: 1.5rem;
    }

    ul {
        list-style-type: none;
        padding-left: 0;
        position: absolute;
        z-index: 30;
        right: 0;
    }

    .relative {
        position: relative;
    }

    .dropdown-fade-enter-active, .dropdown-fade-leave-active {
        transition: all .1s ease-in-out;
    }
    .dropdown-fade-enter, .dropdown-fade-leave-to {
        opacity: 0;
        transform: translateY(-12px);
    }
</style>
