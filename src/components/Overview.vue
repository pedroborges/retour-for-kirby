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

      <k-button-group slot="right">
        <k-icon v-if="loading" type="loader" class="retour-loader" />
      </k-button-group>
    </k-header>

    <license v-if="this.options.license && !hasLicense" @go="go" />

    <template v-for="view in views">
      <component
        v-if="current === view"
        :key="view"
        :ref="view"
        :is="view"
        :options="options"
        :license="hasLicense"
        @add="add"
      />
    </template>

  </k-view>
</template>

<script>

import License from './License.vue';

import Dashboard from './Views/Dashboard.vue';
import Redirects from './Views/Redirects.vue';
import Fails     from './Views/Fails.vue';
import Settings  from './Views/Settings.vue';

export default {
  components: {
    license:   License,
    dashboard: Dashboard,
    redirects: Redirects,
    fails:     Fails,
    settings : Settings
  },
  data() {
    return {
      current: null,
      options: {
        site: null,
        license: null,
        view: null
      },
      loading: false
    }
  },
  computed: {
    hasLicense() {
      return this.options.license.length === 14 &&
             this.options.license.split('-').length === 3;
    },
    views() {
      return ['dashboard', 'redirects', 'fails', 'settings'];
    }
  },
  created() {
    this.$events.$on('retour-load', this.load);
    this.$events.$on('retour-loaded', this.loaded);
    this.fetch();
  },
  destroyed() {
    this.$events.$off('retour-load', this.load);
    this.$events.$off('retour-loaded', this.loaded);
  },
  methods: {
    add(error) {
      this.current = 'redirects';

    },
    fetch() {
      this.$events.$emit('retour-load');
      this.$api.get('retour/system').then(response => {
        this.options = response;
        this.current = this.options.view;

        this.$api.get('retour/load').then(response => {
          this.$events.$emit('retour-loaded');
        });
      });
    },
    go(view) {
      this.current = view;
    },
    load() {
      this.loading = true;
    },
    loaded() {
      this.loading = false;
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

.retour-loader {
  display: block;
  padding: 0 .75rem;

  > svg {
    transform: rotate(-180deg);
    animation: spin 1.5s linear infinite;

    @keyframes spin {
      100% { transform: rotate(180deg); }
    }
  }
}

</style>

