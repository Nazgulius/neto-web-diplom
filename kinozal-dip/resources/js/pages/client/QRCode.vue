<template>
  <div>
    <img :src="qrCodeData" alt="QR Code" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      qrCodeData: ''
    }
  },
  mounted() {
    // Получаем QR-код с сервера
    fetch('/api/get-qr-code')
      .then(res => res.json())
      .then(data => {
        this.qrCodeData = data.qr_code; // строка с base64 изображения
      });
  }
}
</script>