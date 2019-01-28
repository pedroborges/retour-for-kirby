<template>
  <div>

    <section class="k-system-info">
      <header class="k-field-header">
        <label class="k-field-label">{{ options.description }}</label>
      </header>

      <ul class="k-system-info-box">
        <li>
          <dl>
            <dt>{{ $t('license') }}</dt>
            <dd>
              <template v-if="this.license">
                {{ this.options.license || '–' }}
              </template>
              <p v-else>
                <strong class="k-system-unregistered">
                  Unregistered demo
                </strong>
              </p>
            </dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>Installed plugin version</dt>
            <dd>{{ this.options.version }}</dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>Latest plugin version</dt>
            <dd>{{ this.latest }}</dd>
          </dl>
        </li>
      </ul>

      <footer class="k-field-footer">
        <div data-theme="help" class="k-text k-field-help">
          <span v-if="!license">Buy a <a href="https://pay.paddle.com/checkout/550719">plugin license</a>.</span>
          Download <a href="https://github.com/distantnative/kirby-retour/archive/master.zip">latest version</a>.
        </div>
      </footer>
    </section>

    <br />

    <section class="k-system-info">
      <header class="k-field-header">
        <label class="k-field-label">Overview</label>

        <k-button-group>
          <k-button v-if="debug" icon="download" @click="samples">
            Load sample data
          </k-button>
          <k-button icon="trash" theme="negative" @click="flush">
            Clear log
          </k-button>
        </k-button-group>
      </header>

      <ul class="k-system-info-box">
        <li>
          <dl>
            <dt>Redirect routes</dt>
            <dd>{{ this.routes }}</dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>Logged {{ $t('retour.fails') }}</dt>
            <dd>{{ this.fails }}</dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>Successfully redirected</dt>
            <dd>{{ this.redirects }}</dd>
          </dl>
        </li>
      </ul>
      <ul class="k-system-info-box">
        <li>
          <dl>
            <dt>Keep log for</dt>
            <dd>
              6 months
            </dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>Default view</dt>
            <dd>
              dashboard
            </dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>List # items per page</dt>
            <dd>{{ this.options.limit }}</dd>
          </dl>
        </li>
      </ul>

      <footer class="k-field-footer">
        <div data-theme="help" class="k-text k-field-help">
          Learn more about options <a href="https://github.com/distantnative/kirby-retour">in the docs</a>.
        </div>
      </footer>
    </section>

  </div>
</template>

<script>
export default {
  props: {
    options: Object,
    license: Boolean
  },
  data() {
    return {
      routes: '...',
      fails: '...',
      redirects: '...',
      latest: '...'
    }
  },
  computed: {
    debug() {
      return window.panel.debug;
    }
  },
  mounted() {
    this.fetch();
  },
  methods: {
    count(response) {
      if (response.length > 0) {
        this.fails = response.reduce((a, b) => ({fails: a.fails + b.fails})).fails;
        this.redirects = response.reduce((a, b) => ({redirects: a.redirects + b.redirects})).redirects;
      } else {
        this.fails = 0;
        this.redirects = 0;
      }
    },
    fetch() {
      this.$events.$emit('retour-load');
      this.$api.get('retour/redirects').then(response => {
        this.routes = response.length;

        this.$api.get('retour/fails/fails').then(response => {
          this.count(response);

          fetch('https://api.github.com/repos/distantnative/embed/releases/latest', { method: "GET" }).then(response => response.json()).then(response => {
            this.latest = response.name;
            this.$events.$emit('retour-loaded');
          });
        });
      });
    },
    flush() {
      this.$events.$emit('retour-load');
      this.$api.patch('retour/clear').then(() => {
        this.fetch();
      });
    },
    samples() {
      this.$events.$emit('retour-load');
      this.$api.post('retour/samples').then(() => {
        this.fetch();
      });
    }
  }
}
</script>

