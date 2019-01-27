<template>
  <k-view class="k-retour-view">
    <k-header>
      <div class="retour-header">
        <k-icon type="retour" size="medium" />
        {{ $t('view.retour') }}
      </div>

      <k-button-group slot="left">
        <k-button
          v-for="view in views"
          :key="view"
          :icon="'retour-' + view"
          :current="current === view"
          @click="go(view)"
        >
          {{ $t('retour.' + view)}}
        </k-button>
      </k-button-group>

    </k-header>

    <template v-for="view in views">
      <component
        v-if="current === view"
        :key="view"
        :ref="view"
        :is="view"
        @add="add"
      />
    </template>

  </k-view>
</template>

<script>

import Dashboard from './Views/Dashboard.vue';
import Redirects from './Views/Redirects.vue';
import Fails     from './Views/Fails.vue';
import Settings  from './Views/Settings.vue';

export default {
  components: {
    dashboard: Dashboard,
    redirects: Redirects,
    fails:     Fails,
    settings : Settings
  },
  data() {
    return {
      current: null,
      license: null,
      options: null
    }
  },
  computed: {
    views() {
      return ['dashboard', 'redirects', 'fails', 'settings'];
    }
  },
  created() {
    this.$api.get('retour/system').then(response => {
      this.license = response.license;
      this.options = response.options;
      this.current = this.options.view;
    });
  },
  methods: {
    add(error) {
      this.current = 'redirects';
      this.$nextTick(() => {
        this.$refs.redirects.add(error);
      });
    },
    go(view) {
      this.current = view;
      this.$events.$emit('retour-go', view);
    }
  }
}
</script>

<style lang="scss">

.retour-header {
  display: flex;

  > .k-icon {
    padding-right: .5em;
  }
}

.k-retour-view {
  [aria-current]:not([aria-current="false"]) {
    color: #4271ae;
  }
}

</style>

