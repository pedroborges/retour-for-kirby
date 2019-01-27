<template>
  <div>
    <header class="k-field-header">
      <label class="k-field-label">
        {{ $t('retour.timeline') }} {{ headline }}
      </label>

      <k-button-group>
        <k-button icon="angle-left"  @click="prev" />
        <k-button
          icon="calendar"
          :current="view === 'month'"
          @click="show('month')"
        >
          {{ $t('retour.timeline.month') }}
        </k-button>
        <k-button
          icon="dashboard"
          :current="view === 'week'"
          @click="show('week')"
        >
          {{ $t('retour.timeline.week') }}
        </k-button>
        <k-button
          icon="clock"
          :current="view === 'day'"
          @click="show('day')"
        >
          {{ $t('retour.timeline.day') }}
        </k-button>
        <k-button icon="angle-right" :disabled="offset >= 0" @click="next" />
      </k-button-group>
    </header>

    <div class="k-card k-card-content">
      <timeline
        :height="80"
        :chart-data="timeline"
        :options="options"
      />
    </div>
  </div>
</template>

<script>

import Line from './../Charts/Line.vue';

export default {
  components: {
    timeline: Line
  },
  data () {
    return {
      timeline: null,
      headline: null,
      view: 'month',
      offset: 0
    }
  },
  computed: {
    options() {
      return {
        legend: false,
				tooltips: false,
				scales: {
					yAxes: [{
            stacked: true,
						display: true,
						ticks: {
              min: 0,
              suggestedMax: 5
						}
					}]
				}
			};
    }
  },
  mounted() {
    this.fetch();
  },
  methods: {
    fetch() {
      this.headline = '...';
      this.$api.get('retour/stats/' + this.view + '/' + this.offset).then(response => {
        this.headline = response.headline;
        this.timeline = {
          labels: response.labels,
          datasets: [
            {
              label: this.$t('retour.redirects'),
              backgroundColor: '#4271ae',
              data: response.redirects,
              pointRadius: 0
            },
            {
              label: this.$t('retour.errors'),
              backgroundColor: '#f5871f',
              data: response.fails,
              pointRadius: 0
            }
          ],
        }
      });
    },
    prev() {
      this.offset -= 1;
      this.fetch();
    },
    next() {
      this.offset += 1;
      this.fetch();
    },
    show(view) {
      this.view = view;
      this.offset = 0;
      this.fetch();
    }
  },
}
</script>
