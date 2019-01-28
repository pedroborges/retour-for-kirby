<template>
  <div>
    <header class="k-field-header">
      <k-button-group>
        <k-button icon="circle" class="retour-redirects">
          {{ $t('retour.redirects') }}
        </k-button>
        <k-button icon="circle" class="retour-fails">
          {{ $t('retour.fails') }}
        </k-button>
      </k-button-group>
    </header>

    <div class="k-card k-card-content">
      <pie :height="350" :chart-data="data" :options="options" />
    </div>
  </div>
</template>

<script>

import getGradients from './../../lib/gradients.js';
import Pie  from './Pie.vue';

export default {
  components: {
    pie: Pie
  },
  props: {
    response: Object
  },
  data () {
    return {
      data: null
    }
  },
  computed: {
    options() {
      return {
        legend: false,
      };
    }
  },
  watch: {
    response(response) {
      let gradients = getGradients(document, 'pie-chart');

      this.data = {
        labels: [this.$t('retour.redirects'), this.$t('retour.fails')],
        datasets: [
          {
            data: [
              response.redirects.reduce((sum, x) => sum + x),
              response.fails.reduce((sum, x) => sum + x)
            ],
            backgroundColor: [gradients.blue, gradients.grey],
            hoverBackgroundColor: ['#4271ae', '#ccc'],
            borderWidth: [1, 1]
          }
        ],
      }
    }
  }
}
</script>

<style lang="scss">
  .k-button.retour-redirects {
    pointer-events: none;

    .k-icon {
      color: #4271ae;
    }
  }

  .k-button.retour-fails {
    pointer-events: none;

    .k-icon {
      color: #aaa;
    }
  }
</style>

