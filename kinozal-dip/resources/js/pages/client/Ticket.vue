<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'Ticket',
  
  setup() {
    const ticket = ref(null);
    const qrCodeUrl = ref('');

    // Получить uuid из маршрута
    const route = useRoute();  // получаем текущий маршрут
    const uuid = route.params.uuid; // предполагаем, что в маршруте /ticket/:uuid

    // onMounted(async () => {
    //   try {
    //     const response = await axios.get(`http://127.0.0.1:8000/tickets/${uuid}`);
    //     ticket.value = response.data.ticket;
    //     qrCodeUrl.value = response.data.qr_code_url;
    //   } catch (error) {
    //     console.error('Ошибка при загрузке билета:', error);
    //   }
    // });

    return { ticket, qrCodeUrl };
  },
  data() {
    return { // тут состояние 
      qrCodeData: '',
      seats: [],
    }
  },
  methods: {
    async  blockedSeats() {
      await axios.get('http://127.0.0.1:8000/seats')
        .then(response => {
          this.seats = response.data.filter((el) => el.status ===  "blocked" );
          console.log('забронированные места:', this.seats);
        });
    },
    async generateQrWithSeats() {
      try {
        await this.blockedSeats();
        console.log('seats перед сериализацией:', this.seats);
        const seatsData = JSON.stringify(this.seats);
        console.log('сериализованные seats:', seatsData);
        const response = await axios.post('http://127.0.0.1:8000/get-qr-code', { seats: seatsData });
        this.qrCodeData = response.data.qr_code;
      } catch (error) {
        console.error('Ошибка при генерации QR:', error);
      }
    },
  },
  mounted() {
    // this.blockedSeats(); // получаем список забранированных сидений
    this.generateQrWithSeats();
  },
}
</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>
  
  <main>
    <section class="ticket">
      
      <header class="tichet__check">
        <h2 class="ticket__check-title">Электронный билет</h2>
      </header>
      
      <div class="ticket__info-wrapper">
        <p class="ticket__info">На фильм: <span class="ticket__details ticket__title">Звёздные войны XXIII: Атака клонированных клонов</span></p>
        <p class="ticket__info">Места: <span class="ticket__details ticket__chairs">6, 7</span></p>
        <p class="ticket__info">В зале: <span class="ticket__details ticket__hall">1</span></p>
        <p class="ticket__info">Начало сеанса: <span class="ticket__details ticket__start">18:30</span></p>

        <!-- <img class="ticket__info-qr" src="/src/client/qr-code.png"> -->
        <div>
          <h1>QR Code: </h1>
          <img :src="qrCodeData" alt="QR Code" />
        </div>

        <!-- <div v-if="ticket">
          <h2>Ваш билет</h2>
          <p>Номер билета: {{ ticket.id }}</p>
          <img :src="qrCodeUrl" alt="QR-код" />
        </div>
        <div v-else>
          <p>Загрузка билета...</p>
        </div> -->

        <p class="ticket__hint">Покажите QR-код нашему контроллеру для подтверждения бронирования.</p>
        <p class="ticket__hint">Приятного просмотра!</p>
      </div>
    </section>     
  </main>

</template>

<style>
@charset "UTF-8";

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  font-family: "Roboto", sans-serif;
  font-size: 62.5%;
}

body.page-client {
  background-image: url("/src/client/background.jpg");
  background-size: cover;
  background-attachment: fixed;
  background-position: right;
}

.page-header {
  padding: 1.4rem;
}

.page-header__title {
  margin: 0;
  font-weight: 900;
  font-size: 3.4rem;
  color: #FFFFFF;
  text-transform: uppercase;
}

.page-header__title span {
  font-weight: 100;
}

/* ~~~~~~~~~~~~ Билет ~~~~~~~~~~~~ */
.tichet__check,
.ticket__info-wrapper {
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  background-color: rgba(241, 235, 230, 0.95);
}

.tichet__check {
  position: relative;
  padding-top: 2.5rem;
  padding-bottom: 2.5rem;
}

.tichet__check::before {
  content: '';
  display: block;
  position: absolute;
  top: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-top.png);
  background-size: 10px 3px;
}

.tichet__check::after {
  content: '';
  display: block;
  position: absolute;
  bottom: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-bottom.png);
  background-size: 10px 3px;
}

.ticket__info-wrapper {
  position: relative;
  margin-top: 6px;
  padding-top: 2rem;
  padding-bottom: 3rem;
}

.ticket__info-wrapper::before {
  content: '';
  display: block;
  position: absolute;
  top: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-top.png);
  background-size: 10px 3px;
}

.ticket__info-wrapper::after {
  content: '';
  display: block;
  position: absolute;
  bottom: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-bottom.png);
  background-size: 10px 3px;
}

.ticket__check-title {
  font-weight: 700;
  font-size: 2.2rem;
  text-transform: uppercase;
  color: #C76F00;
}

.ticket__info {
  font-size: 1.6rem;
  color: #000000;
}

.ticket__info+.ticket__info {
  margin-top: 1rem;
}

.ticket__details {
  font-weight: 700;
}

.ticket__hint {
  font-weight: 300;
  font-size: 1.4rem;
  margin-top: 3rem;
}

.ticket__hint+.ticket__hint {
  margin-top: 1rem;
}

.ticket__info-qr {
  display: block;
  margin: 3rem auto 0;
  width: 20rem;
  height: 20rem;
}

@media screen and (min-width: 479px) {
  .page-nav .page-nav__day {
    padding: 1rem 2rem;
  }
}

@media screen and (min-width: 990px) {

  .page-header,
  nav,
  main {
    width: 990px;
    margin: auto;
  }

  .movie,
  .buying {
    border-radius: 2px;
  }

  .buying__info-hint {
    display: none;
  }
}</style>