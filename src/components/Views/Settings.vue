<template>
  <div>

    <header class="k-field-header">
      <label class="k-field-label">{{ options.description }}</label>

      <k-button-group>
        <k-button
          icon="download"
          @click="$api.post('retour/samples')"
        >
          Load samples
        </k-button>
        <k-button icon="trash" theme="negative">
          Clear logs
        </k-button>
      </k-button-group>
    </header>

    <section class="k-system-info">
      <ul class="k-system-info-box">
        <li>
          <dl>
            <dt>{{ $t('license') }}</dt>
            <dd>
              <template v-if="this.license">
                {{ this.options.license }}
              </template>
              <p v-else>
                <strong class="k-system-unregistered">
                  This is an unregistered demo of Retour for Kirby
                </strong>
              </p>
            </dd>
          </dl>
        </li>
        <li>
          <dl>
            <dt>{{ $t('version') }}</dt>
            <dd>{{ this.options.version }}</dd>
          </dl>
        </li>
      </ul>
    </section>

  </div>
</template>

<script>
export default {
  props: {
    options: Object,
    license: Boolean
  }
}
</script>

