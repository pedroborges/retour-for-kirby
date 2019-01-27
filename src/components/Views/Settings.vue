<template>
  <div>
    <k-button-group>
      <k-button icon="download" @click="$api.post('retour/samples')">
        Load samples
      </k-button><br>
      <k-button icon="trash" theme="negative">
        Flush logs
      </k-button>
    </k-button-group>
  </div>
</template>
