<template>
  <k-box theme="positive" class="license">
    <k-grid>
      <k-column width="3/4">
        <b>{{ $t('license.register.label') }}.</b><br />
        {{ $t('retour.license') }}
      </k-column>
      <k-column width="1/4">
        <k-button icon="cart" link="https://pay.paddle.com/checkout/550719" target="_blank" class="buy">{{ $t('license.buy') }}</k-button>
      </k-column>
    </k-grid>
  </k-box>
</template>


<style lang="scss">
  .k-retour-view {
    .license {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .buy {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      right: 1rem;
      padding: .5em 1em;
      color: #fff;
      background: #16171a;

      &:hover {
        background: rgba(#16171a, .9);
      }
    }
  }
</style>
